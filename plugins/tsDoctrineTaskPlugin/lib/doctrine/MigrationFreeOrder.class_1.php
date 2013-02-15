<?php
/**
 * Миграция для нескольких источников в рандомном порядке
 * 
 * @author Aliaksei Shytkin <e79eas@gmail.com>
 * @version $Id: MigrationFreeOrder.class.php 93 2010-04-01 17:21:16Z alexeis $
 * @package Doctrine
 * @subpackage Migration 
 *
 */

/**
 * Класс миграции
 *
 */
class Doctrine_MigrationFreeOrder extends Doctrine_Migration{


    /**
     * Имя таблицы для хранения версий
     *
     * @var string
     */
    protected $_migrationTableName = 'migration_version_timestamp';

    /**
     * Устанавливаем примененные версии timestamp
     * 
     * Если направление применения вверх, то записываем в табицу
     * В противном случае удаляем из таблицы
     *
     * @param array $timestamps
     * @param string $direction
     */
    public function setAppliedTimestamps($timestamps = array(), $direction = 'up')
    {
        $conn = $this->getConnection();
        foreach ($timestamps as $timestamp)
        {
          
          if ($direction == 'up')
          {
            $conn->exec("INSERT INTO " . $this->_migrationTableName . " (version) VALUES ($timestamp)");
          }
          else
          {
            $conn->execute("DELETE FROM " . $this->_migrationTableName.' WHERE version="'.$timestamp.'"');
          }
        }
    }

    /**
     * Получаем список примененных версий транзакций из базы данных 
     *
     * @return array
     */
    public function getAppliedTimestamps()
    {
        $conn = $this->getConnection();

        $result = $conn->fetchColumn("SELECT version FROM " . $this->_migrationTableName.' ORDER BY version');

        return $result;
    }
    
    /**
     * Получаем список всех доступных версий из классов миграций
     *
     * @return array
     */
    public function getAllTimestamps()
    {
        
        $versions = array_keys($this->_migrationClasses);
        sort($versions);

        return $versions;
    }
    
    /**
     * Получение текущей версии
     *
     * @return integer
     */
    public function getCurrentVersion()
    {

        $this->_createMigrationTable();

        $conn = $this->getConnection();

        $result = $conn->fetchColumn("SELECT MAX(version) FROM " . $this->_migrationTableName);

        return isset($result[0]) ? $result[0]: 0;
    }    
    
    
    /**
     *
     * Осуществляем миграцию
     * 
     * Поскольку нельзя сделать транзакциями изменение структуры базы типа добавления
     * таблицы и ключей, а сами миграции слабо связаны между собой, то для каждой
     * миграции запускается отдельная транзакция в отличие от реализации в родительском
     * классе, где все в одной транзации, которая выполнялась если внутри нее не было ошибок. 
     * В любом случае, случились ли ошибки или нет, записываем в базу только те 
     * транзакции, которые прошли корректно.
     * 
     * @param  integer $to       Version to migrate to
     * @param  boolean $dryRun   Whether or not to run the migrate process as a dry run
     * @return integer $to       Version number migrated to
     * @throws Doctrine_Exception
     */
    public function migrate($to = null, $dryRun = false)
    {
        
        $this->clearErrors();

        $this->_createMigrationTable();
        
        // If nothing specified then lets assume we are migrating from
        // the current version to the latest version
        if ($to === null)
        {
            $to = $this->getLatestVersion();
        }
        
        $migrationData = $this->_getDirectionAndTimestamps($to);
        
        $direction = $migrationData['direction'];
        $unAppliedTimestamps = $migrationData['timestamps'];
        
        foreach ($unAppliedTimestamps as $timestamp) {

          try {
            
            $this->_connection->beginTransaction();
            
            $this->_doMigrateStep($direction, $timestamp);

            $migrationData['timestamps_applied'][] = $timestamp;
            $this->_connection->commit();

          } catch (Exception $e) {
            
            $this->addError($e);
            
            $this->_connection->rollback();
          }  
        }

        $this->setAppliedTimestamps($migrationData['timestamps_applied'], $migrationData['direction']);        

        if ($this->hasErrors()) {
            

            if ($dryRun) {
                return false;
            } else {
                $this->_throwErrorsException();
            }
        } else {
            if ($dryRun) {
                
                if ($this->hasErrors()) {
                    return false;
                } else {
                    return $to;
                }
            } else {
                
                return $to;
            }
        }
        return false;
    }    
    

    /**
     * Perform a single migration step. Executes a single migration class and
     * processes the changes
     * 
     * Переопределяем чтобы перехватывать эксепшены выше в вызове и узнавать, корректно
     * ли завершился шаг или нет
     *
     * @param string $direction Direction to go, 'up' or 'down'
     * @param integer $num
     * @return void
     */
    protected function _doMigrateStep($direction, $num)
    {
        
            $migration = $this->getMigrationClass($num);

            $method = 'pre' . $direction;
            $migration->$method();

            if (method_exists($migration, $direction)) {
                $migration->$direction();
            } else if (method_exists($migration, 'migrate')) {
                $migration->migrate($direction);
            }

            if ($migration->getNumChanges() > 0) {
                $changes = $migration->getChanges();
                if ($direction == 'down' && method_exists($migration, 'migrate')) {
                    $changes = array_reverse($changes);
                }
                foreach ($changes as $value) {
                    list($type, $change) = $value;
                    $funcName = 'process' . Doctrine_Inflector::classify($type);
                    if (method_exists($this->_process, $funcName)) {

                        $this->_process->$funcName($change);

                    } else {
                        throw new Doctrine_Migration_Exception(sprintf('Invalid migration change type: %s', $type));
                    }
                }
            }

            $method = 'post' . $direction;
            $migration->$method();
        
    }    
    
    /**
     * Создание таблицы для миграции
     *
     * @return boolean
     */
    protected function _createMigrationTable()
    {
      $conn = $this->getConnection();
    
      try {
        $conn->export->createTable($this->_migrationTableName, array(
          'version' => array(
            'type' => 'string',
            'length' => 255
          )
        ));
    
        return true;
      } catch(Exception $e) {
        return false;
      }
    }
    
    
    
    /**
     * Расчет неприменных версий и направления в зависимости от версии до которой мигрируем
     * 
     * [1] Получаем список уже примененных версий
     * [2] Получаем список версий до версии $to
     * [3] Если второй список больше, то направление up, находим список неприменных
     * версий (разница), сортируем по возрастанию
     * Если второй список меньше, но направление down, находим список неприменныех
     * версий, сортируем по убыванию
     * 
     * Возвращает данные в виде массива
     * array(
     *  'direction' => 'down', //направление
     *  'timestamps' => array( .. ) //список миграции, которых надо применить
     *  'timestamps_applied' => array(..) // здесь будут примененные миграции
     * )
     *
     * @param integer $to
     * @return array Данные 
     * @throws Doctrine_Exception 
     */
    protected function _getDirectionAndTimestamps($to) {
      
        $fromTimestamps = $this->getAppliedTimestamps();

        if (array_search($to, $this->getAllTimestamps()) === false) {
          
          throw new Doctrine_Migration_Exception('No migration version # ' . $to);
          
        } elseif ($to == $this->getLatestVersion()) {

          $toTimestamps = $this->getAllTimestamps();

        } else {
          
          $toTimestamps = array_slice($this->getAllTimestamps(), 0, array_search($to, $this->getAllTimestamps()) + 1);
        }
        
        
        if ( $fromTimestamps == $toTimestamps) {
          
            throw new Doctrine_Migration_Exception('Already at version # ' . $to);
        }
        
        if (count($fromTimestamps) < count($toTimestamps)) {

          $direction =  'up'; 
          $unAppliedTimestamps = array_diff($toTimestamps, $fromTimestamps);
          sort($unAppliedTimestamps);
          
        }
        else {

          $direction =  'down'; 
          $unAppliedTimestamps = array_diff($fromTimestamps, $toTimestamps);
          rsort($unAppliedTimestamps);
          
        }
        
        return array(
          'direction' => $direction,
          'timestamps' => $unAppliedTimestamps,
          'timestamps_applied' => array(),
        );
    }

    
    
    /**
     * Переопределение загрузки файлов миграции чтобы хранились с указанием timestamp
     * как версии
     *
     * @param string $directory  Directory to load migration classes from
     * @return void
     */
    public function loadMigrationClassesFromDirectory($directory = null)
    {
        $directory = $directory ? $directory:$this->_migrationClassesDirectory;

        $classesToLoad = array();
        $classes = get_declared_classes();
        foreach ((array) $directory as $dir) {
            $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir),
                RecursiveIteratorIterator::LEAVES_ONLY);

            if (isset(self::$_migrationClassesForDirectories[$dir])) {
                foreach (self::$_migrationClassesForDirectories[$dir] as $num => $className) {
                    $this->_migrationClasses[$num] = $className;
                }
            }

            foreach ($it as $file) {
                $info = pathinfo($file->getFileName());
                if (isset($info['extension']) && $info['extension'] == 'php') {
                    require_once($file->getPathName());

                    $array = array_diff(get_declared_classes(), $classes);
                    $className = end($array);

                    if ($className) {
                        $e = explode('_', $file->getFileName());
                        $timestamp = $e[0];

                        $classesToLoad[$timestamp] = array('className' => $className, 'path' => $file->getPathName());
                    }
                }
            }
        }
        ksort($classesToLoad);
        foreach ($classesToLoad as $timestamp => $class) {
            $this->loadMigrationClass($class['className'], $class['path'], $timestamp);
        }
    }

    /**
     * Переопределение загрузки файлов миграции чтобы хранились с указанием timestamp
     * как версии
     *      *
     * @param string $name
     * @return void
     */
    public function loadMigrationClass($name, $path = null, $num = null)
    {
        $class = new ReflectionClass($name);

        while ($class->isSubclassOf($this->_reflectionClass)) {

            $class = $class->getParentClass();
            if ($class === false) {
                break;
            }
        }

        if ($class === false) {
            return false;
        }

        $classMigrationNum = $num;
        if (empty($classMigrationNum)) {
          
          if (empty($this->_migrationClasses)) {
              $classMigrationNum = 1;
          } else {
              $nums = array_keys($this->_migrationClasses);
              $num = end($nums);
              $classMigrationNum = $num + 1;
          }
        }

        $this->_migrationClasses[$classMigrationNum] = $name;

        if ($path) {
            $dir = dirname($path);
            self::$_migrationClassesForDirectories[$dir][$classMigrationNum] = $name;
        }
    }



}

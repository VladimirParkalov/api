<?php
/**
 * Таск для миграции с использованием  MigrationFreeOrder
 * 
 * @author Aliaksei Shytkin <e79eas@gmail.com>
 * @version $Id: tsDoctrineMigrateTask.class.php 93 2010-04-01 17:21:16Z alexeis $
 * @package Task
 */


/**
 * Класс таска
 *
 */
class tsDoctrineMigrateTask extends sfDoctrineMigrateTask 
{
  
  /**
   * Конфигурим
   *
   */
  protected function configure()
  {
    parent::configure();
    
    $this->aliases = array('doctrine-migrate-ts');
    $this->namespace = 'doctrine';
    $this->name = 'migrate-ts';
    $this->briefDescription = 'Migration process using Doctrine_MigrationFreeOrder';
  }
  
  
  /**
   * Запускаем
   *
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);

    $config = $this->getCliConfig();
    $migration = new Doctrine_MigrationFreeOrder($config['migrations_path']);

    $from = $migration->getCurrentVersion();


    $applied = $migration->getAppliedTimestamps();
    $all = $migration->getAllTimestamps();

    $unapplied = array_diff($all, $applied);


    if (is_numeric($arguments['version']))
    {
      $version = $arguments['version'];
    }
    else if ($options['up'])
    {
      $version = $from + 1;
    }
    else if ($options['down'])
    {
      $version = $from - 1;
    }
    else
    {
      $version = $migration->getLatestVersion();
    }

    if ($from == $version && count($unapplied) == 0)
    {
      $this->logSection('doctrine', sprintf('Already at migration version %s', $version));
      return;
    }

    $this->logSection('doctrine', sprintf('Migrating from version %s to %s%s', $from, $version, $options['dry-run'] ? ' (dry run)' : ''));
    try
    {
      $migration->migrate($version, $options['dry-run']);
    }
    catch (Exception $e)
    {
    }

    // render errors
    if ($migration->hasErrors())
    {
      if ($this->commandApplication && $this->commandApplication->withTrace())
      {
        $this->logSection('doctrine', 'The following errors occurred:');
        foreach ($migration->getErrors() as $error)
        {
          $this->commandApplication->renderException($error);
        }
      }
      else
      {
        $this->logBlock(array_merge(
          array('The following errors occurred:', ''),
          array_map(create_function('$e', 'return \' - \'.$e->getMessage();'), $migration->getErrors())
        ), 'ERROR_LARGE');
      }

      return 1;
    }

    $this->logSection('doctrine', 'Migration complete');
  }
}
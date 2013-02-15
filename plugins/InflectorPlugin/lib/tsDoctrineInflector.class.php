<?php
/**
 * Расшрияем Doctrine_Inflector для работы с русскими буквами
 *
 * @author Aliaksei Shytkin <e79eas@gmail.com>
 * @version $Id$
 * @package Util
 */

/**
 * Класс расширенного инфлектора
 */
class tsDoctrineInflector extends Doctrine_Inflector {

  /**
   * Генерим слаг
   *
   * @see Doctrine_Inflector
   * @static
   * @param  $text
   * @return #M#CDoctrine_Inflector.urlize|?
   */
  static public function urlize($text) {

    return parent::urlize(tsTranslit::convert($text));
  }

  /**
   * Рандомное число
   *
   * @static
   * @return void
   */
  static public function randomId()
  {
    return mt_rand(1000000, 9999999);
  }

  /**
   * Генерим слаговидное имя файла
   *
   * @static
   * @param  $text
   * @return string
   */
  static public function filenameize($text) {

    $info = pathinfo($text);
    $filename =  parent::urlize(tsTranslit::convert($info['filename'])).

    ($info['extension'] ? ('.'.parent::urlize(tsTranslit::convert($info['extension']))) : '');

    return $filename;
  }

}

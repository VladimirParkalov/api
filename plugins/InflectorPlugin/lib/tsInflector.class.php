<?php
/**
 * Расширяем функциональность инфлектора
 *
 * @version $Id$
 * @package Util
 * @author Aliaksei Shytkin <e79eas@gmail.com>
 *
 */

/**
 * Класс инфлектора
 */
class tsInflector extends sfInflector {

  /**
   * Преобразуем к заголовку (все буквы заглавные, корректные пробелы)
   *
   * Например user_name, userName =>  User Name
   *
   * @static
   * @param string $camelCase_or_tabbed
   * @return string
   */
  public static function titleize($camelCase_or_tabbed)
  {
    
    return ucwords(sfInflector::humanize(sfInflector::underscore($camelCase_or_tabbed)));
  }
}

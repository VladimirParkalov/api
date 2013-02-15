<?php

require_once '/usr/share/php/symfony/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enableAllPluginsExcept('sfPropelPlugin');
//    $this->enablePlugins('sfDoctrinePlugin');
//    $this->enablePlugins('sfOauthServerPlugin');
//    $this->enablePlugins('sfDoctrineRestBasicPlugin');
//    $this->enablePlugins('sfDoctrineRestGeneratorPlugin');

  }
}
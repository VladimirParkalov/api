<?php

class sfWidgetFormCKEditor extends sfWidgetFormTextarea {
  
  protected $_editor;
  protected $_finder;
  
  protected function configure($options = array(), $attributes = array())
  {
    $editorClass = 'CKEditor';
    if (!class_exists($editorClass))
    {
      throw new sfConfigurationException(sprintf('CKEditor class not found'));
    }
    $this->_editor = new $editorClass();
    $this->_editor->basePath = sfConfig::get('app_ckeditor_basePath');
    $this->_editor->returnOutput = true;
    //echo "<pre>";
    //var_dump($attributes); exit;
    
    if (!isset($options['jsoptions'])){
        $options['jsoptions']['toolbar'] = array(           
            array( 'Source', '-', 'Bold', 'Italic', 'Underline', 'Strike','Subscript','Superscript','RemoveFormat', '-', 'PasteText','PasteFromWord','-', 'TextColor','BGColor' ),
            array( 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote', '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ),
            array( 'Image','Flash','-','Table','HorizontalRule','SpecialChar','PageBreak','Iframe','-', 'Link', 'Unlink', 'Anchor','-','Font','FontSize','Maximize','-','Undo','Redo' )           
        );
//        $options['jsoptions']['width'] = '300px';
    }
    if(isset($options['jsoptions']))
    {
      $this->addOption('jsoptions', $options['jsoptions']);    
    }
    parent::configure($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $jsoptions = $this->getOption('jsoptions');
    if($jsoptions)
    {   
      $this->setJSOptions($name, $value, $attributes, $errors);
    }
    $response = parent::render($name, $value, $attributes, $errors);
    $ckname = uniqid('editor');
    $response.= $this->renderEditorTag($ckname, $name, $this->_editor);
    if (sfConfig::get('app_ajexfilemanager_active', false))
    {
      $response.= $this->renderFileManager($ckname);
    }

    return $response;

  }

  public function renderFileManager($name)
  {
    $src = "
      AjexFileManager.init({
          returnTo: 'ckeditor',
          editor: ".$name.",
          lang: 'en',
          connector: 'php',
          contextmenu: true
      });";
    return $this->script($src);
  }

  protected function setJSOptions($name, $value = null, $attributes = array(), $errors = array())
  {
    $jsoptions = $this->getOption('jsoptions');
    foreach($jsoptions as $k => $v)
    {
      $this->_editor->config[$k] = $v;
    }
  }

  public function renderEditorTag($ckname, $id, $config = array(), $events = array())
  {
    $out = "";
//    if (!$this->_editor->initialized)
//    {
//      $out .= $this->_editor->init();
//    }

    $_config = $this->_editor->configSettings($config, $events);
    $js = $this->_editor->returnGlobalEvents();
    if (!empty($_config))
    {
      $js .= 'var ' . $ckname . " = CKEDITOR.replace('".$id."', ".$this->_editor->jsEncode($_config).");";
    }
    else
    {
      $js .= 'var ' . $ckname . " = CKEDITOR.replace('".$id."');";
    }
    $out .= $this->script($js);
    if (!$this->_editor->returnOutput)
    {
      print $out;
      $out = "";
    }

    return $out;
  }

  private function script($js)
  {
    $out = "<script type=\"text/javascript\">";
    $out .= "//<![CDATA[\n";
    $out .= $js;
    $out .= "\n//]]>";
    $out .= "</script>\n";
    return $out;
  }

  public function getEditor()
  {
    return $this->_editor;
  }  
  public function getFinder()
  {
    return $this->_finder;
  }

  public function getJavaScripts()
  {
    $scripts = array($this->_editor->basePath . 'ckeditor.js');
    if (sfConfig::get('app_ajexfilemanager_active', false))
    {
      $scripts[] = sfConfig::get('app_ajexfilemanager_basePath') . 'ajex.js';
    }
    return $scripts;
  }
}
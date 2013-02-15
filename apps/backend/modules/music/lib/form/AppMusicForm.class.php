<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AppMusicForm extends MusicForm
{
    public function configure()
    {
        $this->useFields(array(
            'title',
            'url',
            'description',
            'is_active'
        ));
//        sfContext::getInstance()->getConfiguration()->loadHelpers(array('Thumb', 'Url'));
       $this->widgetSchema['url'] = new sfWidgetFormInputFileEditable(array(
              'label' => 'file',
              'with_delete' => true,
              'delete_label'=> false, 
              'is_image' => false,
              'edit_mode' => false,
              'template' => '<h4 style="color: grey;"> Размер файла не больше 15mb <h4> <br /> %file%<br />%input% <br /> %delete%%delete_label%',
//              'file_src' => '/uploads/'. strtolower(get_class($this->getObject())).'/'.$this->getObject()->image
              'file_src' => '/uploads/'. strtolower(get_class($this->getObject())).'/'.$this->getObject()->url
//           'file_src' => trim($this->getObject()->image) == '' ? '' : image_thumb_src($this->getObject()->image, $this->getObject(), 'comment_image')
            ));
    
//        $this->validatorSchema['file_delete'] = new sfValidatorBoolean();
        $this->validatorSchema['url'] = new sfValidatorFile(
                   array(
//                         'mime_types' => 'file',
                         'required' => FALSE,
                         'path' => sfConfig::get('sf_upload_dir') . '/'. strtolower(get_class($this->getObject())).'/'
                        )
            );
        
        //$this->setWidget('section', new sfWidgetFormSelect(array('choices' => Sections::$items)));

    }
}

?>

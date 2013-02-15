<?php
class AppUserProfileForm extends sfGuardUserProfileForm
{
  public function  configure()
  {
    $this->useFields(array(
//        'first_name',
//        'last_name',
//        'patronymic',
////        'address',
//        'phone_prefix',
        'image_path',
        'bio',
        'description',
//        'accept_mail',
//        'info',
//        'icq',
//        'skype',
//        'image_file'
    ));

 sfContext::getInstance()->getConfiguration()->loadHelpers(array('Thumb', 'Url'));
       $this->widgetSchema['image_path'] = new sfWidgetFormInputFileEditable(array(
              'label' => 'Аватар',
              'with_delete' => true,
              'delete_label'=> 'Удалить', 
              'is_image' => true,
              'edit_mode' => true,
           'template' => '<h4 style="color: grey;"> Рекомендуемое разрешение 200х200<h4> <br /> %file%<br />%input% <br /> %delete%%delete_label%',
              'file_src' =>trim($this->getObject()->image_path) == '' ? '' : image_thumb_src($this->getObject()->image_path, $this->getObject(), 'comment_preview') //sfConfig::get('sf_upload_dir') . '/' . $this->getObject()->User->getUploadFolder() . '/' 
            ));
    
  $this->validatorSchema['image_path_delete'] = new sfValidatorBoolean();
       $this->validatorSchema['image_path'] = new sfValidatorFile(
                   array(
                       'max_size' => MAX_AVATAR_SIZE * 1024,
                         'mime_types' => 'web_images',
                         'required' => false,
                         'path' => sfConfig::get('sf_upload_dir') . '/'. strtolower(get_class($this->getObject())).'/'
                        ),
                   array(
                       'max_size' => 'Максимальный размер картинки ' . MAX_AVATAR_SIZE . 'Кб',
                        'mime_types' => 'Неверный формат файла'
                        )
            );   
//    $this->widgetSchema['image_path'] = new sfWidgetFormInputFile();
    
//    $this->validatorSchema['image_path'] = new sfValidatorFile(
//                                                      array(
//                                                          'max_size' => MAX_AVATAR_SIZE * 1024,
//                                                          'mime_types' => 'web_images',
//                                                          'required' => false,
//                                                          'path' => sfConfig::get('sf_upload_dir') . '/' . $this->getObject()->User->getUploadFolder() . '/'
//                                                          
//                                                      ),
//                                                      array(
//                                                          'max_size' => 'Максимальный размер картинки ' . MAX_AVATAR_SIZE . 'Кб',
//                                                          'mime_types' => 'Неверный формат файла'
//                                                        )
//                                                    );

    $this->getWidgetSchema()->setLabels(array(
       'image_path' => 'Аватар',
//       'last_name' => 'Фамилия',
////        'address' => 'Адрес',
//        'phone_prefix' => 'Код страны',
//        'phone' => 'Контактный телефон',
//        'patronymic' => 'Отчество',
//        'icq' => 'ICQ',
//        'skype' => 'Skype',
//        'image_file' => 'Фото',
        'bio' => 'Информация (только для экспертов)',
        'description' => 'Описание на главной (только для экспертов )',
//        'accept_mail' => 'Получать письма',
    ));

//    $this->setWidget('info', new sfWidgetFormTextarea());

//    $this->setWidget('image_file', new sfWidgetFormInputFileEditable(
//            array(
//                'with_delete' => true,
//                'delete_label' => 'Удалить фото',
//                'file_src' => null,
//            )
//      )
//    );
//    $this->setValidator('image_file_delete', new sfValidatorBoolean());
//    $this->setValidator('image_file', new sfValidatorFile(array(
//      'required' => false,
//      'path' => $this->object->getUploadPath()
//    )));
//    $this->getWidgetSchema()->setLabels(array(
//        'image_file' => 'Фото'
//    ));
  }

  protected function saveFile($field, $filename = null, sfValidatedFile $file = null, $useDefaultMethod = false) {

    if (!$this->getObject()->getTable()->hasTemplate('Imageable') || $useDefaultMethod) {

      return parent::saveFile($field, $filename, $file);
    }

    require_once sfConfig::get('sf_plugins_dir').'/tsUploadPlugin/lib/vendor/class.upload/class.upload.php';
    $setName = md5($file->getOriginalName());
    #генерация реальной картинки
    $img = new Upload($file->getTempName());
    $original = pathinfo($setName);
    $img->file_new_name_body = $original['filename'];
    $img->image_convert = 'png';

    $img->process($file->getPath());

    $this->getObject()->image_width = $img->image_dst_x;
    $this->getObject()->image_height = $img->image_dst_y;

    $fileName = $img->file_dst_name;

    #генерация превью картинки
    $img = new Upload($file->getTempName());
    $original = pathinfo($setName);
    $img->file_new_name_body = $original['filename'].'_preview';
    $img->image_convert = 'png';
    $img->image_resize = true;
    $img->image_ratio_crop = true;
    $img->image_x = 160;
    $img->image_y = 160;
    $img->process($file->getPath());

    $this->getObject()->image_preview_file = $img->file_dst_name;
    $this->getObject()->image_preview_width = $img->image_dst_x;
    $this->getObject()->image_preview_height = $img->image_dst_y;

    return $fileName;
  }
  
}
<?php
class AppUserForm extends sfGuardUserForm
{
  public function configure(){
    
    
    $this->useFields(array(
        'email_address',
        'username',
        'is_active',
        'id',
        'first_name',
        'last_name',
        'is_expert',
        'priority',
        'ip',
        'ip2'
        
    ));
    if ($this->getObject()->isNew()){
      $profile = new sfGuardUserProfile();
      $profile->User = $this->getObject();
      $profiles = new Doctrine_Collection('sfGuardUserProfile');
      $profiles->add($profile);
      $this->getObject()->Profile = $profiles;
    }

    $this->setValidator('email_address', new sfValidatorEmail(
              array('required' => true,'trim' => true),
              array('required' => 'Это обязательное поле', 'invalid' => 'Неверное значение')
           ));

    $this->getValidatorSchema()->setPostValidator(new tsValidatorGuardUniqueEmail(array('model' => 'sfGuardUser'), array(
        'required' => 'Это обязательное поле', 'invalid' => 'Такой пользователь уже зарегистрирован'
    )));

    $this->getWidget('is_active')->setDefault(false);
    
    $this->embedRelation('Profile', 'AppUserProfileForm');
//    $this->embedRelation('Codes', 'AppCodeForm');

    $this->getWidgetSchema()->setLabels(array(
        'email_address' => 'Контактный e-mail',
        'is_active' => 'Активен'
    ));
  }
}
<?php

class sfGuardUserFormFilterBackend extends sfGuardUserFormFilter
{
  public function configure()
  {
    $this->widgetSchema   ['email'] = new sfWidgetFormInput();
    $this->validatorSchema['email'] = new sfValidatorString(array(
        'required' => false,
        'trim' => true,
        'max_length' => 255
    ));

    $this->widgetSchema   ['first_name'] = new sfWidgetFormInput();
    $this->validatorSchema['first_name'] = new sfValidatorString(array(
        'required' => false,
        'trim' => true,
        'max_length' => 255
    ));

    $this->widgetSchema   ['last_name'] = new sfWidgetFormInput();
    $this->validatorSchema['last_name'] = new sfValidatorString(array(
        'required' => false,
        'trim' => true,
        'max_length' => 255
    ));

//    $this->widgetSchema   ['video'] = new sfWidgetFormInput();
//    $this->validatorSchema['video'] = new sfValidatorString(array(
//        'required' => false,
//        'trim' => true,
//        'max_length' => 255
//    ));

    $this->getWidgetSchema()->setLabels(array(
      'email' => 'Электронный почта',
      'first_name' => 'Имя',
      'last_name' => 'Фамилия',
//      'video' => 'Название видео',
    ));
  }

  public function addEmailColumnQuery(Doctrine_Query $query, $field, $value)
  {
    if ($value)
    {
      $query->addWhere('r.email_address LIKE ?', array(
         '%'.$value.'%'
      ));
    }

    return $query;
  }
  
  public function addFirstNameColumnQuery(Doctrine_Query $query, $field, $value)
  {
    if ($value)
    {
      $query->addWhere('r.first_name LIKE ? ', array(
         '%'.$value.'%'
      ));
    }

    return $query;
  }

  public function addLastNameColumnQuery(Doctrine_Query $query, $field, $value)
  {
    if ($value)
    {
      $query->addWhere('r.last_name LIKE ?', array(
         '%'.$value.'%'
      ));
    }

    return $query;
  }

//  public function addVideoColumnQuery(Doctrine_Query $query, $field, $value)
//  {
//    if ($value)
//    {
//      $query->addWhere('v.title LIKE ?', array(
//         '%'.$value.'%'
//      ));
//    }
//
//    return $query;
//  }
}

<?php

/**
 * sfOauthServerConsumer form base class.
 *
 * @method sfOauthServerConsumer getObject() Returns the current form's model object
 *
 * @package    api
 * @subpackage form
 * @author     Vladimir
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasesfOauthServerConsumerForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'consumer_key'    => new sfWidgetFormInputText(),
      'consumer_secret' => new sfWidgetFormInputText(),
      'name'            => new sfWidgetFormTextarea(),
      'description'     => new sfWidgetFormInputText(),
      'protocole'       => new sfWidgetFormInputText(),
      'base_domain'     => new sfWidgetFormTextarea(),
      'callback'        => new sfWidgetFormTextarea(),
      'scope'           => new sfWidgetFormTextarea(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'consumer_key'    => new sfValidatorString(array('max_length' => 40)),
      'consumer_secret' => new sfValidatorString(array('max_length' => 40)),
      'name'            => new sfValidatorString(array('max_length' => 256)),
      'description'     => new sfValidatorPass(),
      'protocole'       => new sfValidatorInteger(array('required' => false)),
      'base_domain'     => new sfValidatorString(array('max_length' => 256, 'required' => false)),
      'callback'        => new sfValidatorString(array('max_length' => 256, 'required' => false)),
      'scope'           => new sfValidatorString(array('max_length' => 256, 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'sfOauthServerConsumer', 'column' => array('consumer_key'))),
        new sfValidatorDoctrineUnique(array('model' => 'sfOauthServerConsumer', 'column' => array('consumer_secret'))),
      ))
    );

    $this->widgetSchema->setNameFormat('sf_oauth_server_consumer[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfOauthServerConsumer';
  }

}

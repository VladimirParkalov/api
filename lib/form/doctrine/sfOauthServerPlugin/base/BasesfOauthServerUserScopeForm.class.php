<?php

/**
 * sfOauthServerUserScope form base class.
 *
 * @method sfOauthServerUserScope getObject() Returns the current form's model object
 *
 * @package    api
 * @subpackage form
 * @author     Vladimir
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasesfOauthServerUserScopeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'user_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => false)),
      'consumer_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Consumer'), 'add_empty' => false)),
      'scope'       => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'user_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'))),
      'consumer_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Consumer'))),
      'scope'       => new sfValidatorString(array('max_length' => 256, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sf_oauth_server_user_scope[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfOauthServerUserScope';
  }

}

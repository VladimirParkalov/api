<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Addsfoauthservernonce extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('sf_oauth_server_nonce', array(
             'id' => 
             array(
              'type' => 'integer',
              'length' => 8,
              'autoincrement' => true,
              'primary' => true,
             ),
             'nonce' => 
             array(
              'type' => 'string',
              'notnull' => true,
              'unique' => true,
              'length' => 40,
             ),
             'created_at' => 
             array(
              'notnull' => true,
              'type' => 'timestamp',
              'length' => 25,
             ),
             ), array(
             'indexes' => 
             array(
             ),
             'primary' => 
             array(
              0 => 'id',
             ),
             ));
    }

    public function down()
    {
        $this->dropTable('sf_oauth_server_nonce');
    }
}
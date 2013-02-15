<?php

/**
 * api actions.
 *
 * @package    api
 * @subpackage api
 * @author     Vladimir
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class apiActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->musics = Doctrine_Core::getTable('music')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->music = Doctrine_Core::getTable('music')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->music);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new musicForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new musicForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($music = Doctrine_Core::getTable('music')->find(array($request->getParameter('id'))), sprintf('Object music does not exist (%s).', $request->getParameter('id')));
    $this->form = new musicForm($music);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($music = Doctrine_Core::getTable('music')->find(array($request->getParameter('id'))), sprintf('Object music does not exist (%s).', $request->getParameter('id')));
    $this->form = new musicForm($music);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($music = Doctrine_Core::getTable('music')->find(array($request->getParameter('id'))), sprintf('Object music does not exist (%s).', $request->getParameter('id')));
    $music->delete();

    $this->redirect('api/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $music = $form->save();

      $this->redirect('api/edit?id='.$music->getId());
    }
  }
}

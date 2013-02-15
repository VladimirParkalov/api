<div id='sf_admin_path'>

 
  <? $path = array(); ?>
  <? $path[] = link_to_if($sf_context->getActionName() != 'dashboard', 'Панель администратора', '@homepage') ?>

  <? $item = tsAdminDash::getItemByUrl($sf_context->getModuleName()) ?>
  
  <? $moduleTitle = false; ?>

  <? if ($item): ?>
  
    <? $moduleTitle = $item['title'] ?>
    <? $moduleUrl = $item['url'] ?>
    
  <? elseif ($sf_context->getModuleName() != 'tsAdminDash'): ?>
  
    <? $moduleTitle = tsInflector::titleize($sf_context->getModuleName()) ?>
    <? $moduleUrl = $sf_context->getModuleName().'/index' ?>
  <? endif ?>

  <? if ($moduleTitle): ?>

    <? $step = get_slot('sf_path_prev_step') ?>  
    <? if ($step): ?>
    
      <? $path[] = $step ?> 
    <? else: ?>
    
      <? $path[] = link_to_if($sf_context->getActionName() != 'index', __($moduleTitle, array(), 'sf_admin'), $moduleUrl) ?>
    <? endif ?>
  
  
    <? $step = get_slot('sf_path_current_step') ?>  
    <? if ($step): ?>
    
      <? $path[] = $step ?> 
    <? else: ?>
      <? if ($sf_context->getActionName() != 'index'): ?>
    
        <? /* $title = sfContext::getInstance()->getResponse()->getTitle() */ ?>
        
        
        <? $path[] =  __(tsInflector::titleize($sf_context->getActionName()), array(), 'sf_admin') ?>
      <? endif ?>
        
        
    <? endif ?>
  <? endif ?>
  
  <?=__('Сейчас вы находитесь в: %path%', array(
    '%path%' => implode(' >> ', $path)  
  )) ?>
  
</div>
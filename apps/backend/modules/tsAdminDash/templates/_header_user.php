  <? use_helper('Date', 'I18N') ?>
  <div id='sf_admin_menu'>

    <? if (tsAdminDash::getProperty('logout') && ($sf_user->isAuthenticated())): ?>

      <div id="logout">
        Добро пожаловать,
        <?=link_to($sf_user, '@sf_guard_edit') ?> |
        <?=link_to(__('Сайт'), 'http://'.$sf_request->getHost().'/') ?> |
        <?=link_to(__('Выход'), '@sf_guard_signout') ?>

        <div>
          <?=format_date(time(), 'P', 'ru') ?>
        </div>
      </div>

    <? endif; ?>
    <div class="clear"></div>
  </div>

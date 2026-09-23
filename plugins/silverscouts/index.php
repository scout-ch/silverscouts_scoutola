<?php

function silverscouts_plugin_name()
{
  return "silverscouts";
}

function silverscouts_plugin_folder()
{
  return osc_plugin_folder(__FILE__);
}

function silverscouts_plugin_get_preference($name)
{
  return osc_get_preference($name, silverscouts_plugin_name());
}

function silverscouts_plugin_set_preference($name, $value, $type = 'STRING')
{
  osc_set_preference($name, $value, silverscouts_plugin_name(), $type);
  return $value;
}

function silverscouts_plugin_restrict_access()
{
  $restrictedPages = ["item", "search", "user"];

  if (!osc_logged_user_id() && in_array(Params::getParam('page'), $restrictedPages)) {
    osc_redirect_to(osc_user_login_url());
    exit;
  }
}

function silverscouts_plugin_hook_before_init()
{
  silverscouts_plugin_migrate_database();
  silverscouts_plugin_restrict_access();
}
osc_add_hook('before_init', 'silverscouts_plugin_hook_before_init');

require_once dirname(__FILE__) . '/database.php';
require_once dirname(__FILE__) . '/midata-login.php';
require_once dirname(__FILE__) . '/contact-stats.php';

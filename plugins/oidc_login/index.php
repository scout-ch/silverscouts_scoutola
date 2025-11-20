<?php

require_once osc_plugin_folder(__FILE__) . 'vendor/autoload.php';

function oidc_plugin_admin_path()
{
  return basename(dirname(__FILE__)) . '/admin.php';
}

function oidc_plugin_redirect_uri()
{
  return osc_route_url('oidc_plugin_callback');
}

function oidc_plugin_admin_url()
{
  return osc_admin_render_plugin_url(oidc_plugin_admin_path());
}

function oidc_plugin_name()
{
  return "oidc";
}

function oidc_plugin_folder()
{
  return osc_plugin_folder(__FILE__);
}

function oidc_plugin_get_preference($name)
{
  return osc_get_preference($name, oidc_plugin_name());
}

function oidc_plugin_set_preference($name, $value, $type = 'STRING')
{
  osc_set_preference($name, $value, oidc_plugin_name(), $type);
  return $value;
}

function oidc_plugin_client()
{
  $oidc = new Jumbojett\OpenIDConnectClient(
    oidc_plugin_get_preference('issuer'),
    oidc_plugin_get_preference('client_id'),
    oidc_plugin_get_preference('client_secret')
  );

  $oidc->setRedirectURL(oidc_plugin_redirect_uri());
  $oidc->addScope(explode(" ", oidc_plugin_get_preference('scopes')));

  return $oidc;
}

function oidc_plugin_migration_version()
{
  return strval(oidc_plugin_get_preference('migration_version'));
}

function oidc_plugin_migrate_database()
{
  $migrationPaths = glob(oidc_plugin_folder() . 'migrations/*.sql');
  $db = DBConnectionClass::newInstance()->getOsclassDb();
  $cmd = new DBCommandClass($db);

  foreach ($migrationPaths as $migrationPath) {
    $migrationVersion = basename($migrationPath, '.sql');
    if ($migrationVersion <= oidc_plugin_migration_version()) continue;

    $migrationSql = file_get_contents($migrationPath);

    if (!$cmd->importSQL($migrationSql)) {
      throw new Exception("Migration failed");
    }

    oidc_plugin_set_preference('migration_version', $migrationVersion);
  }
}

function oidc_plugin_login_user($user)
{
  // Cookie::newInstance()->set_expires(osc_time_cookie());
  // Cookie::newInstance()->push('oc_userId', $user['pk_i_id']);
  // Cookie::newInstance()->push('oc_userSecret', $user['s_secret']);
  // Cookie::newInstance()->set();

  Session::newInstance()->_set('userId', $user['pk_i_id']);
  Session::newInstance()->_set('userName', $user['s_name']);
  Session::newInstance()->_set('userEmail', $user['s_email']);
  Session::newInstance()->_set('userPhone', ($user['s_phone_mobile'] ? $user['s_phone_mobile'] : $user['s_phone_land']));
}

require_once oidc_plugin_folder() . 'hooks.php';

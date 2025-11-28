<?php

require_once dirname(__FILE__) . '/../user.php';

// TODO: find out how to run this correctly
oidc_plugin_migrate_database();

// process the callback
$client = oidc_plugin_client();
$client->authenticate();
$userInfo = $client->requestUserInfo();

osc_run_hook('before_login');

// prepare osc user
$identityDAO = new OIDCIdentity();
$user = $identityDAO->findUserByUserInfo(oidc_plugin_name(), (array) $userInfo);

// check if login succeeded
if (!empty($user)) {
  oidc_plugin_login_user($user);
  osc_add_flash_ok_message(__("The user has been created successfully"));

  $redirect = osc_user_dashboard_url();
  // osc_run_hook('after_oidc_login_success', $user, (array) $userInfo, $client);
  osc_run_hook('after_login', $user, $redirect);
} else {

  osc_add_flash_error_message(__("We were not able to identify you given the information provided"));
  $redirect = osc_base_url();
}

header('Location: ' . osc_apply_filter('correct_login_url_redirect', $redirect));

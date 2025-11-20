<?php

require_once oidc_plugin_folder() . 'user.php';

$client = oidc_plugin_client();
$client->authenticate();
$userInfo = $client->requestUserInfo();

osc_run_hook('before_login');
$identityDAO = new OIDCIdentity();
$user = $identityDAO->findUserByUserInfo('test', $userInfo);

if(!empty($user)) {
  oidc_plugin_login_user($user);
  osc_add_flash_ok_message(__('You are now logged in.'));
  $redirect = osc_user_dashboard_url();
  osc_run_hook('after_oidc_login_success', $user, $userInfo, $client);
  osc_run_hook('after_login', $user, $redirect);
  header('Location: ' . osc_apply_filter('correct_login_url_redirect', $redirect));
} else {
  osc_add_flash_error_message(__('Login failed!'));
}

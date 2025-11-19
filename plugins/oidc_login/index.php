<?php

require_once osc_plugin_folder(__FILE__) . 'vendor/autoload.php';

function oidc_plugin_admin_path() {
  return basename(dirname(__FILE__)) . '/admin.php';
}

function oidc_plugin_redirect_uri() {
  return osc_route_url('oidc_plugin_callback');
}

function oidc_plugin_admin_url() {
  return osc_admin_render_plugin_url(oidc_plugin_admin_path());
}

function oidc_plugin_name() {
  return "oidc";
}

function oidc_plugin_folder() {
  return osc_plugin_folder(__FILE__);
}

function oidc_plugin_get_preference($name) {
  return osc_get_preference($name, oidc_plugin_name());
}

function oidc_plugin_set_preference($name, $value, $type = 'STRING') {
  osc_set_preference($name, $value, oidc_plugin_name(), $type);
  return $value;
}

function oidc_plugin_client() {
  $oidc = new Jumbojett\OpenIDConnectClient(
    oidc_plugin_get_preference('issuer'),
    oidc_plugin_get_preference('client_id'),
    oidc_plugin_get_preference('client_secret')
  );

  $oidc->setRedirectURL(oidc_plugin_redirect_uri());
  $oidc->addScope(explode(" ", oidc_plugin_get_preference('scopes')));

  return $oidc;
}

require_once oidc_plugin_folder() . 'hooks.php';

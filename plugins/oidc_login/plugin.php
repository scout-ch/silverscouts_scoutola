<?php

function oidc_plugin_admin_path() {
  return basename(dirname(__FILE__)) . '/admin.php';
}

function oidc_plugin_admin_url() {
  return osc_admin_render_plugin_url(oidc_plugin_admin_path());
}

function oidc_plugin_name() {
  return "oidc";
}

function oidc_plugin_get_preference($name) {
  return osc_get_preference($name, oidc_plugin_name());
}

function oidc_plugin_set_preference($name, $value, $type = 'STRING') {
  osc_set_preference($name, $value, oidc_plugin_name(), $type);
  return $value;
}

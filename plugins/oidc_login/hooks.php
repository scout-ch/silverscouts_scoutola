<?php


// initial setup upon installing the plugin
function oidc_plugin_hook_install() {
  oidc_plugin_set_preference('client_id', '');
  oidc_plugin_set_preference('client_secret', '');
  oidc_plugin_set_preference('issuer', '');
  oidc_plugin_set_preference('scopes', 'email name openid');
  oidc_plugin_migrate_database();

}
osc_register_plugin(oidc_plugin_folder(), 'oidc_plugin_hook_install');

// render configure page
function oidc_plugin_hook_configure() {
  osc_admin_render_plugin(oidc_plugin_folder() . 'admin.php' );
}
osc_add_hook(osc_plugin_path(__FILE__ ) . '_configure', 'oidc_plugin_hook_configure' );	

// show uninstall link
function oidc_plugin_hook_uninstall() {
  // echo "UNINSTALL";
}
osc_add_hook(oidc_plugin_folder() . '_uninstall', 'oidc_plugin_hook_uninstall');

// show link to configure page in admin sidebar
function oidc_plugin_hook_admin_menu() {
  ?>
  <ul>
    <li>
      <a href="<?php echo oidc_plugin_admin_url(); ?>">OIDC</a>
    </li>
  </ul>
  <?php 
}
osc_add_hook('admin_menu','oidc_plugin_hook_admin_menu', 1);


osc_add_route('oidc_plugin_login', 'auth/oidc/login', 'auth/oidc/login', basename(dirname(__FILE__)) . '/endpoints/login.php');
// osc_add_route('oidc_plugin_logout', 'auth/oidc/logout', 'auth/oidc/logout', basename(dirname(__FILE__)) . '/endpoints/logout.php');
osc_add_route('oidc_plugin_callback', 'auth/oidc/callback', 'auth/oidc/callback', basename(dirname(__FILE__)) . '/endpoints/callback.php');

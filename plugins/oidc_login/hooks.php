<?php

require_once osc_plugin_folder(__FILE__) . 'plugin.php';

// initial setup upon installing the plugin
function oidc_plugin_hook_install() {
  
}
osc_register_plugin(osc_plugin_path(__FILE__), 'odic_plugin_hook_install');

// render configure page
function oidc_plugin_hook_configure() {
  osc_admin_render_plugin(osc_plugin_path(__FILE__) . 'admin.php' );
}
osc_add_hook(osc_plugin_path(__FILE__ ) . '_configure', 'oidc_plugin_hook_configure' );	

// show uninstall link
function oidc_plugin_hook_uninstall() {
  // echo "UNINSTALL";
}
osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall', 'oidc_plugin_hook_uninstall');

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




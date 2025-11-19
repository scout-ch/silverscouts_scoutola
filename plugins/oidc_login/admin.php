<?php

function oidc_plugin_admin_page_title($string) {
  return sprintf(__('OpenID Connect Settings - %s'), $string);
}
osc_add_filter('admin_title', 'oidc_plugin_admin_page_title');
  
$client_id = oidc_plugin_get_preference('client_id');
$client_secret = oidc_plugin_get_preference('client_secret');
$issuer = oidc_plugin_get_preference('issuer');
$scopes = oidc_plugin_get_preference('scopes');

if(isset($_POST)) {
  if(Params::existParam('client_id')) $client_id = oidc_plugin_set_preference('client_id', Params::getParam('client_id'));
  if(Params::existParam('client_secret')) $client_secret = oidc_plugin_set_preference('client_secret', Params::getParam('client_secret'));
  if(Params::existParam('issuer')) $issuer = oidc_plugin_set_preference('issuer', Params::getParam('issuer'));
  if(Params::existParam('scopes')) $scopes = oidc_plugin_set_preference('scopes', Params::getParam('scopes'));
}
?>

<div id="oidc_plugin-settings">
  <h2 class="render-title"><?php _e('OpenID Connect'); ?></h2>
    <p></p>

    <form name="settings_form" action="<?php echo osc_admin_base_url(true); ?>" method="post" class="separate-top">
      <input type="hidden" name="page" value="plugins" />
      <input type="hidden" name="action" value="renderplugin" />
      <input type="hidden" name="file" value="<?php echo oidc_plugin_admin_path(); ?>" />
      <input type="hidden" name="plugin_action" value="done" />

      <fieldset class="form-horizontal">
        <div class="form-row">
          <div class="form-label"><?php _e('client_id'); ?></div>
          <div class="form-controls">
            <input type="text" class="input-large" name="client_id" value="<?php echo $client_id; ?>" />
          </div>
        </div>
        <div class="form-row">
          <div class="form-label"><?php _e('client_secret'); ?></div>
          <div class="form-controls">
            <input type="text" class="input-large" name="client_secret" value="<?php echo $client_secret; ?>" />
          </div>
        </div>
        <div class="form-row">
          <div class="form-label"><?php _e('discovery'); ?></div>
          <div class="form-controls">
            <input type="text" class="input-large" name="issuer" value="<?php echo $issuer; ?>" />
          </div>
        </div>
        <div class="form-row">
          <div class="form-label"><?php _e('scopes'); ?></div>
          <div class="form-controls">
            <input type="text" class="input-large" name="scopes" value="<?php echo $scopes; ?>" />
          </div>
        </div>
        <div class="form-row">
          <div class="form-label"><?php _e('redirect_uri'); ?></div>
          <div class="form-controls">
            <?php echo oidc_plugin_redirect_uri(); ?>
          </div>
        </div>
          
        <div class="form-actions">
          <input type="submit" id="submit" value="<?php echo osc_esc_html( __('Save changes') ); ?>" class="btn btn-submit" />
        </div>
      </fieldset>
    </form>
  </div>
</div>

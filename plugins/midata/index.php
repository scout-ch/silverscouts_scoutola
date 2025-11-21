<?php

// require_once osc_plugin_folder(__FILE__) . 'vendor/autoload.php';

function midata_plugin_name()
{
  return "midata";
}

function midata_plugin_folder()
{
  return osc_plugin_folder(__FILE__);
}

function midata_plugin_get_preference($name)
{
  return osc_get_preference($name, midata_plugin_name());
}

function midata_plugin_set_preference($name, $value, $type = 'STRING')
{
  osc_set_preference($name, $value, midata_plugin_name(), $type);
  return $value;
}

// function midata_plugin_after_oidc_identity_create($user, $userInfo)
// {
// }
// osc_add_hook('after_oidc_identity_create', 'midata_plugin_after_oidc_identity_create');

function midata_plugin_oidc_user_identity_linked($user, $identity)
{
  $userDAO = User::newInstance();
  $userInfo = unserialize($identity['s_user_info']);
  $country = Country::newInstance()->findByCode($userInfo['country']);
  $city = City::newInstance()->findByName($userInfo['town']);
  // $region = Region::newInstance()->findByName()
  $updateData = [
    's_name' => "{$userInfo['first_name']} {$userInfo['last_name']} / {$userInfo['nickname']}",
    // 's_phone_land',
    // 's_phone_mobile',
    'fk_c_country_code' => $country['pk_c_id'],
    // 's_country' => $country['pk_i_id'],
    // 's_country_native' => $country['pk_i_id'],
    's_address' => "{$userInfo['street']} {$userInfo['housenumber']}",
    's_zip' => "{$userInfo['zip_code']}",
    // 'fk_i_region_id',
    // 's_region',
    // 's_region_native',
    'fk_i_city_id' => $city["pk_i_id"],
    // 's_city',
    // 's_city_native',
    // 'fk_i_city_area_id',
    // 's_city_area',
    // 'd_coord_lat',
    // 'd_coord_long',
    // 'b_company',
    // 'fk_c_locale_code'
  ];
  $userDAO->update($updateData, ['pk_i_id' => $user['pk_i_id']]);

  Session::newInstance()->_set('userName', $updateData['s_name']);
}

osc_add_hook('oidc_user_identity_linked', 'midata_plugin_oidc_user_identity_linked');

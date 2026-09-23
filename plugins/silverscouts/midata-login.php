<?php

// function silverscouts_plugin_after_oidc_identity_create($user, $userInfo)
// {
// }
// osc_add_hook('after_oidc_identity_create', 'silverscouts_plugin_after_oidc_identity_create');

function silverscouts_plugin_oidc_user_identity_linked($user, $identity)
{
  $userDAO = User::newInstance();
  $userInfo = unserialize($identity['s_user_info']);
  $updateData = [
    's_name' => "{$userInfo['first_name']} {$userInfo['last_name']} / {$userInfo['nickname']}",
  ];
  $userDAO->update($updateData, ['pk_i_id' => $user['pk_i_id']]);

  Session::newInstance()->_set('userName', $updateData['s_name']);
}
osc_add_hook('oidc_user_identity_linked', 'silverscouts_plugin_oidc_user_identity_linked');

function silverscouts_plugin_post_item()
{
  $session = Session::newInstance();
  $userInfo = $session->_get('oidcUserInfo');

  if (isset($userInfo) && !empty($userInfo)) {
    $country = Country::newInstance()->findByCode($userInfo['country']);
    $session->_setForm('countryId', $country['pk_i_id']);

    $city = City::newInstance()->findByName($userInfo['town']);
    if (isset($city) && !empty($city)) {
      $session->_setForm('cityId', $city['pk_i_id']);
      $session->_setForm('regionId', $city['fk_i_region_id']);
    }

    $session->_setForm('zip', "{$userInfo['zip_code']}");
    $session->_setForm('cityArea', "{$userInfo['zip_code']}");
    $session->_setForm('address', "{$userInfo['address']}");
  }
}
osc_add_filter("post_item", 'silverscouts_plugin_post_item');

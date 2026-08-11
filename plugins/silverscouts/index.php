<?php

// require_once osc_plugin_folder(__FILE__) . 'vendor/autoload.php';

function silverscouts_plugin_name()
{
  return "silverscouts";
}

function silverscouts_plugin_folder()
{
  return osc_plugin_folder(__FILE__);
}

function silverscouts_plugin_get_preference($name)
{
  return osc_get_preference($name, silverscouts_plugin_name());
}

function silverscouts_plugin_set_preference($name, $value, $type = 'STRING')
{
  osc_set_preference($name, $value, silverscouts_plugin_name(), $type);
  return $value;
}

// function silverscouts_plugin_after_oidc_identity_create($user, $userInfo)
// {
// }
// osc_add_hook('after_oidc_identity_create', 'silverscouts_plugin_after_oidc_identity_create');

function silverscouts_plugin_oidc_user_identity_linked($user, $identity)
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
osc_add_hook('oidc_user_identity_linked', 'silverscouts_plugin_oidc_user_identity_linked');

function silverscouts_plugin_restrict_access()
{
  $restrictedPages = ["item", "search", "user"];

  if (!osc_logged_user_id() && in_array(Params::getParam('page'), $restrictedPages)) {
    osc_redirect_to(osc_user_login_url());
    exit;
  }
}

function silverscouts_plugin_hook_before_init()
{
  silverscouts_plugin_migrate_database();
  silverscouts_plugin_restrict_access();
}
osc_add_hook('before_init', 'silverscouts_plugin_hook_before_init');

function silverscouts_plugin_hook_pre_contact_post($item)
{
  $itemStatsDao = ItemStats::newInstance();
  $sql = 'INSERT INTO ' . $itemStatsDao->getTableName() . ' (fk_i_item_id, dt_date, i_num_contacts) VALUES (' . $item['fk_i_item_id'] . ', \'' . date('Y-m-d') . '\' ,1) ON DUPLICATE KEY UPDATE  i_num_contacts = i_num_contacts + 1';
  return $itemStatsDao->dao->query($sql);
}
osc_add_hook('post_item_contact_post', 'silverscouts_plugin_hook_pre_contact_post');

function silverscouts_plugin_migrate_database()
{
  $migrationPaths = glob(dirname(__FILE__) . '/migrations/*.sql');
  $db = DBConnectionClass::newInstance()->getOsclassDb();
  $cmd = new DBCommandClass($db);

  foreach ($migrationPaths as $migrationPath) {
    $migrationVersion = basename($migrationPath, '.sql');
    if ($migrationVersion <= strval(silverscouts_plugin_get_preference('migration_version'))) continue;

    $migrationSql = file_get_contents($migrationPath);

    if (!$cmd->importSQL($migrationSql)) {
      throw new Exception("Migration failed");
    }

    silverscouts_plugin_set_preference('migration_version', $migrationVersion);
  }
}

function silverscouts_plugin_hook_admin_items_table($dummy)
{
  $dummy->addColumn('contacts', __('Contacts'));
}
osc_add_hook('admin_items_table', 'silverscouts_plugin_hook_admin_items_table');


$silverscouts_plugin_contactsSumCache = [];
$silverscouts_plugin_contactsSumCacheTime = null;
function silverscouts_plugin_filter_add_contacts_to_items($row, $aRow)
{
  global $silverscouts_plugin_contactsSumCache;
  global $silverscouts_plugin_contactsSumCacheTime;
  $cacheIsStale = is_null($silverscouts_plugin_contactsSumCacheTime) || (time() - $silverscouts_plugin_contactsSumCacheTime) > 300;

  if ($cacheIsStale) {
    $itemStatsDao = ItemStats::newInstance();
    $itemStatsDao->dao->select('fk_i_item_id');
    $itemStatsDao->dao->select('SUM(i_num_contacts) as i_num_contacts');
    $itemStatsDao->dao->groupBy('fk_i_item_id');
    $itemStatsDao->dao->from($itemStatsDao->getTableName());
    $result = $itemStatsDao->dao->get();

    $silverscouts_plugin_contactsSumCache = [];
    foreach ($result->result() as $statRow) {
      $silverscouts_plugin_contactsSumCache[$statRow['fk_i_item_id']] = $statRow['i_num_contacts'];
    }
    $silverscouts_plugin_contactsSumCacheTime = time();
  }

  $row['contacts'] = ($silverscouts_plugin_contactsSumCache[$aRow['fk_i_item_id']] ?? 0) . 'x';
  return $row;
}
osc_add_filter("items_processing_row", 'silverscouts_plugin_filter_add_contacts_to_items');

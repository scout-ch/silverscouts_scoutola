<?php

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

function silverscouts_plugin_hook_pre_contact_post($item)
{
  $itemStatsDao = ItemStats::newInstance();
  $sql = 'INSERT INTO ' . $itemStatsDao->getTableName() . ' (fk_i_item_id, dt_date, i_num_contacts) VALUES (' . $item['fk_i_item_id'] . ', \'' . date('Y-m-d') . '\' ,1) ON DUPLICATE KEY UPDATE  i_num_contacts = i_num_contacts + 1';
  return $itemStatsDao->dao->query($sql);
}
osc_add_hook('post_item_contact_post', 'silverscouts_plugin_hook_pre_contact_post');

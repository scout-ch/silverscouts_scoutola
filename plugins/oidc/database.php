<?php

function oidc_plugin_migration_version()
{
  return strval(oidc_plugin_get_preference('migration_version'));
}

function oidc_plugin_migrate_database()
{
  $migrationPaths = glob(dirname(__FILE__) . '/migrations/*.sql');
  $db = DBConnectionClass::newInstance()->getOsclassDb();
  $cmd = new DBCommandClass($db);

  foreach ($migrationPaths as $migrationPath) {
    $migrationVersion = basename($migrationPath, '.sql');
    if ($migrationVersion <= oidc_plugin_migration_version()) continue;

    $migrationSql = file_get_contents($migrationPath);

    if (!$cmd->importSQL($migrationSql)) {
      throw new Exception("Migration failed");
    }

    oidc_plugin_set_preference('migration_version', $migrationVersion);
  }
}

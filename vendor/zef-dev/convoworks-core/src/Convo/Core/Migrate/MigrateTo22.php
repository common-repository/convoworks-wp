<?php

namespace Convo\Core\Migrate;

class MigrateTo22 extends \Convo\Core\Migrate\AbstractMigration
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getVersion()
    {
        return 22;
    }
    public function migrateConfig($config)
    {
        if (isset($config["amazon"])) {
            if (!isset($config["amazon"]["interfaces"])) {
                $config["amazon"]["interfaces"] = [];
            }
        }
        return parent::migrateConfig($config);
    }
}

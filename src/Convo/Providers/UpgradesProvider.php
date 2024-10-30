<?php

namespace Convo\Providers;

use Exception;
class UpgradesProvider
{
    /**
     * DB updates and callbacks that need to be run per version.
     * Naming convention for upgrade methods is convoDBVERSIONDescriptionOfWhatWeAreDoing
     * For example convo101RemoveFieldsFromOrdersTable (where 101 means db version 1.0.1)
     *
     * IMPORTANT: if you add new version here, please update CONVO_DB_VERSION constant in root convo-plugin.php
     *
     * @var array
     */
    protected $dbUpdates = ['1.0.1' => ['add101ServicesTables'], '1.0.2' => ['add102ServiceReleaseMeta'], '1.0.3' => ['add103OAuthTable'], '1.0.4' => ['update104ServiceParamTable', 'add104CacheTable'], '1.0.5' => ['drop105OauthTable'], '1.0.6' => ['add106OServiceConversationLogTable'], '1.0.7' => ['add107ServiceConversationLogTableIndexes'], '1.0.8' => ['remove108ServiceConversationLogTableIndexes', 'update108ServiceConversationLogTable', 'add108ServiceConversationLogTableIndexes'], '1.0.9' => ['recreate109OServiceConversationLogTable'], '1.0.10' => ['update110ServiceConversationLogTableIndexes'], '1.0.11' => ['update111ConvoServiceReleasesTable', 'update111ConvoServiceVersionsTable'], '1.0.12' => ['update112MuFix']];
    /**
     * name of the db update version option in database
     */
    protected $version = 'convo_db_version';
    /**
     * Get list of DB update callbacks.
     *
     * @return array
     */
    public function getDbUpdateCallbacks()
    {
        return $this->dbUpdates;
    }
    /**
     * Is a DB update needed?
     *
     * @return boolean
     */
    public function needsDbUpdate($currentDbVersion)
    {
        $updates = $this->getDbUpdateCallbacks();
        $updateVersions = \array_keys($updates);
        \usort($updateVersions, 'version_compare');
        return !\is_null($currentDbVersion) && \version_compare($currentDbVersion, \end($updateVersions), '<');
    }
    /**
     * Run all needed DB updates according to db version.
     */
    public function run()
    {
        $dbVersion = get_option($this->version);
        \error_log('CONVO UPDATE: Current version [' . $dbVersion . ']');
        if ($this->_fixMuBrokenInstallation($dbVersion)) {
            return;
        }
        if ($this->needsDbUpdate($dbVersion)) {
            \error_log('CONVO UPDATE: updating DB');
            foreach ($this->getDbUpdateCallbacks() as $version => $updateCallbacks) {
                \error_log('CONVO UPDATE: Cheking version [' . $version . ']');
                if (\version_compare($dbVersion, $version, '<')) {
                    \error_log('CONVO UPDATE: Updating version [' . $version . ']');
                    foreach ($updateCallbacks as $updateCallback) {
                        \error_log('CONVO UPDATE: Applying patch [' . $updateCallback . ']');
                        $this->{$updateCallback}();
                    }
                    // raising db option
                    update_option($this->version, $version);
                }
            }
        }
    }
    /**
     * @TODO: remove after few versions
     * @param string $dbVersion
     */
    private function _fixMuBrokenInstallation($dbVersion)
    {
        $TO_FIX = '1.0.11';
        if ($dbVersion !== $TO_FIX || !is_multisite()) {
            return \false;
        }
        \error_log('CONVO UPDATE: Applying 1.0.11 - Broken MU patch');
        foreach ($this->getDbUpdateCallbacks() as $version => $updateCallbacks) {
            \error_log('CONVO UPDATE: Applying version [' . $version . ']');
            foreach ($updateCallbacks as $updateCallback) {
                \error_log('CONVO UPDATE: Applying patch [' . $updateCallback . ']');
                $this->{$updateCallback}();
            }
            // raising db option
            update_option($this->version, $version);
        }
        return \true;
    }
    /**
     * Add Services table
     *
     * @throws Exception
     */
    protected function add101ServicesTables()
    {
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        $sql = "DROP TABLE IF EXISTS {$wpdb->prefix}service_params";
        $wpdb->query($sql);
        $sql = "DROP TABLE IF EXISTS {$wpdb->prefix}service_releases";
        $wpdb->query($sql);
        $sql = "DROP TABLE IF EXISTS {$wpdb->prefix}service_versions";
        $wpdb->query($sql);
        $sql = "DROP TABLE IF EXISTS {$wpdb->prefix}service_data";
        $wpdb->query($sql);
        $sql = "DROP TABLE IF EXISTS {$wpdb->prefix}convo_service_conversation_log";
        $wpdb->query($sql);
        $sql = "DROP TABLE IF EXISTS {$wpdb->prefix}wp_convo_cache";
        $wpdb->query($sql);
        $sql = "\n\t\tCREATE TABLE IF NOT EXISTS {$wpdb->prefix}convo_service_data (\n          service_id VARCHAR(255) NOT NULL,\n          workflow LONGTEXT NOT NULL DEFAULT '',\n          meta TEXT NOT NULL DEFAULT '',\n          config TEXT NOT NULL DEFAULT '',\n          PRIMARY KEY  (service_id)\n        );\n\t\t";
        dbDelta($sql);
        $sql = "\n\t        CREATE TABLE IF NOT EXISTS {$wpdb->prefix}convo_service_params (\n\t\t\t  `service_id` VARCHAR(255) NOT NULL,\n\t\t\t  `scope_type` VARCHAR(50) NOT NULL,\n\t\t\t  `level_type` VARCHAR(50) NOT NULL,\n\t\t\t  `key` VARCHAR(255) NOT NULL,\n\t\t\t  `value` LONGTEXT NOT NULL DEFAULT '',\n\t\t\t  UNIQUE INDEX  `{$wpdb->prefix}SERVICE_PARAMS_UNIQUE` (`service_id` ASC, `level_type` ASC, `scope_type` ASC, `key` ASC),\n\t\t\t  CONSTRAINT  `{$wpdb->prefix}FK_PARAMS_SERVICE`\n\t\t\t    FOREIGN KEY  (`service_id`)\n\t\t\t    REFERENCES  {$wpdb->prefix}convo_service_data (`service_id`)\n\t\t\t    ON DELETE NO ACTION\n\t\t\t    ON UPDATE NO ACTION\n\t\t\t    );\n\t    ";
        dbDelta($sql);
        $sql = "\n\t        CREATE TABLE IF NOT EXISTS {$wpdb->prefix}convo_service_releases (\n\t\t\t  `service_id` VARCHAR(255) NOT NULL,\n\t\t\t  `release_id` VARCHAR(50) NOT NULL,\n\t\t\t  `platform_id` VARCHAR(50) NOT NULL,\n\t\t\t  `version_id` VARCHAR(50) NOT NULL,\n\t\t\t  `type` VARCHAR(50) NOT NULL,\n\t\t\t  `stage` VARCHAR(50) NOT NULL,\n\t\t\t  `alias` VARCHAR(50) NOT NULL,\n\t\t\t  `time_created` INT NULL DEFAULT 0,\n\t\t\t  `time_updated` INT NULL DEFAULT 0,\n\t\t\t  UNIQUE INDEX  `{$wpdb->prefix}UNIQUE_SERVICE_RELEASE` (`service_id` ASC, `release_id` ASC),\n\t\t\t  CONSTRAINT  `{$wpdb->prefix}FK_REKLEASE_SERVICE`\n\t\t\t    FOREIGN KEY  (`service_id`)\n\t\t\t    REFERENCES  {$wpdb->prefix}convo_service_data (`service_id`)\n\t\t\t    ON DELETE NO ACTION\n\t\t\t    ON UPDATE NO ACTION\n\t\t\t    );\n\t    ";
        dbDelta($sql);
        $sql = "\n\t        CREATE TABLE IF NOT EXISTS {$wpdb->prefix}convo_service_versions (\n\t\t\t  `service_id` VARCHAR(255) NOT NULL,\n\t\t\t  `version_id` VARCHAR(50) NOT NULL,\n\t\t\t  `release_id` VARCHAR(50) NULL DEFAULT NULL,\n\t\t\t  `version_tag` VARCHAR(255) NULL DEFAULT NULL,\n\t\t\t  `workflow` LONGTEXT NOT NULL DEFAULT '',\n\t\t\t  `config` TEXT NOT NULL DEFAULT '',\n\t\t\t  `time_created` INT NULL DEFAULT 0,\n\t\t\t  `time_updated` INT NULL DEFAULT 0,\n\t\t\t  UNIQUE INDEX  `{$wpdb->prefix}UNIQUE_SERVICE_VERSION` (`service_id` ASC, `version_id` ASC),\n\t\t\t  CONSTRAINT  `{$wpdb->prefix}FK_VERSION_SERVICE`\n\t\t\t    FOREIGN KEY  (`service_id`)\n\t\t\t    REFERENCES  {$wpdb->prefix}convo_service_data (`service_id`)\n\t\t\t    ON DELETE NO ACTION\n\t\t\t    ON UPDATE NO ACTION\n\t\t\t    );\n\t    ";
        dbDelta($sql);
    }
    protected function add102ServiceReleaseMeta()
    {
        global $wpdb;
        $wpdb->query("ALTER TABLE {$wpdb->prefix}convo_service_releases ADD COLUMN `meta` LONGTEXT NOT NULL AFTER `alias`");
    }
    protected function add103OAuthTable()
    {
        global $wpdb;
        $sql = "\n\t        CREATE TABLE IF NOT EXISTS {$wpdb->prefix}convo_oauth (\n\t          `user_id` INTEGER NOT NULL,\n\t\t\t  `service_id` VARCHAR(255) NOT NULL,\n\t\t\t  `type` VARCHAR(50) NOT NULL,\n\t\t\t  `code` VARCHAR(255) NULL DEFAULT NULL,\n\t\t\t  `redeemed` TINYINT NULL DEFAULT 0,\n\t\t\t  `accessToken` LONGTEXT NOT NULL DEFAULT ''\n\t\t\t    );\n\t    ";
        $wpdb->query($sql);
    }
    protected function update104ServiceParamTable()
    {
        global $wpdb;
        $sql = "\n\t        ALTER TABLE `{$wpdb->prefix}convo_service_params`\n    \t\t\tADD COLUMN `time_created` INT NULL DEFAULT 0,\n    \t\t\tADD COLUMN `time_updated` INT NULL DEFAULT 0;\n\t    ";
        $wpdb->query($sql);
    }
    protected function add104CacheTable()
    {
        global $wpdb;
        $sql = "\n\t        CREATE TABLE IF NOT EXISTS {$wpdb->prefix}convo_cache (\n\t          `key` VARCHAR(255) NOT NULL,\n  \t\t\t  `value` LONGTEXT NOT NULL DEFAULT '',\n  \t\t\t  `time_created` INT NULL DEFAULT 0,\n  \t\t\t  `expires` INT NULL DEFAULT 0,\n  \t\t\t\tPRIMARY KEY (`key`)\n\t\t\t);\n\t    ";
        $wpdb->query($sql);
    }
    protected function drop105OauthTable()
    {
        global $wpdb;
        $sql = "DROP TABLE IF EXISTS {$wpdb->prefix}convo_oauth;";
        $wpdb->query($sql);
    }
    protected function add106OServiceConversationLogTable()
    {
        global $wpdb;
        $sql = "\n\t        CREATE TABLE IF NOT EXISTS {$wpdb->prefix}convo_service_conversation_log (\n\t          `request_id` VARCHAR(255) NOT NULL,\n\t          `service_id` VARCHAR(255) NOT NULL,\n\t          `session_id` VARCHAR(255) NOT NULL,\n\t          `device_id` VARCHAR(255) NOT NULL,\n\t          `stage` VARCHAR(255) NOT NULL,\n\t          `status_code` VARCHAR(255) NOT NULL,\n\t          `platform` VARCHAR(255) NOT NULL,\n\t          `intent_name` VARCHAR(255) NOT NULL DEFAULT '',\n\t          `time_created` INT NULL DEFAULT 0,\n\t          `time_elapsed` FLOAT NULL DEFAULT 0,\n  \t\t\t  `request` LONGTEXT NOT NULL DEFAULT '',\n  \t\t\t  `response` LONGTEXT NOT NULL DEFAULT '',\n  \t\t\t  `intent_slots` LONGTEXT NOT NULL DEFAULT '',\n  \t\t\t  `service_variables` LONGTEXT NOT NULL DEFAULT '',\n  \t\t\t  `error_stack_trace` LONGTEXT NOT NULL DEFAULT '',\n  \t\t\t\tPRIMARY KEY (`request_id`)\n\t\t\t);\n\t    ";
        $wpdb->query($sql);
    }
    protected function add107ServiceConversationLogTableIndexes()
    {
        global $wpdb;
        $container = \Convo\Providers\ConvoWPPlugin::getPublicDiContainer();
        /** @var \Psr\Log\LoggerInterface $logger */
        $logger = $container->get('logger');
        $logger->info('Upgrading DB to version 1.0.7');
        $fieldsToBeIndexed = ['request_id', 'service_id', 'session_id', 'device_id', 'stage', 'status_code', 'platform', 'time_created'];
        $indexedFieldsSql = "\n            SHOW INDEXES FROM {$wpdb->prefix}convo_service_conversation_log;\n        ";
        $indexedFieldsRows = $wpdb->get_results($indexedFieldsSql, ARRAY_A);
        $alreadyIndexedFields = [];
        foreach ($indexedFieldsRows as $indexedFieldRow) {
            $alreadyIndexedFields[] = $indexedFieldRow['Column_name'];
        }
        $fieldsNotIndexed = [];
        foreach ($fieldsToBeIndexed as $filedToBeIndexed) {
            if (!\in_array($filedToBeIndexed, $alreadyIndexedFields)) {
                $fieldsNotIndexed[] = "`" . $filedToBeIndexed . "`";
            }
        }
        if (!empty($fieldsNotIndexed)) {
            foreach ($fieldsNotIndexed as $key => $value) {
                $createIndexSql = "\n\t            CREATE INDEX {$wpdb->prefix}convo_request_log_index_{$key} ON\n\t                {$wpdb->prefix}convo_service_conversation_log({$value});\n\t        ";
                $logger->info('Going to execute update query [' . $createIndexSql . ']');
                $wpdb->query($createIndexSql);
            }
        }
    }
    protected function remove108ServiceConversationLogTableIndexes()
    {
        global $wpdb;
        $container = \Convo\Providers\ConvoWPPlugin::getPublicDiContainer();
        /** @var \Psr\Log\LoggerInterface $logger */
        $logger = $container->get('logger');
        $logger->info('Upgrading DB to version 1.0.8');
        $indexedFieldsSql = "\n            SHOW INDEXES FROM {$wpdb->prefix}convo_service_conversation_log;\n        ";
        $indexedFieldsRows = $wpdb->get_results($indexedFieldsSql, ARRAY_A);
        $alreadyIndexedFields = [];
        foreach ($indexedFieldsRows as $indexedFieldRow) {
            $logger->info('Checking index kex name [' . $indexedFieldRow['Key_name'] . ']');
            if (\strpos($indexedFieldRow['Key_name'], 'convo_request_log_index') !== \false) {
                $alreadyIndexedFields[] = $indexedFieldRow['Key_name'];
            }
        }
        if (!empty($alreadyIndexedFields)) {
            foreach ($alreadyIndexedFields as $value) {
                $createIndexSql = "\n\t            DROP INDEX {$value} ON\n\t                {$wpdb->prefix}convo_service_conversation_log;\n\t        ";
                $logger->info('Going to execute drop query [' . $createIndexSql . ']');
                $wpdb->query($createIndexSql);
            }
        }
    }
    protected function update108ServiceConversationLogTable()
    {
        global $wpdb;
        $indexedFieldsSql = "\n            SHOW COLUMNS FROM {$wpdb->prefix}convo_service_conversation_log;\n        ";
        $tableColumnsRows = $wpdb->get_results($indexedFieldsSql, ARRAY_A);
        $tableColumns = [];
        foreach ($tableColumnsRows as $tableColumnsRow) {
            $tableColumns[] = $tableColumnsRow['Field'];
        }
        if (\in_array('status_code', $tableColumns)) {
            $sql = "ALTER TABLE {$wpdb->prefix}convo_service_conversation_log DROP COLUMN `status_code`;";
            $wpdb->query($sql);
        }
        if (!\in_array('error', $tableColumns) && !\in_array('test_view', $tableColumns)) {
            $sql = "\n\t        ALTER TABLE {$wpdb->prefix}convo_service_conversation_log\n    \t\t\tADD COLUMN `error` VARCHAR(255) NULL DEFAULT '',\n    \t\t\tADD COLUMN `test_view` BOOLEAN;\n\t    ";
            $wpdb->query($sql);
        }
    }
    protected function add108ServiceConversationLogTableIndexes()
    {
        global $wpdb;
        $container = \Convo\Providers\ConvoWPPlugin::getPublicDiContainer();
        /** @var \Psr\Log\LoggerInterface $logger */
        $logger = $container->get('logger');
        $logger->info('Upgrading DB to version 1.0.8');
        $fieldsToBeIndexed = ['service_id', 'session_id', 'device_id', 'stage', 'test_view', 'platform', 'time_created'];
        $indexedFieldsSql = "\n            SHOW INDEXES FROM {$wpdb->prefix}convo_service_conversation_log;\n        ";
        $indexedFieldsRows = $wpdb->get_results($indexedFieldsSql, ARRAY_A);
        $alreadyIndexedFields = [];
        foreach ($indexedFieldsRows as $indexedFieldRow) {
            $alreadyIndexedFields[] = $indexedFieldRow['Column_name'];
        }
        $fieldsNotIndexed = [];
        foreach ($fieldsToBeIndexed as $filedToBeIndexed) {
            if (!\in_array($filedToBeIndexed, $alreadyIndexedFields)) {
                if ($filedToBeIndexed === 'service_id') {
                    $fieldsNotIndexed[] = $filedToBeIndexed . "(100)";
                } else {
                    if ($filedToBeIndexed === 'session_id') {
                        $fieldsNotIndexed[] = $filedToBeIndexed . "(255)";
                    } else {
                        if ($filedToBeIndexed === 'device_id') {
                            $fieldsNotIndexed[] = $filedToBeIndexed . "(255)";
                        } else {
                            if ($filedToBeIndexed === 'stage') {
                                $fieldsNotIndexed[] = $filedToBeIndexed . "(10)";
                            } else {
                                if ($filedToBeIndexed === 'platform') {
                                    $fieldsNotIndexed[] = $filedToBeIndexed . "(10)";
                                } else {
                                    $fieldsNotIndexed[] = $filedToBeIndexed;
                                }
                            }
                        }
                    }
                }
            }
        }
        $fieldsToBeIndexedQueryPartString = \join(', ', $fieldsNotIndexed);
        $logger->info('Fields to be indexed [' . $fieldsToBeIndexedQueryPartString . ']');
        if (!empty($fieldsToBeIndexedQueryPartString)) {
            $createIndexSql = "\n\t            CREATE INDEX convo_request_log_indexes ON\n\t                {$wpdb->prefix}convo_service_conversation_log({$fieldsToBeIndexedQueryPartString});\n\t        ";
            $logger->info('Going to execute query [' . $createIndexSql . ']');
            $wpdb->query($createIndexSql);
        }
    }
    protected function recreate109OServiceConversationLogTable()
    {
        global $wpdb;
        $container = \Convo\Providers\ConvoWPPlugin::getPublicDiContainer();
        /** @var \Psr\Log\LoggerInterface $logger */
        $logger = $container->get('logger');
        $logger->info('Upgrading DB to version 1.0.9');
        // drop old table
        $dropSql = "DROP TABLE IF EXISTS {$wpdb->prefix}convo_service_conversation_log;";
        $wpdb->query($dropSql);
        // create new table
        $createSql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}convo_service_conversation_log\n        (\n            request_id        VARCHAR(255)            NOT NULL PRIMARY KEY,\n            service_id        VARCHAR(100)            NOT NULL,\n            session_id        VARCHAR(255)            NOT NULL,\n            device_id         VARCHAR(255)            NOT NULL,\n            stage             VARCHAR(10)             NOT NULL,\n            platform          VARCHAR(20)             NOT NULL,\n            intent_name       VARCHAR(255) DEFAULT '' NOT NULL,\n            time_created      INT          DEFAULT 0  NOT NULL,\n            request           LONGTEXT     DEFAULT '' NOT NULL,\n            response          LONGTEXT     DEFAULT '' NOT NULL,\n            intent_slots      LONGTEXT     DEFAULT '' NOT NULL,\n            service_variables LONGTEXT     DEFAULT '' NOT NULL,\n            error_stack_trace LONGTEXT     DEFAULT '' NOT NULL,\n            time_elapsed      FLOAT        DEFAULT 0  NOT NULL,\n            error             VARCHAR(255) DEFAULT '' NULL,\n            test_view         TINYINT(1)              NOT NULL\n        );";
        $wpdb->query($createSql);
        // attach indexes to recently created table
        $fieldsToBeIndexed = ['service_id', 'session_id', 'device_id', 'stage', 'test_view', 'platform', 'time_created'];
        $indexedFieldsSql = "\n            SHOW INDEXES FROM {$wpdb->prefix}convo_service_conversation_log;\n        ";
        $indexedFieldsRows = $wpdb->get_results($indexedFieldsSql, ARRAY_A);
        $alreadyIndexedFields = [];
        foreach ($indexedFieldsRows as $indexedFieldRow) {
            $alreadyIndexedFields[] = $indexedFieldRow['Column_name'];
        }
        $fieldsNotIndexed = [];
        foreach ($fieldsToBeIndexed as $filedToBeIndexed) {
            if (!\in_array($filedToBeIndexed, $alreadyIndexedFields)) {
                $fieldsNotIndexed[] = $filedToBeIndexed;
            }
        }
        $fieldsToBeIndexedQueryPartString = \join(', ', $fieldsNotIndexed);
        $logger->info('Fields to be indexed [' . $fieldsToBeIndexedQueryPartString . ']');
        if (!empty($fieldsToBeIndexedQueryPartString)) {
            $createIndexSql = "\n\t            CREATE INDEX convo_request_log_indexes ON\n\t                {$wpdb->prefix}convo_service_conversation_log({$fieldsToBeIndexedQueryPartString});\n\t        ";
            $logger->info('Going to execute query [' . $createIndexSql . ']');
            $wpdb->query($createIndexSql);
        }
    }
    protected function update110ServiceConversationLogTableIndexes()
    {
        global $wpdb;
        $container = \Convo\Providers\ConvoWPPlugin::getPublicDiContainer();
        /** @var \Psr\Log\LoggerInterface $logger */
        $logger = $container->get('logger');
        $logger->info('Upgrading DB to version 1.0.10');
        $createIndexSql = "\n\t            ALTER TABLE {$wpdb->prefix}convo_service_conversation_log\n                    CHANGE COLUMN `platform` `platform` VARCHAR(30) NOT NULL AFTER `stage`;\n\t    ";
        $logger->info('Going to execute query [' . $createIndexSql . ']');
        $wpdb->query($createIndexSql);
    }
    protected function update111ConvoServiceReleasesTable()
    {
        global $wpdb;
        $sql = "\n\t        ALTER TABLE `{$wpdb->prefix}convo_service_releases`\n    \t\t\tADD COLUMN `platform_release_data` TEXT NULL DEFAULT NULL\n\t    ";
        $wpdb->query($sql);
    }
    protected function update111ConvoServiceVersionsTable()
    {
        global $wpdb;
        $sql = "\n\t        ALTER TABLE `{$wpdb->prefix}convo_service_versions`\n    \t\t\tADD COLUMN `platform_id` VARCHAR(50) NULL DEFAULT NULL,\n    \t\t\tADD COLUMN `platform_version_data` TEXT NULL DEFAULT NULL;\n\t    ";
        $wpdb->query($sql);
    }
    protected function update112MuFix()
    {
        // NOP
    }
}

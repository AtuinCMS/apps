<?php


use yii\db\Schema;


class m000000_000001_atuin_app_migration extends \yii\db\Migration
{

    private function appsTableName()
    {
        return \atuin\apps\models\App::tableName();
    }

    private function appConnectionsTableName()
    {
        return \atuin\apps\models\AppConnections::tableName();
    }


    private function pageTableName()
    {
        return \atuin\engine\models\Page::tableName();
    }

    private function pluginTableName()
    {
        return \atuin\apps\models\Plugin::tableName();
    }

    private function pagePluginTableName()
    {
        return \atuin\engine\models\PagePluginData::tableName();
    }

    private function staticPluginTableName()
    {
        return \atuin\engine\widgets\staticPage\models\StaticPlugin::tableName();
    }

    private function pageDesignableName()
    {
        return \atuin\engine\models\PageDesign::tableName();
    }

    private function pageReferenceTableName()
    {
        return \atuin\engine\models\PageReference::tableName();
    }

    private function pageSectionsTableName()
    {
        return \atuin\engine\models\PageSections::tableName();
    }

    public function safeUp()
    {
        $tableOptions = null;
        if (Yii::$app->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /**
         * Apps table that will hold the app info
         */
        $this->createTable($this->appsTableName(), [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(25) NOT NULL',
            'className' => Schema::TYPE_STRING . '(255) NOT NULL',
            'directory' => Schema::TYPE_STRING . '(255) NOT NULL',
            'namespace' => Schema::TYPE_STRING . '(255) NOT NULL',
            'alias' => Schema::TYPE_STRING . '(255) NOT NULL',
            'install_date' => Schema::TYPE_DATETIME,
            'status' => "enum('active', 'inactive') DEFAULT 'inactive'",
            // module will be defined in the admin section
            'backend' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 1',
            // module will be defined at frontend
            'frontend' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 1',
            // checks if is a core module, atuin won't be able to uninstall or deactivate this modules
            'core_module' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 0',
        ], $tableOptions);

        // add indexes for performance optimization
        $this->createIndex('{{%apps_name}}', $this->appsTableName(), ['name'], TRUE);
        $this->createIndex('{{%apps_className}}', $this->appsTableName(), ['className'], TRUE);
        $this->createIndex('{{%apps_backend_status}}', $this->appsTableName(), ['backend', 'status']);
        $this->createIndex('{{%apps_frontend_status}}', $this->appsTableName(), ['frontend', 'status']);
        $this->createIndex('{{%apps_core_module}}', $this->appsTableName(), ['core_module']);


        /**
         * AppConnections table will store links to every connection Apps will have
         */
        $this->createTable($this->appConnectionsTableName(), [
            'id' => Schema::TYPE_PK,
            'type' => Schema::TYPE_STRING . ' NOT NULL',
            'app_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'reference_id' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        // add indexes for performance optimization
        $this->createIndex('{{%apps_connections_type_app}}', $this->appConnectionsTableName(), ['type', 'app_id']);
        $this->createIndex('{{%apps_connections_appid}}', $this->appConnectionsTableName(), ['app_id']);
        $this->createIndex('{{%apps_connections_menuItem}}', $this->appConnectionsTableName(), ['reference_id']);

        $this->addForeignKey('{{%apps_connections_apps}}', $this->appConnectionsTableName(), ['app_id'], $this->appsTableName(), ['id'], 'cascade', NULL);


        /**
         * Pages table holding info for automatic page generation.
         * Linked to Apps table.
         */

        $this->createTable($this->pageTableName(), [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255)',
       //     'type' => "enum('static', 'dynamic') DEFAULT 'static'",
            // will hold extra info for the page like classes that depend from them
            'parameters' => Schema::TYPE_TEXT
        ], $tableOptions);

        // add indexes for performance optimization
        $this->createIndex('{{%page_name}}', $this->pageTableName(), ['name'], TRUE);


        /**
         * Plugins table holding info for the plugins loaded in the system.
         * Linked to Apps table.
         */
        $this->createTable($this->pluginTableName(), [
            'id' => Schema::TYPE_PK,
            'className' => Schema::TYPE_STRING . '(255) NOT NULL',
            'directory' => Schema::TYPE_STRING . '(255) NOT NULL',
            'namespace' => Schema::TYPE_STRING . '(255) NOT NULL',
            // plugin will be only available for pages from its own app
            'private' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 1',
        ], $tableOptions);


        /**
         * This table will store all the data concerned with linking Pages and the Plugins and the
         * data that store of each Page.
         */
        $this->createTable($this->pagePluginTableName(), [
            'id' => Schema::TYPE_PK,
            // activeRecord name that will be referenced to the page data
            // for example, webcomic and webcomicPage
            'className' => Schema::TYPE_STRING . '(255) NOT NULL',
            // id for the activeRecord references, for example, webcomic -> 12, webcomicPage -> 234
            'reference_id' => Schema::TYPE_INTEGER,
            // plugin id referenced to load the namespace and all necesary to load the
            // plugin data (for example -> comments)
            'plugin_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            // plugin id from the data (for example comments -> id 12)
            'plugin_reference_id' => Schema::TYPE_INTEGER
        ], $tableOptions);

        // add indexes for performance optimization
        $this->createIndex('{{%pageplugin_classname}}', $this->pagePluginTableName(), ['className', 'reference_id']);
        $this->createIndex('{{%pageplugin_app_id}}', $this->pagePluginTableName(), ['plugin_id', 'plugin_reference_id']);


        /**
         * Static Plugin for Static Pages
         */
        $this->createTable($this->staticPluginTableName(), [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . '(255) NOT NULL',
            'url' => Schema::TYPE_STRING . '(255) NOT NULL',
            'text' => Schema::TYPE_TEXT,
            'creation_date' => Schema::TYPE_DATETIME,
            'update_date' => Schema::TYPE_DATETIME,
            'author_id' => Schema::TYPE_INTEGER,
            'last_editor_id' => Schema::TYPE_INTEGER
        ], $tableOptions);

        /**
         * Sections that will hold the plugins in the pages
         */
        $this->createTable($this->pageSectionsTableName(), [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'cols' => Schema::TYPE_INTEGER,
            'cols_sizes' => Schema::TYPE_STRING
        ], $tableOptions);


        /**
         * This table will hold the specifics to link the page designs with the
         * pages per se. We are using a junction table because probably we will
         * need different page designs for the same page depending from the data
         * it's loaded in them
         */
        $this->createTable($this->pageReferenceTableName(), [
            'id' => Schema::TYPE_PK,
            // Page Id Where the page will be assigned
            'page_id' => Schema::TYPE_INTEGER,
            // Pages hold classes that will reference when loaded
            // for example comic pages will reference webcomic -> id
            // this is the classname that will be related to the next field
            'className' => Schema::TYPE_STRING . '(255) NULL',
            // Field id that relates with the previous field
            'reference_id' => Schema::TYPE_INTEGER,
        ], $tableOptions);


        // add indexes for performance optimization
        $this->createIndex('{{%pagedesign_classname}}', $this->pageReferenceTableName(), ['className', 'reference_id']);
        $this->createIndex('{{%pagedesign_page_id}}', $this->pageReferenceTableName(), ['page_id']);
        $this->createIndex('{{%pagedesign_page_all}}', $this->pageReferenceTableName(), ['page_id', 'className', 'reference_id']);

        $this->addForeignKey('{{%pagedesign_page}}', $this->pageReferenceTableName(), ['page_id'], $this->pageTableName(), ['id'], 'cascade', NULL);


        $this->createTable($this->pageDesignableName(), [
            'id' => Schema::TYPE_PK,
            'page_reference_id' => Schema::TYPE_INTEGER,
            // Section id of the web
            'section_id' => Schema::TYPE_INTEGER,
            'plugins' => Schema::TYPE_TEXT
        ], $tableOptions);


    }


    public function safeDown()
    {
    }
}
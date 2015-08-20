<?php

namespace atuin\apps\config;

use atuin\apps\models\Plugin;
use atuin\config\models\ModelConfig;
use atuin\engine\models\Page;
use atuin\engine\models\PageDesign;
use atuin\engine\models\PageReference;
use atuin\engine\models\PageSections;


/**
 * Class ConfigSkeleton
 * @package common\engine\module_skeleton\libraries
 *
 * Class called to install a module in the CMS.
 *
 * Here must be all the automatic changes in the system that will be necessary to install a new module.
 *
 */
class AtuinConfig extends \atuin\skeleton\config\AtuinConfig
{

    /**
     * @inheritdoc
     */
    public function upMigration()
    {

    }

    /**
     * @inheritdoc
     */
    public function downMigration()
    {

    }

    /**
     * @inheritdoc
     */
    public function upMenu()
    {
        $this->menuItems->add_menu_item('apps_head', NULL, NULL, 'Apps', 'plug', NULL);
        $this->menuItems->add_menu_item('apps_list', '@web/apps', 'apps_head', 'Intalled Apps', 'list-ul', NULL);
        $this->menuItems->add_menu_item('apps_new', '@web/apps/market', 'apps_head', 'New App', 'plus', NULL);
        $this->menuItems->add_menu_item('pages_head', NULL, '@web/pages_head', 'Pages', 'file-o', NULL);
        $this->menuItems->add_menu_item('pages_dynamic', '@web/pages/dynamic', 'pages_head', 'Dynamic Pages', 'file', NULL);
    }


    /**
     * @inheritdoc
     */
    public function downMenu()
    {

    }

    /**
     * @inheritdoc
     */
    public function upConfig()
    {

        // Setting translations for engine
        ModelConfig::addConfig(NULL, 'components', 'i18n', 'translations',
            [
                'admin' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@vendor/atuin/engine/messages',
                ],
                'atuin-installation' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@vendor/atuin/engine/messages',
                ],
                'menu' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@vendor/atuin/engine/messages',
                ]
            ], FALSE);


        // Adding basic theme suport

        ModelConfig::addConfig(NULL, 'components', 'view', 'theme',
            [
                'pathMap' => [
                    '@vendor/atuin/engine/views' => '@vendor/atuin/engine/themes/basic',
                    '@vendor/atuin/engine' => '@vendor/atuin/engine/themes/basic'
                ]
            ], FALSE);

        // Adds HomeUrl and RequestUrl for backend and frontend

        $base_directory = '/' . basename(dirname(\Yii::$app->getVendorPath()));

        ModelConfig::addConfig('app-backend', 'components', 'request', 'baseUrl', $base_directory . '/admin', FALSE);
        ModelConfig::addConfig('app-backend', NULL, NULL, 'homeUrl', $base_directory . '/admin', FALSE);

        ModelConfig::addConfig('app-frontend', 'components', 'request', 'baseUrl', $base_directory, FALSE);
        ModelConfig::addConfig('app-frontend', NULL, NULL, 'homeUrl', $base_directory, FALSE);

        // Adding Kartik GridView as basic grid
        ModelConfig::addConfig(NULL, 'modules', 'gridview', 'class', '\kartik\grid\Module', FALSE);

        // Adding basic app market url
        ModelConfig::addConfig('app-backend', 'modules', 'apps', 'appMarketUrls', ['http://webhost.zoltan.es/appMarket.json'], TRUE);

    }


    /**
     * @inheritdoc
     */
    public function downConfig()
    {
    }

    /**
     * @inheritdoc
     */
    public function upManual()
    {


        // Adds the basic Page Sections
        $section = new PageSections();
        $section->name = 'One column';
        $section->cols = 1;
        $section->cols_sizes = '12';
        $section->save();

    }


    /**
     * @inheritdoc
     */
    public function downManual()
    {

    }

}
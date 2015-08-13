<?php

namespace atuin\apps\models;

use yii\base\Model;

/**
 * Class ModelStaticPage
 * @package atuin\apps\models
 *
 * Uses StaticPlugin, yii2-route/models/Route and Page
 */
class ModelStaticPage extends Model
{
    public $id;
    public $name;
    public $type;
    public $parameters;
    public $url;
    public $text;
}
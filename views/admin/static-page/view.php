<?php

use yii\helpers\Html;

/* @var \atuin\engine\widgets\staticPage\models\StaticPlugin $staticPlugin */

$this->title = Yii::t('admin', 'View static page');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $staticPlugin->title;
?>

<div class="content-header">
    <h2><?= Html::encode($staticPlugin->title) ?></h2>
</div>
<div class="content body">
    <div class="box box-default">
        <div class="box-body">
            <div class="form-group">
                <?= Html::label($staticPlugin->getAttributeLabel('title')) ?>
                <?= Html::activeTextInput($staticPlugin, 'title', ['readOnly' => TRUE, 'class' => 'form-control']) ?>
            </div>
            <div class="form-group">
                <?= Html::label($staticPlugin->getAttributeLabel('url')) ?>
                <?= Html::activeTextInput($staticPlugin, 'url', ['readOnly' => TRUE, 'class' => 'form-control']) ?>
            </div>
            <div class="form-group">
                <?= Html::label($staticPlugin->getAttributeLabel('text')) ?>

                <blockquote>
                    <?= \yii\helpers\HtmlPurifier::process($staticPlugin->text); ?>
               </blockquote>
            </div>
        </div>
    </div>
</div>

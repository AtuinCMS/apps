<?php

use yii\helpers\Html;

/* @var \atuin\engine\widgets\staticPage\models\StaticPlugin $staticPlugin */

$this->title = Yii::t('admin', 'Create Page');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-header">
    <h2><?= Html::encode($this->title) ?></h2>
</div>
<div class="content body">
    <div class="box box-default">
        <div class="box-body">
            <?= $this->render('_form', [
                'staticPlugin' => $staticPlugin,
            ]) ?>

        </div>
    </div>
</div>

<?php
/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ArrayDataProvider */

$this->title = Yii::t('admin', 'App Market');
?>

<div class="content-header">
    <h2><?= \yii\helpers\Html::encode($this->title) ?></h2>
</div>
<div class="content body">
    <div class="box box-default">
        <div class="box-body">
            <p>
                <?php
                echo \yii\widgets\ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemView' => '_marketEntry',
                ]);
                ?>
            </p>
        </div>
    </div>
</div>

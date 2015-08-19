<div class="col-lg-6">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><?= $model['name'] ?>
                <small>v. <?= $model['version'] ?></small>
            </h3>
        </div>
        <div class="box-body">
            <div class="col-md-6">
                <p><?= $model['description'] ?></p>
            </div>
            <div class="col-md-6">
                <?= \yii\helpers\Html::a(Yii::t('admin', 'Install'),
                    Yii::$app->getHomeUrl() . '/apps/install/' . $model['id'], [
                        'class' => "btn btn-default pull-right",
                        'title' => Yii::t('admin', 'Install'),
                        'data-confirm' => Yii::t('admin', 'Are you sure you want to install this App?'),
                        'data-method' => 'post',
                    ]) ?>
            </div>
        </div>
    </div>
</div>
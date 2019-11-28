<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model yiiplus\appversion\modules\admin\models\Version */
/* @var $channelVersions yiiplus\appversion\modules\admin\models\ChannelVersion */

$this->title = 'Update Version: ' . $model->app->name . " 版本名：" . $model->name . " 版本号：" . $model->code;
$this->params['breadcrumbs'][] = ['label' => 'Versions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="version-update">
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

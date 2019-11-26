<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model yiiplus\appversion\modules\admin\models\Channel */

$this->title = 'Update Channel: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Channels', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="channel-update">
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
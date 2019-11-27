<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yiiplus\appversion\modules\admin\models\App;
use yiiplus\appversion\modules\admin\models\Version;

/* @var $this yii\web\View */
/* @var $model yiiplus\appversion\modules\admin\models\VersionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="version-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'app_id')->dropDownList(App::getAppOptions()) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'platform')->dropDownList(App::PLATFORM_OPTIONS) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'status')->dropDownList(Version::STATUS_TYPE) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'type')->dropDownList(Version::UPDATE_TYPE) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'code') ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'min_code') ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'name') ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'min_name') ?>
        </div>
    </div>




    <?php // echo $form->field($model, 'min_name') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'platform') ?>

    <?php // echo $form->field($model, 'scope') ?>

    <?php // echo $form->field($model, 'desc') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'operated_id') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

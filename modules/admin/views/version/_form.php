<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yiiplus\appversion\modules\admin\models\App;
use yiiplus\appversion\modules\admin\models\Version;
use yiiplus\appversion\modules\admin\models\Channel;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model yiiplus\appversion\modules\admin\models\Version */
/* @var $form yii\widgets\ActiveForm */
/* @var $channelVersions yii\widgets\ActiveForm */
?>

<div class="version-form">

    <?php $form = ActiveForm::begin();?>

    <?php
        $apps = App::find()->select(['id', 'name'])->asArray()->all();
    ?>
    <?= $form->field($model, 'app_id')
        ->dropdownList(App::getAppOptions(), ['prompt'=>'选择应用', "disabled" => 'disabled']); ?>

    <?= $form->field($model, 'code')->textInput() ?>

    <?= $form->field($model, 'min_code')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'min_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList(Version::UPDATE_TYPE, ['prompt'=>'选择更新类型']) ?>

    <?= $form->field($model, 'platform')->dropdownList(App::PLATFORM_OPTIONS, ['prompt'=>'选择平台', "disabled" => 'disabled']) ?>

    <?php
        $channels = Channel::find()->select(['id', 'name'])->where(['platform' => $model->platform])->asArray()->all();
    ?>

    <?= $form->field($model, 'scope')->dropdownList(Version::SCOPE_TYPE) ?>

    <?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


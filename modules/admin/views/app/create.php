<?php

/* @var $this yii\web\View */
/* @var $model yiiplus\appversion\modules\admin\models\App */

$this->title = '创建应用';
$this->params['breadcrumbs'][] = ['label' => '应用列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-create">
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

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $searchModel yiiplus\appversion\modules\admin\models\ChannelVersionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '版本渠道管理';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
if (!empty(Yii::$app->session->getFlash('success'))) {
    echo Alert::widget([
        'options' => ['class' => 'alert-info'],
        'body' => Yii::$app->session->getFlash('success'),
    ]);
} elseif (!empty(Yii::$app->session->getFlash('error'))) {
    echo Alert::widget([
        'options' => ['class' => 'alert-error'],
        'body' => Yii::$app->session->getFlash('error'),
    ]);
}
?>

<div class="box">
    <div class="box-header">
        <h3 class="box-title"><?= $this->title ?></h3>

        <div class="btn-group pull-right grid-create-btn" style="margin-right: 10px">
            <?= Html::a('<i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;新增',
                ['create', 'version_id' => Yii::$app->request->getQueryParam('version_id')], ['class' => 'btn btn-sm btn-success']) ?>
        </div>
    </div>

    <div class="box-body">
        <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
            <div class="row"><div class="col-sm-6"></div>
                <div class="col-sm-6"></div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'id',
                            [
                                'attribute'=>'app',
                                'value' => function ($model) {
                                    return $model->version->app->name ?? null;
                                }
                            ],
                            [
                                'attribute'=>'version_id',
                                'value' => function ($model) {
                                    return $model->version->name ?? null;
                                }
                            ],
                            [
                                'attribute'=>'channel_id',
                                'value' => function ($model) {
                                    return \yiiplus\appversion\modules\admin\models\Channel::findOne($model->channel_id)->name ?? null;
                                }
                            ],
                            'url:url',
                            [
                                'attribute'=>'operated_id',
                                'value' => function ($model) {
                                    return $model->operator->username ?? null;
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update} {delete}',
                                'buttons' => [
                                    'update' => function ($url, $model, $key) {
                                        $url .= "&version_id=" . Yii::$app->request->getQueryParam('version_id');
                                        return Html::a('编辑', $url, ['class' => 'btn btn-xs btn-primary']);
                                    },
                                    'delete' => function ($url, $model, $key) {
                                        return Html::a('删除', $url,
                                            ['class' => 'btn btn-xs btn-danger',
                                                'data-pjax'=>"0",
                                                'data-confirm'=>"您确定要删除此项吗？",
                                                'data-method'=>"post"]);
                                    },
                                ],
                                'header' => '操作',
                            ],
                        ],
                        'showFooter' => false,
                        'layout'=>"{items}<div class='col-sm-11'>{summary}<div class='pull-right'>{pager}</div></div>",
                        'tableOptions' => ['class' => 'table table-hover']
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

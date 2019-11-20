<?php
/**
 * 萌股 - 二次元潮流聚集地
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    陈思辰 <chensichen@mocaapp.cn>
 * @copyright 2019 重庆次元能力科技有限公司
 * @license   https://www.moego.com/licence.txt Licence
 * @link      http://www.moego.com
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use yii\widgets\ActiveForm;
use admin\modules\member\models\UserBan;

$this->title = 'App管理';
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
        <h3 class="box-title">App 列表</h3>
        <div class="btn-group pull-right grid-create-btn" style="margin-right: 10px">
            <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#add-app" title="新增">
                <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;新增</span>
            </button>
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
                            [
                                'header' => '操作id',
                                'attribute' => 'id',
                                'filter' => true, //不显示搜索框
                                'value' => function ($data) {
                                    return $data->id;
                                }
                            ],
                            'name',
                            'application_id',
                            [
                                'attribute'=>'operator',
                                'value' => function ($model) {
                                    return $model->operator->username ?? null;
                                }
                            ],
                            [
                                'attribute'=>'created_at',
                                'value' => function ($model) {
                                    return date("Y-m-d H:i:s", $model->created_at);
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => '操作',
//                                'headerOptions' => ['width' => '10'],
//                                'template' => '{view}',
//                                'buttons' => [
//                                    'view' => function ($url, $model, $key) {
//                                        return Html::a('版本管理', ['/appversion/version/index', 'id' => $key], ['class' => 'btn btn-sm btn-secondary',]);
//                                    }
//                                ],
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

<!-- APP版本创建窗口 -->
<div class="modal fade" id="add-app" tabindex="-1" role="dialog" aria-labelledby="add-app" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">App 新增</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php $form = ActiveForm::begin([
                'action' => ['store'],
                'method' => 'post',
            ]); ?>
            <div class="modal-body">
                    <?= $form->field($app, 'name')->textInput()->hint('请输入你的应用名称') ?>
                    <?= $form->field($app, 'application_id')->textInput()->hint('输入应用id')  ?>
            </div>
            <div class="modal-footer">
                <?= Html::button('关闭', ["class" => "btn btn-secondary", "data-dismiss"=>"modal"]) ?>
                <?= Html::submitButton('新增', ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<script>
    //点击复选框改变当前行背景色
    $('input[name="selection[]"]').click(function() {
        var tr = $('#tr_'+this.value);
        this.checked ? tr.addClass('tr_selected') : tr.removeClass('tr_selected');
    });
    //删除选中的所有记录
    function delall(url) {
        var ckbox = $('input[name="selection[]"]:checked'), ids = [];
        $.each(ckbox, function(i, o) {
            ids.push(o.value);
        });
        if(ids.length <= 0) return alert('请至少选择一条数据！');

        var okay = confirm('此操作将删除所有选中的数据，是否确认操作？');
        if(!okay) return;

        ids = ids.join(',');
        $.post(url, {'ids': ids}, function(ret) {
            if(ret.ok) {
                alert('恭喜你，操作成功！');
                window.location.reload();
            } else {
                alert(ret.msg ? ret.msg : '对不起，操作失败！');
            }
        }, 'json');
    }
</script>

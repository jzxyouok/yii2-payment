<?php
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yuncms\admin\widgets\Jarvis;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yuncms\payment\models\Payment;

/* @var $this yii\web\View */
/* @var $searchModel yuncms\payment\backend\models\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('payment', 'Manage Payment');
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("jQuery(\"#batch_deletion\").on(\"click\", function () {
    yii.confirm('" . Yii::t('app', 'Are you sure you want to delete this item?') . "',function(){
        var ids = jQuery('#gridview').yiiGridView(\"getSelectedRows\");
        jQuery.post(\"/payment/payment/batch-delete\",{ids:ids});
    });
});", View::POS_LOAD);
?>
<section id="widget-grid">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 payment-index">
            <?php Pjax::begin(); ?>
            <?php Jarvis::begin([
                'noPadding' => true,
                'editbutton' => false,
                'deletebutton' => false,
                'header' => Html::encode($this->title),
                'bodyToolbarActions' => [
                    [
                        'label' => Yii::t('payment', 'Manage Payment'),
                        'url' => ['index'],
                    ],
                    [
                        'options' => ['id' => 'batch_deletion', 'class' => 'btn btn-sm btn-danger'],
                        'label' => Yii::t('payment', 'Batch Deletion'),
                        'url' => 'javascript:void(0);',
                    ]
                ]
            ]); ?>
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'options' => ['id' => 'gridview'],
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        "name" => "id",
                    ],
                    //['class' => 'yii\grid\SerialColumn'],
                    'id',
                    'order_id',
                    'pay_id',
                    'user_id',
                    'user.username',
                    'name',
                    'gateway',
                    'currency',
                    'money',
                    [
                        'header' => Yii::t('payment', 'Pay Type'),
                        'value' => function ($model) {
                            if ($model->pay_type == Payment::TYPE_ONLINE) {
                                return Yii::t('payment', 'Online Payment');
                            } else if ($model->pay_type == Payment::TYPE_OFFLINE) {
                                return Yii::t('payment', 'Office Payment');
                            } else if ($model->pay_type == Payment::TYPE_RECHARGE) {
                                return Yii::t('payment', 'Recharge Payment');
                            } else if ($model->pay_type == Payment::TYPE_COIN) {
                                return Yii::t('payment', 'Coin Payment');
                            }
                        },
                        'format' => 'raw'
                    ],
                    [
                        'header' => Yii::t('payment', 'Pay State'),
                        'value' => function ($model) {
                            if ($model->pay_state == Payment::STATUS_NOT_PAY) {
                                return Yii::t('payment', 'State Not Pay');
                            } else if ($model->pay_state == Payment::STATUS_SUCCESS) {
                                return Yii::t('payment', 'State Success');
                            } else if ($model->pay_state == Payment::STATUS_FAILED) {
                                return Yii::t('payment', 'State Failed');
                            } else if ($model->pay_state == Payment::STATUS_REFUND) {
                                return Yii::t('payment', 'State Refund');
                            } else if ($model->pay_state == Payment::STATUS_CLOSED) {
                                return Yii::t('payment', 'State Close');
                            } else if ($model->pay_state == Payment::STATUS_REVOKED) {
                                return Yii::t('payment', 'State Revoked');
                            } else if ($model->pay_state == Payment::STATUS_ERROR) {
                                return Yii::t('payment', 'State Error');
                            }
                        },
                        'format' => 'raw'
                    ],
                    'ip',
                    'note:ntext',
                    'created_at:datetime',
                    'updated_at:datetime',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => Yii::t('app', 'Operation'),
                        'template' => '{view} {update} {delete}',
                        //'buttons' => [
                        //    'update' => function ($url, $model, $key) {
                        //        return $model->status === 'editable' ? Html::a('Update', $url) : '';
                        //    },
                        //],
                    ],
                ],
            ]); ?>
            <?php Jarvis::end(); ?>
            <?php Pjax::end(); ?>
        </article>
    </div>
</section>

<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
?>
<div class="wrap">

    <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#" onclick="NetYog.msgBoardDisabled()"><i class="fa fa-plus-circle"></i>
                    Add Board </a>
            </div>
            <div id="navbar-main" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown"
                           class="dropdown-toggle"><?= @Yii::$app->user->identity->username ?>
                            <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                            </li>
                            <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                            </li>
                            <li class="divider"></li>
                            <li><a href="/site/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                            </li>
                        </ul>
                        <!-- /.dropdown-user -->
                    </li>
                </ul>

            </div>
        </div>

    </nav>
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>


        <div class="container">
            <div class="row">

                <?php
                if (!empty($this->params['blist'])) {
                    foreach ($this->params['blist'] as $v) {
                        ?>
                        <a href=<?=Yii::$app->urlManager->createUrl("/board/".$v['id']) ?>>
                            <div class="col-xs-3 board-block">
                                <?= $v['name'] ?>
                                <div>
                                    <i>
                                        <?= $v['description'] ?>
                                    </i>
                                </div>
                            </div>
                        </a>

                    <?php
                    }
                } else {
                    echo "<h3>没有数据！</h3>";
                }

                ?>

            </div>
            <br/>

            <a data-toggle="modal" href="#mymodal" class=" btn btn-primary">添加</a>

            <div class="modal fade" id="mymodal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span
                                    aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title">新增信息版</h4>
                        </div>
                        <div class="modal-body">
                            <?php
                            $form = ActiveForm::begin(['action' => ['site/add'], 'method' => 'post', 'id' => $model->formName()]
                            ) ?>

                            <? echo $form->field($model, 'name', ['labelOptions' => ['label' => '标题']])->textInput(['placehoder' => '请输入标题']) ?>
                            <? echo $form->field($model, 'description', ['labelOptions' => ['label' => '内容']])->textInput(['placehoder' => '请输入内容']) ?>
                            <? echo $form->field($model, 'created_at', ['labelOptions' => ['label' => false]])->hiddenInput(['value' => Yii::$app->formatter->asDatetime('now', 'yyyy-MM-dd H:i:s')]) ?>
                            <? echo $form->field($model, 'updated_at', ['labelOptions' => ['label' => false]])->hiddenInput(['value' => Yii::$app->formatter->asDatetime('now', 'yyyy-MM-dd H:i:s')]) ?>
                            <? echo $form->field($model, 'userid', ['labelOptions' => ['label' => false]])->hiddenInput(['value' => $uid]) ?>

                        </div>
                        <div class="modal-footer">
                            <? echo Html::submitButton('保存', ['class' => 'btn btn-default', 'name' => 'submit-button', 'onClick' => 'this.disabled=true;this.form.submit()']) ?>
                            <? echo Html::resetButton('重置', ['class' => 'btn btn-primary', 'name' => 'submit-button']) ?>

                            <?php ActiveForm::end(); ?>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>
</div>




<?php //JsBlock::begin() ?>
<!--<script>-->
<!--    $(function () {-->
<!--        jQuery('form#{$model->formName()}').on('beforeSubmit', function (e) {-->
<!--            var $form = $(this);-->
<!--            $.ajax({-->
<!--                url: $form.attr('action'),-->
<!--                type: 'post',-->
<!--                data: $form.serialize(),-->
<!--                success: function (data) {-->
<!--                    // do something-->
<!--                }-->
<!--            });-->
<!--        }).on('submit', function (e) {-->
<!--            e.preventDefault();-->
<!--        });-->
<!--</script>-->
<?php //JsBlock::end() ?>



<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link href="/css/libs/font-awesome.min.css" rel="stylesheet"/>
    <link href="/css/libs/bootstrap.min.css" rel="stylesheet"/>

    <?php $this->head() ?>
    <link href="/css/app.css" rel="stylesheet"/>
</head>
<body>
<?php $this->beginBody() ?>


<?= $content ?>
<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
<!--<script src="/js/libs/jquery-1.11.1.min.js" type="text/javascript"></script>-->
<script src="/js/libs/jquery-ui.min.js" type="text/javascript"></script>
<script src="/js/libs/jquery.ui.touch-punch.min.js" type="text/javascript"></script>
<script src="/js/libs/bootstrap.min.js" type="text/javascript"></script>
<script src="/js/libs/knockout-3.2.0.js" type="text/javascript"></script>
<script src="/js/libs/knockout.mapping.js" type="text/javascript"></script>
<script src="/js/libs/jquery.jeditable.mini.js" type="text/javascript"></script>
<script src="/js/app.js" type="text/javascript"></script>
</body>
</html>
<?php $this->endPage() ?>

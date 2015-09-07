<?php

namespace backend\web;

use yii\web\AssetBundle;

/**
 * AuditAsset
 * @package backend\assets
 */
class AuditAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    /**
     * @inheritdoc
     */
    public $css = [
        'css/audit.css',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];



}
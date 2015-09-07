<?php

namespace backend\components\web;

use backend\components\Access;
use backend\web\AuditAsset;
use backend\Audit;
use Yii;
use yii\web\View;

/**
 * Base Controller
 * @package backend\components\web
 *
 * @property Audit $module
 * @property View  $view
 */
class Controller extends \yii\web\Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => Access::getAccessControlFilter()
        ];
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {

        AuditAsset::register($this->view);
        return parent::beforeAction($action);
    }

}

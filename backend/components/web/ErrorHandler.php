<?php
/**
 * Error handler version for web based modules
 */

namespace backend\components\web;

use backend\components\base\ErrorHandlerTrait;

/**
 * ErrorHandler
 * @package backend\components\web
 */
class ErrorHandler extends \yii\web\ErrorHandler
{
    use ErrorHandlerTrait;
}
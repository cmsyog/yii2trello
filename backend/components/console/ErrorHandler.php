<?php
/**
 * Console compatible error handler
 */

namespace backend\components\console;

use backend\components\base\ErrorHandlerTrait;

/**
 * ErrorHandler
 * @package backend\components\console
 */
class ErrorHandler extends \yii\console\ErrorHandler
{
    use ErrorHandlerTrait;
}
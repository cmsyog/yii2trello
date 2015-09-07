<?php

namespace backend\models;

use app\models\Lists;
use backend\components\db\ActiveRecord;
use backend\components\Helper;
use Yii;
use yii\db\ActiveQuery;

/**
 * Class AuditEntry
 * @package backend\models
 *
 * @property int               $id
 * @property string            $created
 * @property float             $duration
 * @property int               $user_id        0 means anonymous
 * @property string            $ip
 * @property string            $route
 * @property int               $memory_max
 * @property string            $request_method
 * @property string            $ajax
 *
 * @property AuditError[]      $linkedErrors
 * @property AuditJavascript[] $javascripts
 * @property AuditTrail[]      $trails
 * @property AuditMail[]       $mails
 * @property AuditData[]       $associatedPanels
 */
class Board extends ActiveRecord
{

    public $message;

    public function rules()
    {
        return [
            [['name','description'], 'required','message'=>'标题与内容不能为空'],
            [['name','description'], 'string',],
            [['open','board_visibility'],'integer'],
            [['created_at','updated_at'], 'date','format'=>'yyyy-MM-dd H:i:s'],
        ];
    }


    /**
     * @var bool
     */
    protected $autoSerialize = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%boards}}';
    }

    /**
     * @param bool $initialise
     * @return static
     */
    public static function create($initialise = true)
    {
        $entry = new static;
        if ($initialise)
            $entry->record();

        return $entry;
    }

    /**
     * Returns all linked AuditError instances
     * (Called `linkedErrors()` to avoid confusion with the `getErrors()` method)
     * @return ActiveQuery
     */
    public function getLinkedErrors()
    {
        return static::hasMany(AuditError::className(), ['entry_id' => 'id']);
    }

    /**
     * Returns all linked AuditTrail instances
     * @return ActiveQuery
     */
    public function getTrails()
    {
        return static::hasMany(AuditTrail::className(), ['entry_id' => 'id']);
    }

    /**
     * Returns all linked AuditMail instances
     * @return ActiveQuery
     */
    public function getBoards()
    {
        return $this->hasOne(BoardMembers::className(), ['board_id' => 'id']);
    }

    /**
     * Returns all linked AuditJavascript instances
     * @return ActiveQuery
     */
    public function getJavascripts()
    {
        return static::hasMany(AuditJavascript::className(), ['entry_id' => 'id']);
    }

    /**
     * Returns all linked data records
     * @return ActiveQuery
     */
    public function getData()
    {
        return static::hasMany(AuditData::className(), ['entry_id' => 'id'])->indexBy('type');
    }

    /**
     * Writes a number of associated data records in one go.
     * @param      $batchData
     * @param bool $compact
     * @throws \yii\db\Exception
     */
    public function addBatchData($batchData, $compact = true)
    {
        $columns = ['entry_id', 'type', 'data'];
        $rows = [];
        foreach ($batchData as $type => $data) {
            $rows[] = [$this->id, $type, Helper::serialize($data, $compact)];
        }
        static::getDb()->createCommand()->batchInsert(AuditData::tableName(), $columns, $rows)->execute();
    }

    public function addData($type, $data, $compact = true)
    {
        $record = ['entry_id' => $this->id, 'type' => $type, 'data' => Helper::serialize($data, $compact)];
        static::getDb()->createCommand()->insert(AuditData::tableName(), $record)->execute();
    }

    /**
     * Records the current application state into the instance.
     */
    public function record()
    {
        $app = Yii::$app;
        $request = $app->request;

        $this->route = $app->requestedAction ? $app->requestedAction->uniqueId : null;
        if ($request instanceof \yii\web\Request) {
            $user = $app->user;
            $this->user_id        = $user->isGuest ? 0 : $user->id;
            $this->ip             = $request->userIP;
            $this->ajax           = $request->isAjax;
            $this->request_method = $request->method;
        } else if ($request instanceof \yii\console\Request) {
            $this->request_method = 'CLI';
        }

        $this->save(false);
    }

    /**
     * @return bool
     */
    public function finalize()
    {
        return $this->save(false, ['duration', 'memory_max']);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id'             => Yii::t('audit', 'Entry ID'),
            'created_at'        => Yii::t('audit', 'Created'),
            'updated_at'        => Yii::t('audit', 'Updated'),
            'ip'             => Yii::t('audit', 'IP'),
            'description'       => Yii::t('audit', 'Description'),
            'open'        => Yii::t('audit', 'Open'),
            'name'     => Yii::t('audit', 'Name'),
            'board_visibility' => Yii::t('audit', 'Board Visibility'),
        ];
    }

    public function getCardLists(){
        return $this->hasMany(Lists::className(),['board_id' => 'id']);
    }

    public function getBoardActivities(){
        return $this->hasMany(BoardActivities::className(),['board_id' => 'id']);
    }
}

<?php
/**
 * Base model for the audit classes containing some helper functions to auto serialize/unserialize the
 * raw data attributes.
 */

namespace backend\components\db;

use backend\Audit;
use backend\components\Helper;

/**
 * ActiveRecord
 * @package backend\models
 * @property string $created
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    /** @var bool                   If true, automatically pack and unpack the data attribute */
    protected $autoSerialize = true;
    /**
     * @var array
     */
    protected $serializeAttributes = ['data'];


    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($insert && $this->hasAttribute('created'))
            $this->created = date('Y-m-d H:i:s');

        if ($this->autoSerialize)
            foreach ($this->serializeAttributes as $attribute)
                if ($this->hasAttribute($attribute))
                    $this->$attribute = Helper::serialize($this->$attribute, false);

        return parent::beforeSave($insert);
    }

    /**
     * @param bool  $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->autoSerialize)
            foreach ($this->serializeAttributes as $attribute)
                if ($this->hasAttribute($attribute))
                    $this->$attribute = Helper::unserialize($this->$attribute);
    }

    /**
     *
     */
    public function afterFind()
    {
        parent::afterFind();

        if ($this->autoSerialize)
            foreach ($this->serializeAttributes as $attribute)
                if ($this->hasAttribute($attribute))
                    $this->$attribute = Helper::unserialize($this->$attribute);
    }
}
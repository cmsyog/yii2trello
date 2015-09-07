<?php

namespace backend\components\panels;

/**
 * DataStoragePanelTrait
 * @package backend\components\panels
 */
trait DataStoragePanelTrait
{
    use PanelTrait;

    /**
     * @inheritdoc
     */
    public function hasEntryData($entry)
    {
        $data = $entry->data;
        return isset($data[$this->id]);
    }
}
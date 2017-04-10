<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder\Business\Api\Converter\Data;

use FACTFinder\Data\Record;
use Generated\Shared\Transfer\FactFinderDataRecordTransfer;
use Spryker\Client\FactFinder\Business\Api\Converter\BaseConverter;
use Spryker\Shared\FactFinder\FactFinderConstants;

class RecordConverter extends BaseConverter
{

    /**
     * @var \FACTFinder\Data\Record
     */
    protected $record;

    /**
     * @param \FACTFinder\Data\Record $record
     * @return void
     */
    public function setRecord(Record $record)
    {
        $this->record = $record;
    }

    /**
     * @return \Generated\Shared\Transfer\FactFinderDataRecordTransfer
     */
    public function convert()
    {
        $factFinderDataRecordTransfer = new FactFinderDataRecordTransfer();
        $factFinderDataRecordTransfer->setId($this->record->getID());
        $factFinderDataRecordTransfer->setSimilarity($this->record->getSimilarity());
        $factFinderDataRecordTransfer->setPosition($this->record->getPosition());
        $factFinderDataRecordTransfer->setSeoPath($this->record->getSeoPath());
        $factFinderDataRecordTransfer->setKeywords($this->record->getKeywords());

        $this->convertFields($factFinderDataRecordTransfer);

        return $factFinderDataRecordTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FactFinderDataRecordTransfer $factFinderDataRecordTransfer
     * @return void
     */
    protected function convertFields(FactFinderDataRecordTransfer $factFinderDataRecordTransfer)
    {
        $fields = [];
        foreach (FactFinderConstants::ITEM_FIELDS as $itemFieldName) {
            $fields[$itemFieldName] = $this->record->getField($itemFieldName);
        }
        $factFinderDataRecordTransfer->setFields($fields);
    }

}

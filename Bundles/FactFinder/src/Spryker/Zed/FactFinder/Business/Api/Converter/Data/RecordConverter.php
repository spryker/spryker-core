<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api\Converter\Data;

use FACTFinder\Data\Record;
use Generated\Shared\Transfer\FactFinderDataRecordTransfer;
use Spryker\Zed\FactFinder\Business\Api\Converter\BaseConverter;

class RecordConverter extends BaseConverter
{

    /**
     * @var \FACTFinder\Data\Record
     */
    protected $record;

    /**
     * @param \FACTFinder\Data\Record $record
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
        //$factFinderDataRecordTransfer->setFields($this->record->getField());
        $factFinderDataRecordTransfer->setSimilarity($this->record->getSimilarity());
        $factFinderDataRecordTransfer->setPosition($this->record->getPosition());
        $factFinderDataRecordTransfer->setSeoPath($this->record->getSeoPath());
        $factFinderDataRecordTransfer->setKeywords($this->record->getKeywords());

        return $factFinderDataRecordTransfer;
    }

}

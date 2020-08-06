<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep;

use Orm\Zed\CmsSlot\Persistence\Map\SpyCmsSlotTableMap;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotQuery;
use Spryker\Shared\CmsSlotBlock\CmsSlotBlockConfig;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataSet\CmsSlotBlockDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CmsSlotBlockValidatorStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->validateCmsSlotBlockPosition($dataSet);
        $this->validateIdCmsSlot($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function validateCmsSlotBlockPosition(DataSetInterface $dataSet): void
    {
        $position = $dataSet[CmsSlotBlockDataSetInterface::COL_POSITION];

        if (!filter_var($position, FILTER_VALIDATE_INT)) {
            throw new InvalidDataException(
                sprintf('Failed to import CMS Slot Block relationship with position %s.', $position)
            );
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function validateIdCmsSlot(DataSetInterface $dataSet): void
    {
        $contentProviderType = SpyCmsSlotQuery::create()
            ->select([SpyCmsSlotTableMap::COL_CONTENT_PROVIDER_TYPE])
            ->findOneByIdCmsSlot($dataSet[CmsSlotBlockDataSetInterface::COL_SLOT_ID]);

        if ($contentProviderType !== CmsSlotBlockConfig::CMS_SLOT_CONTENT_PROVIDER_TYPE) {
            throw new InvalidDataException(
                sprintf(
                    'Failed to import CMS Slot Block relationship with content provider type "%s":',
                    $contentProviderType
                )
            );
        }
    }
}

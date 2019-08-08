<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotDataImport\Business\DataImportStep;

use Spryker\Zed\CmsSlotDataImport\Business\DataSet\CmsSlotDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CmsSlotMutatorDataStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->mutateIsActiveDataSetValue($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function mutateIsActiveDataSetValue(DataSetInterface $dataSet): void
    {
        $dataSet[CmsSlotDataSetInterface::CMS_SLOT_IS_ACTIVE] = filter_var(
            $dataSet[CmsSlotDataSetInterface::CMS_SLOT_IS_ACTIVE],
            FILTER_VALIDATE_BOOLEAN
        );
    }
}

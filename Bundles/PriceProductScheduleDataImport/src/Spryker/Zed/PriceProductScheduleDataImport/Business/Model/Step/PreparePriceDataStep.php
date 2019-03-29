<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step;

use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\DataSet\PriceProductScheduleDataSet;

class PreparePriceDataStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        if (empty($dataSet[PriceProductScheduleDataSet::KEY_PRICE_NET])) {
            $dataSet[PriceProductScheduleDataSet::KEY_PRICE_NET] = null;
        }

        if (empty($dataSet[PriceProductScheduleDataSet::KEY_PRICE_GROSS])) {
            $dataSet[PriceProductScheduleDataSet::KEY_PRICE_GROSS] = null;
        }

        if (!empty($dataSet[PriceProductScheduleDataSet::KEY_PRICE_NET])) {
            $dataSet[PriceProductScheduleDataSet::KEY_PRICE_NET] = (int)$dataSet[PriceProductScheduleDataSet::KEY_PRICE_NET];
        }

        if (!empty($dataSet[PriceProductScheduleDataSet::KEY_PRICE_GROSS])) {
            $dataSet[PriceProductScheduleDataSet::KEY_PRICE_GROSS] = (int)$dataSet[PriceProductScheduleDataSet::KEY_PRICE_GROSS];
        }
    }
}

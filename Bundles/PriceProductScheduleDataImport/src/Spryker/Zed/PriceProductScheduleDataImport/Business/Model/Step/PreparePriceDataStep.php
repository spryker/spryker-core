<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step;

use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\DataSet\PriceProductScheduleDataSetInterface;

class PreparePriceDataStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet[PriceProductScheduleDataSetInterface::KEY_PRICE_NET] =
            empty($dataSet[PriceProductScheduleDataSetInterface::KEY_PRICE_NET])
                ? null
                : (int)$dataSet[PriceProductScheduleDataSetInterface::KEY_PRICE_NET];

        $dataSet[PriceProductScheduleDataSetInterface::KEY_PRICE_GROSS] =
            empty($dataSet[PriceProductScheduleDataSetInterface::KEY_PRICE_GROSS])
                ? null
                : (int)$dataSet[PriceProductScheduleDataSetInterface::KEY_PRICE_GROSS];
    }
}

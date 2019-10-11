<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDataImport\Business\ShipmentPrice\Writer\Step;

use Orm\Zed\Currency\Persistence\SpyCurrencyQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShipmentDataImport\Business\ShipmentPrice\Writer\DataSet\ShipmentPriceDataSetInterface;

class CurrencyCodeToIdCurrencyStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $currencyCode = $dataSet[ShipmentPriceDataSetInterface::COL_CURRENCY];

        if (!$currencyCode) {
            throw new EntityNotFoundException(sprintf('Invalid currency ISO code: %s', $currencyCode));
        }

        $currencyEntity = SpyCurrencyQuery::create()
            ->filterByCode($currencyCode)
            ->findOne();

        if ($currencyEntity === null) {
            throw new EntityNotFoundException('Currency not found');
        }

        $dataSet[ShipmentPriceDataSetInterface::COL_ID_CURRENCY] = $currencyEntity->getIdCurrency();
    }
}

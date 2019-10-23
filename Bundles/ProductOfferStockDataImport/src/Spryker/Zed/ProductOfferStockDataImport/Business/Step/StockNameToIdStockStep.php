<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStockDataImport\Business\Step;

use Generated\Shared\Transfer\SpyStockEntityTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductOfferStockDataImport\Business\DataSet\ProductOfferStockDataSetInterface;

class StockNameToIdStockStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $stockName = $dataSet[ProductOfferStockDataSetInterface::PRODUCT_STOCK_NAME];

        if (!$stockName) {
            return;
        }

        $stock = new SpyStockEntityTransfer();

        $dataSet[ProductOfferStockDataSetInterface::FK_STOCK] = $stock->getIdStock();
    }
}

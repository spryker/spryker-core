<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStockDataImport\Business\Step;

use Orm\Zed\ProductOfferStock\Persistence\Base\SpyProductOfferStockQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductOfferStockDataImport\Business\DataSet\ProductOfferStockDataSetInterface;

class ProductOfferStockWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productOfferStockEntity = SpyProductOfferStockQuery::create()
            ->filterByFkProductOffer($dataSet[ProductOfferStockDataSetInterface::FK_PRODUCT_OFFER])
            ->filterByFkStock($dataSet[ProductOfferStockDataSetInterface::FK_STOCK])
            ->findOneOrCreate();

        $productOfferStockEntity
            ->setQuantity($dataSet[ProductOfferStockDataSetInterface::QUANTITY])
            ->save();
    }
}

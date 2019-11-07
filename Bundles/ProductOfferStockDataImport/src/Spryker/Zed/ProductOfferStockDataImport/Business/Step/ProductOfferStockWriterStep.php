<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStockDataImport\Business\Step;

use Orm\Zed\ProductOfferStock\Persistence\Base\SpyProductOfferStockQuery;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductOfferStockDataImport\Business\DataSet\ProductOfferStockDataSetInterface;

class ProductOfferStockWriterStep implements DataImportStepInterface
{
    protected const REQUIRED_DATA_SET_KEYS = [
        ProductOfferStockDataSetInterface::FK_STOCK,
        ProductOfferStockDataSetInterface::FK_PRODUCT_OFFER,
        ProductOfferStockDataSetInterface::QUANTITY,
    ];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->validateDataSet($dataSet);

        $productOfferStockEntity = SpyProductOfferStockQuery::create()
            ->filterByFkProductOffer($dataSet[ProductOfferStockDataSetInterface::FK_PRODUCT_OFFER])
            ->filterByFkStock($dataSet[ProductOfferStockDataSetInterface::FK_STOCK])
            ->findOneOrCreate();

        $productOfferStockEntity
            ->setQuantity($dataSet[ProductOfferStockDataSetInterface::QUANTITY])
            ->save();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function validateDataSet(DataSetInterface $dataSet): void
    {
        foreach (static::REQUIRED_DATA_SET_KEYS as $requiredDataSetKey) {
            if (!isset($dataSet[$requiredDataSetKey])) {
                throw new InvalidDataException(sprintf('"%s" is required.', $requiredDataSetKey));
            }
        }
    }
}

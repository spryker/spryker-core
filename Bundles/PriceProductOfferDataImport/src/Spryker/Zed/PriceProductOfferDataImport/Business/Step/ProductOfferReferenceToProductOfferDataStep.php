<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferDataImport\Business\Step;

use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductOfferDataImport\Business\DataSet\PriceProductOfferDataSetInterface;

class ProductOfferReferenceToProductOfferDataStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $productOfferDataCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productOfferReferenceKey = $dataSet[PriceProductOfferDataSetInterface::PRODUCT_OFFER_REFERENCE];

        if (!isset($this->productOfferDataCache[$productOfferReferenceKey])) {
            $productOfferQuery = SpyProductOfferQuery::create();
            $productOfferQuery->select([SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER, SpyProductOfferTableMap::COL_CONCRETE_SKU]);

            $productOfferEntity = $productOfferQuery->findOneByProductOfferReference($productOfferReferenceKey);
            if (!$productOfferEntity) {
                throw new EntityNotFoundException(sprintf('Could not find product offer by product offer reference "%s"', $productOfferReferenceKey));
            }

            $this->productOfferDataCache[$productOfferReferenceKey] = $productOfferEntity;
        }

        $dataSet[PriceProductOfferDataSetInterface::FK_PRODUCT_OFFER] = $this->productOfferDataCache[$productOfferReferenceKey][SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER];
        $dataSet[PriceProductOfferDataSetInterface::CONCRETE_SKU] = $this->productOfferDataCache[$productOfferReferenceKey][SpyProductOfferTableMap::COL_CONCRETE_SKU];
    }
}

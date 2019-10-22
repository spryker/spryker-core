<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductConcreteProductOffersStorageCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductConcreteProductOffersStorageTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStoragePersistenceFactory getFactory()
 */
class MerchantProductOfferStorageRepository extends AbstractRepository implements MerchantProductOfferStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteProductOffersStorageCriteriaFilterTransfer $productConcreteProductOffersStorageCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteProductOffersStorageTransfer[]
     */
    public function findProductConcreteProductOffersStorage(ProductConcreteProductOffersStorageCriteriaFilterTransfer $productConcreteProductOffersStorageCriteriaFilterTransfer): array
    {
        $productConcreteProductOffersStorageTransfers = [];
        $productConcreteProductOffersStorageQuery = $this->getFactory()->createProductConcreteProductOffersStorageQuery();
        $productConcreteProductOffersStorageIds = $productConcreteProductOffersStorageCriteriaFilterTransfer->getProductConcreteProductOffersStorageIds();
        if ($productConcreteProductOffersStorageIds) {
            $productConcreteProductOffersStorageQuery->filterByIdProductConcreteProductOffersStorage_In($productConcreteProductOffersStorageIds);
        }

        if ($productConcreteProductOffersStorageCriteriaFilterTransfer->getFilter()) {
            $productConcreteProductOffersStorageQuery = $this->applyFilter($productConcreteProductOffersStorageQuery, $productConcreteProductOffersStorageCriteriaFilterTransfer->getFilter());
        }
        $productConcreteProductOffersStorageEntityCollection = $productConcreteProductOffersStorageQuery->find();

        foreach ($productConcreteProductOffersStorageEntityCollection as $productConcreteProductOffersStorageEntity) {
            $productConcreteProductOffersStorageTransfers[] = $this->getFactory()
                ->createProductConcreteProductOffersStorageMapper()
                ->mapProductConcreteProductOffersStorageEntityToProductConcreteProductOffersStorageTransfer(
                    $productConcreteProductOffersStorageEntity,
                    (new ProductConcreteProductOffersStorageTransfer())
                );
        }

        return $productConcreteProductOffersStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaFilterTransfer $productOfferStorageCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer[]
     */
    public function findProductOfferStorage(ProductOfferStorageCriteriaFilterTransfer $productOfferStorageCriteriaFilterTransfer): array
    {
        $productOfferStorageTransfers = [];
        $productOfferStorageQuery = $this->getFactory()->createProductOfferStorageQuery();
        $productOfferStorageIds = $productOfferStorageCriteriaFilterTransfer->getProductOfferStorageIds();
        if ($productOfferStorageIds) {
            $productOfferStorageQuery->filterByIdProductOfferStorage_In($productOfferStorageIds);
        }
        if ($productOfferStorageCriteriaFilterTransfer->getFilter()) {
            $productOfferStorageQuery = $this->applyFilter($productOfferStorageQuery, $productOfferStorageCriteriaFilterTransfer->getFilter());
        }
        $productOfferStorageEntityCollection = $productOfferStorageQuery->find();

        foreach ($productOfferStorageEntityCollection as $productOfferStorageEntity) {
            $productOfferStorageTransfers[] = $this->getFactory()
                ->createProductOfferStorageMapper()
                ->mapProductOfferStorageEntityToProductOfferStorageTransfer(
                    $productOfferStorageEntity,
                    (new ProductOfferStorageTransfer())
                );
        }

        return $productOfferStorageTransfers;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\FilterTransfer|null $filterTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyFilter(ModelCriteria $query, ?FilterTransfer $filterTransfer): ModelCriteria
    {
        $query->setOffset($filterTransfer->getOffset());
        $query->setLimit($filterTransfer->getLimit());

        return $query;
    }
}

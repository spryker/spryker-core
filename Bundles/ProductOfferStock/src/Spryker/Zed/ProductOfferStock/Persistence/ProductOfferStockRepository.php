<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStockQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductOfferStock\Persistence\ProductOfferStockPersistenceFactory getFactory()
 */
class ProductOfferStockRepository extends AbstractRepository implements ProductOfferStockRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockTransfer|null
     */
    public function findOne(ProductOfferStockRequestTransfer $productOfferStockRequestTransfer): ?ProductOfferStockTransfer
    {
        $productOfferStockQuery = $this->getFactory()
            ->getProductOfferStockPropelQuery()
            ->joinWithSpyProductOffer();
        $productOfferStockEntity = $this->applyFilters(
            $productOfferStockQuery,
            $productOfferStockRequestTransfer,
        )->findOne();

        if (!$productOfferStockEntity) {
            return null;
        }

        return $this->getFactory()
            ->createProductOfferStockMapper()
            ->mapProductOfferStockEntityToProductOfferStockTransfer(
                $productOfferStockEntity,
                new ProductOfferStockTransfer(),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductOfferStockTransfer>
     */
    public function find(ProductOfferStockRequestTransfer $productOfferStockRequestTransfer): ArrayObject
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock> $productOfferStockEntities */
        $productOfferStockEntities = $this->applyFilters(
            $this->getFactory()->getProductOfferStockPropelQuery(),
            $productOfferStockRequestTransfer,
        )->find();

        return $this->getFactory()
            ->createProductOfferStockMapper()
            ->mapProductOfferStockEntityCollectionToProductOfferStockTransfers(
                $productOfferStockEntities,
                new ArrayObject(),
            );
    }

    /**
     * @module Stock
     * @module Store
     *
     * @param \Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStockQuery $productOfferStockQuery
     * @param \Generated\Shared\Transfer\ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
     *
     * @return \Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStockQuery
     */
    protected function applyFilters(
        SpyProductOfferStockQuery $productOfferStockQuery,
        ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
    ): SpyProductOfferStockQuery {
        if ($productOfferStockRequestTransfer->getProductOfferReference() !== null) {
            $productOfferStockQuery
                ->useSpyProductOfferQuery()
                    ->filterByProductOfferReference($productOfferStockRequestTransfer->getProductOfferReference())
                ->endUse();
        }

        if ($productOfferStockRequestTransfer->getStore() && $productOfferStockRequestTransfer->getStoreOrFail()->getName()) {
            $productOfferStockQuery
                ->useStockQuery()
                    ->useStockStoreQuery()
                        ->useStoreQuery()
                            ->filterByName($productOfferStockRequestTransfer->getStoreOrFail()->getNameOrFail())
                        ->endUse()
                    ->endUse()
                ->endUse();
        }

        if ($productOfferStockRequestTransfer->getIsStockActive() !== null) {
            $productOfferStockQuery
                ->useStockQuery()
                    ->filterByIsActive($productOfferStockRequestTransfer->getIsStockActiveOrFail())
                ->endUse();
        }

        return $productOfferStockQuery;
    }
}

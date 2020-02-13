<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\MerchantProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOffer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantProductOffer\Persistence\MerchantProductOfferPersistenceFactory getFactory()
 */
class MerchantProductOfferRepository extends AbstractRepository implements MerchantProductOfferRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProductOfferCriteriaFilterTransfer $merchantProductOfferCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOfferCollection(MerchantProductOfferCriteriaFilterTransfer $merchantProductOfferCriteriaFilterTransfer): ProductOfferCollectionTransfer
    {
        $productOfferCollectionTransfer = new ProductOfferCollectionTransfer();
        $productOfferQuery = $this->getFactory()->getProductOfferPropelQuery();

        $productOfferQuery = $this->applyFilters($productOfferQuery, $merchantProductOfferCriteriaFilterTransfer);

        $productOfferEntities = $this->getPaginatedCollection($productOfferQuery, $productOfferCriteriaFilter->getPagination());

        foreach ($productOfferEntities as $productOfferEntity) {
            $productOfferTransfer = $this->getFactory()
                ->createProductOfferMapper()
                ->mapProductOfferEntityToProductOfferTransfer($productOfferEntity, (new ProductOfferTransfer()));

            $productOfferTransfer->setStores($this->getStoresByProductOfferEntity($productOfferEntity));

            $productOfferCollectionTransfer->addProductOffer($productOfferTransfer);
        }

        return $productOfferCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOffer $spyProductOfferEntity
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]|\ArrayObject
     */
    protected function getStoresByProductOfferEntity(SpyProductOffer $spyProductOfferEntity): ArrayObject
    {
        $storeTransfers = [];
        foreach ($spyProductOfferEntity->getSpyStores() as $storeEntity) {
            $storeTransfers[] = $this->getFactory()->createProductOfferMapper()->mapStoreEntityToStoreTransfer(
                $storeEntity,
                new StoreTransfer()
            );
        }

        return new ArrayObject($storeTransfers);
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     * @param \Generated\Shared\Transfer\MerchantProductOfferCriteriaFilterTransfer $merchantProductOfferCriteriaFilterTransfer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function applyFilters(
        SpyProductOfferQuery $productOfferQuery,
        MerchantProductOfferCriteriaFilterTransfer $merchantProductOfferCriteriaFilterTransfer
    ): SpyProductOfferQuery {
        if ($merchantProductOfferCriteriaFilterTransfer->getSkus()) {
            $productOfferQuery->filterByConcreteSku_In($merchantProductOfferCriteriaFilterTransfer->getSkus());
        }

        if ($merchantProductOfferCriteriaFilterTransfer->getMerchantReference()) {
            $productOfferQuery
                ->useSpyMerchantQuery()
                    ->filterByMerchantReference($merchantProductOfferCriteriaFilterTransfer->getMerchantReference())
                ->endUse();
        }

        return $productOfferQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return mixed|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\Collection|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getPaginatedCollection(ModelCriteria $query, ?PaginationTransfer $paginationTransfer = null)
    {
        if ($paginationTransfer !== null) {
            $page = $paginationTransfer
                ->requirePage()
                ->getPage();

            $maxPerPage = $paginationTransfer
                ->requireMaxPerPage()
                ->getMaxPerPage();

            $paginationModel = $query->paginate($page, $maxPerPage);

            $paginationTransfer->setNbResults($paginationModel->getNbResults());
            $paginationTransfer->setFirstIndex($paginationModel->getFirstIndex());
            $paginationTransfer->setLastIndex($paginationModel->getLastIndex());
            $paginationTransfer->setFirstPage($paginationModel->getFirstPage());
            $paginationTransfer->setLastPage($paginationModel->getLastPage());
            $paginationTransfer->setNextPage($paginationModel->getNextPage());
            $paginationTransfer->setPreviousPage($paginationModel->getPreviousPage());

            return $paginationModel->getResults();
        }

        return $query->find();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductOffer\Persistence\ProductOfferPersistenceFactory getFactory()
 */
class ProductOfferRepository extends AbstractRepository implements ProductOfferRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function find(ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter): ProductOfferCollectionTransfer
    {
        $propelProductOfferMapper = $this->getFactory()->createPropelProductOfferMapper();
        $productOfferCollectionTransfer = new ProductOfferCollectionTransfer();
        $productOfferQuery = $this->getFactory()->createProductOfferPropelQuery();

        $productOfferQuery = $this->applyFilters($productOfferQuery, $productOfferCriteriaFilter);

        $productOfferEntities = $this->getPaginatedCollection($productOfferQuery, $productOfferCriteriaFilter->getPagination());

        foreach ($productOfferEntities as $productOfferEntity) {
            $productOfferTransfer = $propelProductOfferMapper->mapProductOfferEntityToProductOfferTransfer(
                $productOfferEntity,
                new ProductOfferTransfer()
            );
            $productOfferTransfer->setStores(new ArrayObject(
                $propelProductOfferMapper->mapProductOfferStoreEntitiesToStoreTransfers($productOfferEntity->getSpyProductOfferStores())
            ));

            $productOfferCollectionTransfer->addProductOffer($productOfferTransfer);
        }

        return $productOfferCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function findOne(ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter): ?ProductOfferTransfer
    {
        $productOfferQuery = $this->getFactory()->createProductOfferPropelQuery();
        $productOfferQuery = $this->applyFilters($productOfferQuery, $productOfferCriteriaFilter);

        $productOfferEntity = $productOfferQuery->findOne();

        if (!$productOfferEntity) {
            return null;
        }

        $propelProductOfferMapper = $this->getFactory()->createPropelProductOfferMapper();
        $productOfferTransfer = $propelProductOfferMapper->mapProductOfferEntityToProductOfferTransfer(
            $productOfferEntity,
            new ProductOfferTransfer()
        );
        $productOfferTransfer->setStores(new ArrayObject(
            $propelProductOfferMapper->mapProductOfferStoreEntitiesToStoreTransfers($productOfferEntity->getSpyProductOfferStores())
        ));

        return $productOfferTransfer;
    }

    /**
     * @return int
     */
    public function getMaxIdProductOffer(): int
    {
        $idProductOffer = $this->getFactory()->createProductOfferPropelQuery()
            ->orderByIdProductOffer(Criteria::DESC)
            ->select(SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER)
            ->findOne();

        return $idProductOffer ?: 0;
    }

    /**
     * @param int $idProductOffer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getProductOfferStores(int $idProductOffer): array
    {
        $productOfferStoreEntities = $this->getFactory()
            ->createProductOfferStoreQuery()
            ->filterByFkProductOffer($idProductOffer)
            ->find();

        return $this->getFactory()
            ->createPropelProductOfferMapper()
            ->mapProductOfferStoreEntitiesToStoreTransfers($productOfferStoreEntities);
    }

    /**
     * @param string $productOfferReference
     *
     * @return bool
     */
    public function hasProductOfferReference(string $productOfferReference): bool
    {
        return $this->getFactory()
            ->createProductOfferPropelQuery()
            ->filterByProductOfferReference($productOfferReference)
            ->exists();
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function applyFilters(
        SpyProductOfferQuery $productOfferQuery,
        ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter
    ): SpyProductOfferQuery {
        if ($productOfferCriteriaFilter->getConcreteSku()) {
            $productOfferQuery->filterByConcreteSku($productOfferCriteriaFilter->getConcreteSku());
        }

        if ($productOfferCriteriaFilter->getProductOfferReference()) {
            $productOfferQuery->filterByProductOfferReference($productOfferCriteriaFilter->getProductOfferReference());
        }

        if ($productOfferCriteriaFilter->getIdProductOffer()) {
            $productOfferQuery->filterByIdProductOffer($productOfferCriteriaFilter->getIdProductOffer());
        }

        if ($productOfferCriteriaFilter->getProductOfferIds()) {
            $productOfferQuery->filterByIdProductOffer_In($productOfferCriteriaFilter->getProductOfferIds());
        }

        if ($productOfferCriteriaFilter->getConcreteSkus()) {
            $productOfferQuery->filterByConcreteSku_In($productOfferCriteriaFilter->getConcreteSkus());
        }

        if ($productOfferCriteriaFilter->getProductOfferReferences()) {
            $productOfferQuery->filterByProductOfferReference_In($productOfferCriteriaFilter->getProductOfferReferences());
        }

        if ($productOfferCriteriaFilter->getIsActive() !== null) {
            $productOfferQuery->filterByIsActive($productOfferCriteriaFilter->getIsActive());
        }

        if ($productOfferCriteriaFilter->getApprovalStatuses()) {
            $productOfferQuery->filterByApprovalStatus_In($productOfferCriteriaFilter->getApprovalStatuses());
        }

        if ($productOfferCriteriaFilter->getIdStore()) {
            $productOfferQuery->useSpyProductOfferStoreQuery()
                ->filterByFkStore($productOfferCriteriaFilter->getIdStore())
            ->endUse();
        }

        if ($productOfferCriteriaFilter->getIsActiveConcreteProduct() !== null) {
            $productOfferQuery->addJoin(
                SpyProductOfferTableMap::COL_CONCRETE_SKU,
                SpyProductTableMap::COL_SKU,
                Criteria::INNER_JOIN
            );
            $productOfferQuery->where(SpyProductTableMap::COL_IS_ACTIVE, $productOfferCriteriaFilter->getIsActiveConcreteProduct());
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

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
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
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaTransfer $productOfferCriteria
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function get(ProductOfferCriteriaTransfer $productOfferCriteria): ProductOfferCollectionTransfer
    {
        $productOfferMapper = $this->getFactory()->createProductOfferMapper();
        $productOfferCollectionTransfer = new ProductOfferCollectionTransfer();
        $productOfferQuery = $this->getFactory()->createProductOfferPropelQuery();

        $productOfferQuery = $this->applyFilters($productOfferQuery, $productOfferCriteria);

        $productOfferEntities = $this->getPaginatedCollection($productOfferQuery, $productOfferCriteria->getPagination());

        foreach ($productOfferEntities as $productOfferEntity) {
            $productOfferTransfer = $productOfferMapper->mapProductOfferEntityToProductOfferTransfer(
                $productOfferEntity,
                new ProductOfferTransfer()
            );
            $productOfferTransfer->setStores(new ArrayObject(
                $productOfferMapper->mapProductOfferStoreEntitiesToStoreTransfers($productOfferEntity->getSpyProductOfferStores())
            ));

            $productOfferCollectionTransfer->addProductOffer($productOfferTransfer);
        }

        return $productOfferCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaTransfer $productOfferCriteria
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function findOne(ProductOfferCriteriaTransfer $productOfferCriteria): ?ProductOfferTransfer
    {
        $productOfferQuery = $this->getFactory()->createProductOfferPropelQuery();
        $productOfferQuery = $this->applyFilters($productOfferQuery, $productOfferCriteria);

        $productOfferEntity = $productOfferQuery->findOne();

        if (!$productOfferEntity) {
            return null;
        }

        $productOfferMapper = $this->getFactory()->createProductOfferMapper();
        $productOfferTransfer = $productOfferMapper->mapProductOfferEntityToProductOfferTransfer(
            $productOfferEntity,
            new ProductOfferTransfer()
        );
        $productOfferTransfer->setStores(new ArrayObject(
            $productOfferMapper->mapProductOfferStoreEntitiesToStoreTransfers($productOfferEntity->getSpyProductOfferStores())
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
            ->createProductOfferMapper()
            ->mapProductOfferStoreEntitiesToStoreTransfers($productOfferStoreEntities);
    }

    /**
     * @param string $productOfferReference
     *
     * @return bool
     */
    public function isProductOfferReferenceUsed(string $productOfferReference): bool
    {
        return $this->getFactory()
            ->createProductOfferPropelQuery()
            ->filterByProductOfferReference($productOfferReference)
            ->exists();
    }

    /**
     * @module Pro
     *
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaTransfer $productOfferCriteria
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function applyFilters(
        SpyProductOfferQuery $productOfferQuery,
        ProductOfferCriteriaTransfer $productOfferCriteria
    ): SpyProductOfferQuery {
        if ($productOfferCriteria->getConcreteSku()) {
            $productOfferQuery->filterByConcreteSku($productOfferCriteria->getConcreteSku());
        }

        if ($productOfferCriteria->getProductOfferReference()) {
            $productOfferQuery->filterByProductOfferReference($productOfferCriteria->getProductOfferReference());
        }

        if ($productOfferCriteria->getIdProductOffer()) {
            $productOfferQuery->filterByIdProductOffer($productOfferCriteria->getIdProductOffer());
        }

        if ($productOfferCriteria->getProductOfferIds()) {
            $productOfferQuery->filterByIdProductOffer_In($productOfferCriteria->getProductOfferIds());
        }

        if ($productOfferCriteria->getConcreteSkus()) {
            $productOfferQuery->filterByConcreteSku_In($productOfferCriteria->getConcreteSkus());
        }

        if ($productOfferCriteria->getProductOfferReferences()) {
            $productOfferQuery->filterByProductOfferReference_In($productOfferCriteria->getProductOfferReferences());
        }

        if ($productOfferCriteria->getIsActive() !== null) {
            $productOfferQuery->filterByIsActive($productOfferCriteria->getIsActive());
        }

        if ($productOfferCriteria->getApprovalStatuses()) {
            $productOfferQuery->filterByApprovalStatus_In($productOfferCriteria->getApprovalStatuses());
        }

        if ($productOfferCriteria->getIdStore()) {
            $productOfferQuery->useSpyProductOfferStoreQuery()
                ->filterByFkStore($productOfferCriteria->getIdStore())
            ->endUse();
        }

        if ($productOfferCriteria->getIsActiveConcreteProduct() !== null) {
            $productOfferQuery->addJoin(
                SpyProductOfferTableMap::COL_CONCRETE_SKU,
                SpyProductTableMap::COL_SKU,
                Criteria::INNER_JOIN
            );
            $productOfferQuery->where(SpyProductTableMap::COL_IS_ACTIVE, $productOfferCriteria->getIsActiveConcreteProduct());
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

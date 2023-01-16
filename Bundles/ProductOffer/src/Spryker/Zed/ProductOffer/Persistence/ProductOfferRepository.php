<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
     * @var string
     */
    protected const ID_PRODUCT_CONCRETE = 'idProductConcrete';

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

        $productOfferQuery = $this->expandProductOfferQueryWithIdProductConcrete($productOfferQuery);
        $productOfferQuery = $this->applyFilters($productOfferQuery, $productOfferCriteria);

        $productOfferEntities = $this->getPaginatedCollection($productOfferQuery, $productOfferCriteria->getPagination());

        $productOfferIds = [];

        foreach ($productOfferEntities as $productOfferEntity) {
            $idProductConcrete = $productOfferEntity->getVirtualColumn(static::ID_PRODUCT_CONCRETE);
            $productOfferEntity->setVirtualColumn(static::ID_PRODUCT_CONCRETE, (int)$idProductConcrete);

            $productOfferIds[] = $productOfferEntity->getIdProductOffer();
        }

        $productOfferStoreEntities = $this->getProductOfferStoreEntitiesGroupedByIdProductOffer($productOfferIds);

        foreach ($productOfferEntities as $productOfferEntity) {
            $productOfferTransfer = $productOfferMapper->mapProductOfferEntityToProductOfferTransfer(
                $productOfferEntity,
                new ProductOfferTransfer(),
            );
            $productOfferTransfer->setStores(new ArrayObject(
                $productOfferMapper->mapProductOfferStoreEntitiesToStoreTransfers(
                    $productOfferStoreEntities[$productOfferEntity->getIdProductOffer()] ?? [],
                ),
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
            new ProductOfferTransfer(),
        );
        $productOfferTransfer->setStores(new ArrayObject(
            $productOfferMapper->mapProductOfferStoreEntitiesToStoreTransfers($productOfferEntity->getSpyProductOfferStores()),
        ));

        return $productOfferTransfer;
    }

    /**
     * @param int $idProductOffer
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
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
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaTransfer $productOfferCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOfferCollection(ProductOfferCriteriaTransfer $productOfferCriteriaTransfer): ProductOfferCollectionTransfer
    {
        $productOfferCollectionTransfer = new ProductOfferCollectionTransfer();
        $productOfferQuery = $this->getFactory()->createProductOfferPropelQuery();

        $paginationTransfer = $productOfferCriteriaTransfer->getPagination();
        if ($paginationTransfer) {
            $productOfferQuery = $this->applyProductOfferPagination($productOfferQuery, $paginationTransfer);
            $productOfferCollectionTransfer->setPagination($paginationTransfer);
        }

        return $this->getFactory()
            ->createProductOfferMapper()
            ->mapProductOfferEntitiesToProductOfferCollectionTransfer(
                $productOfferQuery->find(),
                $productOfferCollectionTransfer,
            );
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function applyProductOfferPagination(
        SpyProductOfferQuery $productOfferQuery,
        PaginationTransfer $paginationTransfer
    ): SpyProductOfferQuery {
        $paginationTransfer->setNbResults($productOfferQuery->count());

        if ($paginationTransfer->getLimit() !== null && $paginationTransfer->getOffset() !== null) {
            return $productOfferQuery
                ->limit($paginationTransfer->getLimit())
                ->offset($paginationTransfer->getOffset());
        }

        return $productOfferQuery;
    }

    /**
     * @module Product
     *
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery<mixed> $productOfferQuery
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaTransfer $productOfferCriteria
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery<mixed>
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

        if ($productOfferCriteria->getMerchantReferences()) {
            $productOfferQuery->filterByMerchantReference_In($productOfferCriteria->getMerchantReferences());
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
                Criteria::INNER_JOIN,
            );
            $productOfferQuery->where(SpyProductTableMap::COL_IS_ACTIVE, $productOfferCriteria->getIsActiveConcreteProduct());
        }

        return $productOfferQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria<mixed> $query
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return \Propel\Runtime\Collection\Collection|\Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductOffer\Persistence\SpyProductOffer[]
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

    /**
     * @module Product
     *
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function expandProductOfferQueryWithIdProductConcrete(SpyProductOfferQuery $productOfferQuery): SpyProductOfferQuery
    {
        return $productOfferQuery
            ->addJoin(SpyProductOfferTableMap::COL_CONCRETE_SKU, SpyProductTableMap::COL_SKU, Criteria::INNER_JOIN)
            ->withColumn(SpyProductTableMap::COL_ID_PRODUCT, static::ID_PRODUCT_CONCRETE);
    }

    /**
     * @param array<int, int> $productOfferIds
     *
     * @return array<int, array<int, \Orm\Zed\ProductOffer\Persistence\SpyProductOfferStore>>
     */
    protected function getProductOfferStoreEntitiesGroupedByIdProductOffer(array $productOfferIds): array
    {
        $result = [];

        $productOfferStoreEntities = $this->getFactory()->createProductOfferStoreQuery()
            ->filterByFkProductOffer_In($productOfferIds)
            ->find();

        /** @var \Orm\Zed\ProductOffer\Persistence\SpyProductOfferStore $productOfferStoreEntity */
        foreach ($productOfferStoreEntities as $productOfferStoreEntity) {
            $result[$productOfferStoreEntity->getFkProductOffer()][] = $productOfferStoreEntity;
        }

        return $result;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCriteriaTransfer;
use Orm\Zed\ProductOfferServicePoint\Persistence\Base\SpyProductOfferServiceQuery;
use Orm\Zed\ProductOfferServicePoint\Persistence\Map\SpyProductOfferServiceTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Formatter\SimpleArrayFormatter;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointPersistenceFactory getFactory()
 */
class ProductOfferServicePointRepository extends AbstractRepository implements ProductOfferServicePointRepositoryInterface
{
    /**
     * @var string
     */
    protected const SERVICE_IDS_GROUPED = 'SERVICE_IDS_GROUPED';

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceCriteriaTransfer $productOfferServiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer
     */
    public function getProductOfferServiceCollection(
        ProductOfferServiceCriteriaTransfer $productOfferServiceCriteriaTransfer
    ): ProductOfferServiceCollectionTransfer {
        $productOfferServiceQuery = $this->getFactory()
            ->getProductOfferServiceQuery()
            ->select([
                SpyProductOfferServiceTableMap::COL_ID_PRODUCT_OFFER_SERVICE,
                SpyProductOfferServiceTableMap::COL_FK_PRODUCT_OFFER,
                SpyProductOfferServiceTableMap::COL_FK_SERVICE,
            ]);

        $productOfferServiceQuery = $this->applyProductOfferServiceFilters($productOfferServiceQuery, $productOfferServiceCriteriaTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortTransfers */
        $sortTransfers = $productOfferServiceCriteriaTransfer->getSortCollection();
        $productOfferServiceQuery = $this->applySorting($productOfferServiceQuery, $sortTransfers);
        $productOfferServiceCollectionTransfer = new ProductOfferServiceCollectionTransfer();
        $paginationTransfer = $productOfferServiceCriteriaTransfer->getPagination();

        if ($paginationTransfer) {
            $productOfferServiceQuery = $this->applyPagination($productOfferServiceQuery, $paginationTransfer);
            $productOfferServiceCollectionTransfer->setPagination($paginationTransfer);
        }

        $productOfferServicesData = $productOfferServiceQuery
            ->setFormatter(SimpleArrayFormatter::class)
            ->find();

        return $this->getFactory()
            ->createProductOfferServiceMapper()
            ->mapProductOfferServiceDataToProductOfferServiceCollectionTransfer($productOfferServicesData, $productOfferServiceCollectionTransfer);
    }

    /**
     * @param \Orm\Zed\ProductOfferServicePoint\Persistence\SpyProductOfferServiceQuery $productOfferServiceQuery
     * @param \Generated\Shared\Transfer\ProductOfferServiceCriteriaTransfer $productOfferServiceCriteriaTransfer
     *
     * @return \Orm\Zed\ProductOfferServicePoint\Persistence\SpyProductOfferServiceQuery
     */
    protected function applyProductOfferServiceFilters(
        SpyProductOfferServiceQuery $productOfferServiceQuery,
        ProductOfferServiceCriteriaTransfer $productOfferServiceCriteriaTransfer
    ): SpyProductOfferServiceQuery {
        $productOfferServiceConditionsTransfer = $productOfferServiceCriteriaTransfer->getProductOfferServiceConditions();

        if (!$productOfferServiceConditionsTransfer) {
            return $productOfferServiceQuery;
        }

        if ($productOfferServiceConditionsTransfer->getServiceIds() !== []) {
            $productOfferServiceQuery->filterByFkService_In($productOfferServiceConditionsTransfer->getServiceIds());
        }

        if ($productOfferServiceConditionsTransfer->getProductOfferIds() !== []) {
            $productOfferServiceQuery->filterByFkProductOffer_In($productOfferServiceConditionsTransfer->getProductOfferIds());
        }

        if ($productOfferServiceConditionsTransfer->getProductOfferServiceIds() !== []) {
            $productOfferServiceQuery->filterByIdProductOfferService_In($productOfferServiceConditionsTransfer->getProductOfferServiceIds());
        }

        if ($productOfferServiceConditionsTransfer->getGroupByIdProductOffer()) {
            $columnClause = sprintf('GROUP_CONCAT(%s)', SpyProductOfferServiceTableMap::COL_FK_SERVICE);

            $productOfferServiceQuery
                ->select(SpyProductOfferServiceTableMap::COL_FK_PRODUCT_OFFER)
                ->withColumn($columnClause, static::SERVICE_IDS_GROUPED)
                ->groupByFkProductOffer();
        }

        return $productOfferServiceQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $modelCriteria
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyPagination(
        ModelCriteria $modelCriteria,
        PaginationTransfer $paginationTransfer
    ): ModelCriteria {
        if ($paginationTransfer->getOffset() !== null && $paginationTransfer->getLimit() !== null) {
            $paginationTransfer->setNbResults($modelCriteria->count());

            return $modelCriteria
                ->offset($paginationTransfer->getOffsetOrFail())
                ->setLimit($paginationTransfer->getLimitOrFail());
        }

        if ($paginationTransfer->getPage() !== null && $paginationTransfer->getMaxPerPage()) {
            $propelModelPager = $modelCriteria->paginate(
                $paginationTransfer->getPageOrFail(),
                $paginationTransfer->getMaxPerPageOrFail(),
            );

            $paginationTransfer->setNbResults($propelModelPager->getNbResults())
                ->setFirstIndex($propelModelPager->getFirstIndex())
                ->setLastIndex($propelModelPager->getLastIndex())
                ->setFirstPage($propelModelPager->getFirstPage())
                ->setLastPage($propelModelPager->getLastPage())
                ->setNextPage($propelModelPager->getNextPage())
                ->setPreviousPage($propelModelPager->getPreviousPage());

            return $propelModelPager->getQuery();
        }

        return $modelCriteria;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $modelCriteria
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortTransfers
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applySorting(
        ModelCriteria $modelCriteria,
        ArrayObject $sortTransfers
    ): ModelCriteria {
        foreach ($sortTransfers as $sortTransfer) {
            $modelCriteria->orderBy(
                $sortTransfer->getFieldOrFail(),
                $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC,
            );
        }

        return $modelCriteria;
    }
}

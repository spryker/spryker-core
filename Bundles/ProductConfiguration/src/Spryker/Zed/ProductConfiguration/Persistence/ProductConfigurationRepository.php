<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Persistence;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductConfigurationCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationConditionsTransfer;
use Generated\Shared\Transfer\ProductConfigurationCriteriaTransfer;
use Orm\Zed\ProductConfiguration\Persistence\SpyProductConfigurationQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductConfiguration\Persistence\ProductConfigurationPersistenceFactory getFactory()
 */
class ProductConfigurationRepository extends AbstractRepository implements ProductConfigurationRepositoryInterface
{
    /**
     * @module Product
     *
     * @param \Generated\Shared\Transfer\ProductConfigurationCriteriaTransfer $productConfigurationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer
     */
    public function getProductConfigurationCollection(
        ProductConfigurationCriteriaTransfer $productConfigurationCriteriaTransfer
    ): ProductConfigurationCollectionTransfer {
        $productConfigurationCollectionTransfer = new ProductConfigurationCollectionTransfer();

        $productConfigurationQuery = $this->getFactory()
            ->createProductConfigurationQuery()
            ->leftJoinWithSpyProduct();

        $productConfigurationQuery = $this->applyProductConfigurationFilters($productConfigurationQuery, $productConfigurationCriteriaTransfer);
        $productConfigurationQuery = $this->applyProductConfigurationSorting($productConfigurationQuery, $productConfigurationCriteriaTransfer);

        $paginationTransfer = $productConfigurationCriteriaTransfer->getPagination();
        if ($paginationTransfer !== null) {
            $productConfigurationQuery = $this->applyProductConfigurationPagination($productConfigurationQuery, $paginationTransfer);
            $productConfigurationCollectionTransfer->setPagination($paginationTransfer);
        }

        $productConfigurationQuery->setFormatter(ModelCriteria::FORMAT_OBJECT);

        return $this->getFactory()
            ->createProductConfigurationMapper()
            ->mapProductConfigurationEntityCollectionToProductConfigurationCollectionTransfer(
                $productConfigurationQuery->find(),
                $productConfigurationCollectionTransfer,
            );
    }

    /**
     * @param \Orm\Zed\ProductConfiguration\Persistence\SpyProductConfigurationQuery $productConfigurationQuery
     * @param \Generated\Shared\Transfer\ProductConfigurationCriteriaTransfer $productConfigurationCriteriaTransfer
     *
     * @return \Orm\Zed\ProductConfiguration\Persistence\SpyProductConfigurationQuery
     */
    protected function applyProductConfigurationFilters(
        SpyProductConfigurationQuery $productConfigurationQuery,
        ProductConfigurationCriteriaTransfer $productConfigurationCriteriaTransfer
    ): SpyProductConfigurationQuery {
        $productConfigurationConditionsTransfer = $productConfigurationCriteriaTransfer->getProductConfigurationConditions();
        if ($productConfigurationConditionsTransfer === null) {
            return $productConfigurationQuery;
        }

        return $this->buildQueryByConditions($productConfigurationConditionsTransfer, $productConfigurationQuery);
    }

    /**
     * @param \Orm\Zed\ProductConfiguration\Persistence\SpyProductConfigurationQuery $productConfigurationQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyProductConfigurationPagination(
        SpyProductConfigurationQuery $productConfigurationQuery,
        PaginationTransfer $paginationTransfer
    ): ModelCriteria {
        if ($paginationTransfer->getOffset() !== null && $paginationTransfer->getLimit() !== null) {
            $productConfigurationQuery->offset($paginationTransfer->getOffsetOrFail())
                ->setLimit($paginationTransfer->getLimitOrFail());

            return $productConfigurationQuery;
        }

        $paginationModel = $productConfigurationQuery->paginate($paginationTransfer->getPageOrFail(), $paginationTransfer->getMaxPerPageOrFail());

        $paginationTransfer->setNbResults($paginationModel->getNbResults())
            ->setFirstIndex($paginationModel->getFirstIndex())
            ->setLastIndex($paginationModel->getLastIndex())
            ->setFirstPage($paginationModel->getFirstPage())
            ->setLastPage($paginationModel->getLastPage())
            ->setNextPage($paginationModel->getNextPage())
            ->setPreviousPage($paginationModel->getPreviousPage());

        return $paginationModel->getQuery();
    }

    /**
     * @param \Orm\Zed\ProductConfiguration\Persistence\SpyProductConfigurationQuery $productConfigurationQuery
     * @param \Generated\Shared\Transfer\ProductConfigurationCriteriaTransfer $productConfigurationCriteriaTransfer
     *
     * @return \Orm\Zed\ProductConfiguration\Persistence\SpyProductConfigurationQuery
     */
    protected function applyProductConfigurationSorting(
        SpyProductConfigurationQuery $productConfigurationQuery,
        ProductConfigurationCriteriaTransfer $productConfigurationCriteriaTransfer
    ): SpyProductConfigurationQuery {
        $sortCollection = $productConfigurationCriteriaTransfer->getSortCollection();
        foreach ($sortCollection as $sortTransfer) {
            $productConfigurationQuery->orderBy(
                $sortTransfer->getFieldOrFail(),
                $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC,
            );
        }

        return $productConfigurationQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationConditionsTransfer $productConfigurationConditionsTransfer
     * @param \Orm\Zed\ProductConfiguration\Persistence\SpyProductConfigurationQuery $productConfigurationQuery
     *
     * @return \Orm\Zed\ProductConfiguration\Persistence\SpyProductConfigurationQuery
     */
    protected function buildQueryByConditions(
        ProductConfigurationConditionsTransfer $productConfigurationConditionsTransfer,
        SpyProductConfigurationQuery $productConfigurationQuery
    ): SpyProductConfigurationQuery {
        if ($productConfigurationConditionsTransfer->getProductConfigurationIds()) {
            $productConfigurationQuery->filterByIdProductConfiguration(
                $productConfigurationConditionsTransfer->getProductConfigurationIds(),
                Criteria::IN,
            );
        }

        if ($productConfigurationConditionsTransfer->getUuids()) {
            $productConfigurationQuery->filterByUuid(
                $productConfigurationConditionsTransfer->getUuids(),
                Criteria::IN,
            );
        }

        if ($productConfigurationConditionsTransfer->getSkus()) {
            $productConfigurationQuery
                ->useSpyProductQuery()
                    ->filterBySku($productConfigurationConditionsTransfer->getSkus(), Criteria::IN)
                ->endUse();
        }

        return $productConfigurationQuery;
    }
}

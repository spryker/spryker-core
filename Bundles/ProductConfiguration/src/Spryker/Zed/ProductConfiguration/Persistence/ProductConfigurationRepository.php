<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductConfigurationCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationFilterTransfer;
use Orm\Zed\ProductConfiguration\Persistence\SpyProductConfigurationQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductConfiguration\Persistence\ProductConfigurationPersistenceFactory getFactory()
 */
class ProductConfigurationRepository extends AbstractRepository implements ProductConfigurationRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationFilterTransfer $productConfigurationFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer
     */
    public function getProductConfigurationCollection(
        ProductConfigurationFilterTransfer $productConfigurationFilterTransfer
    ): ProductConfigurationCollectionTransfer {
        $productConfigurationIds = $productConfigurationFilterTransfer->getProductConfigurationIds();

        $productConfigurationEntitiesQuery = $this->getFactory()->createProductConfigurationQuery();

        if ($productConfigurationIds) {
            $productConfigurationEntitiesQuery->filterByIdProductConfiguration_In($productConfigurationIds);
        }

        $productConfigurationEntitiesQuery = $this->setQueryFilters(
            $productConfigurationEntitiesQuery,
            $productConfigurationFilterTransfer->getFilter()
        );

        return $this->getFactory()->createProductConfigurationMapper()
            ->mapEntityCollectionToTransferCollection($productConfigurationEntitiesQuery->find());
    }

    /**
     * @param \Orm\Zed\ProductConfiguration\Persistence\SpyProductConfigurationQuery $productConfigurationEntitiesQuery
     * @param \Generated\Shared\Transfer\FilterTransfer|null $filterTransfer
     *
     * @return \Orm\Zed\ProductConfiguration\Persistence\SpyProductConfigurationQuery
     */
    protected function setQueryFilters(
        SpyProductConfigurationQuery $productConfigurationEntitiesQuery,
        ?FilterTransfer $filterTransfer
    ): SpyProductConfigurationQuery {
        if (!$filterTransfer) {
            return $productConfigurationEntitiesQuery;
        }

        if ($filterTransfer->getLimit()) {
            $productConfigurationEntitiesQuery->setLimit($filterTransfer->getLimit());
        }

        if ($filterTransfer->getOffset()) {
            $productConfigurationEntitiesQuery->setOffset($filterTransfer->getOffset());
        }

        if ($filterTransfer->getOrderBy()) {
            $productConfigurationEntitiesQuery->orderBy($filterTransfer->getOrderBy());
        }

        return $productConfigurationEntitiesQuery;
    }
}

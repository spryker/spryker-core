<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Persistence;

use Generated\Shared\Transfer\ProductConfigurationCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationFilterTransfer;
use Orm\Zed\ProductConfiguration\Persistence\SpyProductConfigurationQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
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
        $productConfigurationQuery = $this->getFactory()->createProductConfigurationQuery()->leftJoinWithSpyProduct();

        $productConfigurationQuery = $this->setProductConfigurationFilters(
            $productConfigurationQuery,
            $productConfigurationFilterTransfer
        );

        return $this->getFactory()->createProductConfigurationMapper()
            ->mapProductConfigurationEntityCollectionToProductConfigurationTransferCollection(
                new ProductConfigurationCollectionTransfer(),
                $productConfigurationQuery->find()
            );
    }

    /**
     * @param \Orm\Zed\ProductConfiguration\Persistence\SpyProductConfigurationQuery $productConfigurationQuery
     * @param \Generated\Shared\Transfer\ProductConfigurationFilterTransfer $productConfigurationFilterTransfer
     *
     * @return \Orm\Zed\ProductConfiguration\Persistence\SpyProductConfigurationQuery
     */
    protected function setProductConfigurationFilters(
        SpyProductConfigurationQuery $productConfigurationQuery,
        ProductConfigurationFilterTransfer $productConfigurationFilterTransfer
    ): SpyProductConfigurationQuery {
        $productConfigurationIds = $productConfigurationFilterTransfer->getProductConfigurationIds();

        if ($productConfigurationIds) {
            $productConfigurationQuery->filterByIdProductConfiguration_In($productConfigurationIds);
        }

        if ($productConfigurationFilterTransfer->getFilter()) {
            /** @var \Orm\Zed\ProductConfiguration\Persistence\SpyProductConfigurationQuery $productConfigurationQuery */
            $productConfigurationQuery = $this->buildQueryFromCriteria(
                $productConfigurationQuery,
                $productConfigurationFilterTransfer->getFilter()
            );

            $productConfigurationQuery->setFormatter(ModelCriteria::FORMAT_OBJECT);
        }

        return $productConfigurationQuery;
    }
}

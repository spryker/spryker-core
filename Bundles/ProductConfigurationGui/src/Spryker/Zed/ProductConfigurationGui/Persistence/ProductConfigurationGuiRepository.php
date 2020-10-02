<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationGui\Persistence;

use Generated\Shared\Transfer\ProductConfigurationAggregationTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductConfiguration\Persistence\Map\SpyProductConfigurationTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductConfigurationGui\Persistence\ProductConfigurationGuiPersistenceFactory getFactory()
 */
class ProductConfigurationGuiRepository extends AbstractRepository implements ProductConfigurationGuiRepositoryInterface
{
    /**
     * @module Product
     *
     * @param string $abstractProductSku
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationAggregationTransfer|null
     */
    public function findProductConfigurationAggregation(
        string $abstractProductSku
    ): ?ProductConfigurationAggregationTransfer {
        $productConfigurationAggregationData = $this->getFactory()->getProductAbstractPropelQuery()
            ->filterBySku($abstractProductSku)
            ->useSpyProductQuery()
               ->leftJoinSpyProductConfiguration()
            ->endUse()
            ->withColumn(
                sprintf('COUNT(%s)', SpyProductConfigurationTableMap::COL_ID_PRODUCT_CONFIGURATION),
                ProductConfigurationAggregationTransfer::PRODUCT_CONFIGURATION_COUNT
            )
            ->withColumn(
                sprintf('COUNT(%s)', SpyProductTableMap::COL_ID_PRODUCT),
                ProductConfigurationAggregationTransfer::PRODUCT_CONCRETE_COUNT
            )
            ->select([
                ProductConfigurationAggregationTransfer::PRODUCT_CONCRETE_COUNT,
                ProductConfigurationAggregationTransfer::PRODUCT_CONFIGURATION_COUNT,
            ])->findOne();

        if (empty($productConfigurationAggregationData)) {
            return null;
        }

        /** @var array $productConfigurationAggregationData */
        return (new ProductConfigurationAggregationTransfer())->fromArray($productConfigurationAggregationData);
    }
}

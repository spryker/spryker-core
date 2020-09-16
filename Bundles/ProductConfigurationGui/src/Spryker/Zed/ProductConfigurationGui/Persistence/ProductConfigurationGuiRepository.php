<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationGui\Persistence;

use Generated\Shared\Transfer\ProductConfigurationAggregationTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductConfigurationGui\Persistence\ProductConfigurationGuiPersistenceFactory getFactory()
 */
class ProductConfigurationGuiRepository extends AbstractRepository implements ProductConfigurationGuiRepositoryInterface
{
    /**
     * @uses \Orm\Zed\Product\Persistence\Map\SpyProductTableMap::COL_ID_PRODUCT
     */
    protected const COL_ID_PRODUCT = 'spy_product.id_product';

    /**
     * @uses \Orm\Zed\Product\Persistence\Map\SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT
     */
    protected const COL_FK_PRODUCT_ABSTRACT = 'spy_product.fk_product_abstract';

    /**
     * @uses \Orm\Zed\ConfigurableBundle\Persistence\Map\SpyProductConfigurationTableMap::COL_ID_PRODUCT_CONFIGURATION
     */
    protected const COL_ID_PRODUCT_CONFIGURATION = 'spy_product_configuration.id_product_configuration';

    /**
     * @param string $abstractProductSku
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationAggregationTransfer|null
     */
    public function findProductConfigurationAggregation(
        string $abstractProductSku
    ): ?ProductConfigurationAggregationTransfer {
        $productConfigurationAggregationData = $this->getFactory()->createProductConfigurationQuery()
            ->useSpyProductQuery()
                ->useSpyProductAbstractQuery()
                    ->filterBySku($abstractProductSku)
                ->endUse()
            ->endUse()
            ->withColumn(
                sprintf('COUNT(%s)', static::COL_ID_PRODUCT_CONFIGURATION),
                ProductConfigurationAggregationTransfer::PRODUCT_CONFIGURATION_COUNT
            )
            ->withColumn(
                sprintf('COUNT(%s)', static::COL_ID_PRODUCT),
                ProductConfigurationAggregationTransfer::PRODUCT_CONCRETE_COUNT
            )
            ->select([
                ProductConfigurationAggregationTransfer::PRODUCT_CONCRETE_COUNT,
                ProductConfigurationAggregationTransfer::PRODUCT_CONFIGURATION_COUNT,
            ])
            ->groupBy(static::COL_FK_PRODUCT_ABSTRACT)->findOne();

        if (empty($productConfigurationAggregationData)) {
            return null;
        }

        /** @var array $productConfigurationAggregationData */
        return (new ProductConfigurationAggregationTransfer())->fromArray($productConfigurationAggregationData);
    }
}

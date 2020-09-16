<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationGui\Communication\Expander;

use Spryker\Zed\ProductConfigurationGui\Persistence\ProductConfigurationGuiRepositoryInterface;

class ProductConfigurationTableDataExpander implements ProductConfigurationTableDataExpanderInterface
{
    /**
     * @uses \Spryker\Zed\ProductManagement\Communication\Table\ProductTable::COL_PRODUCT_TYPES
     */
    protected const COL_PRODUCT_TYPES = 'product_types';

    /**
     * @uses \Spryker\Zed\ProductManagement\Communication\Table\ProductTable::COL_SKU
     */
    protected const COL_SKU = 'sku';

    protected const CONFIGURABLE_PRODUCT_TYPE = 'Configurable Product';

    /**
     * @var \Spryker\Zed\ProductConfigurationGui\Persistence\ProductConfigurationGuiRepositoryInterface
     */
    protected $productConfigurationGuiRepository;

    /**
     * @param \Spryker\Zed\ProductConfigurationGui\Persistence\ProductConfigurationGuiRepositoryInterface $productConfigurationGuiRepository
     */
    public function __construct(ProductConfigurationGuiRepositoryInterface $productConfigurationGuiRepository)
    {
        $this->productConfigurationGuiRepository = $productConfigurationGuiRepository;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    public function expandProductItemWithProductConfigurationType(array $item): array
    {
        $productConfigurationAggregation = $this->productConfigurationGuiRepository
            ->findProductConfigurationAggregation($item[static::COL_SKU]);

        if (!$productConfigurationAggregation) {
            return $item;
        }

        if (
            $productConfigurationAggregation->getProductConcreteCount()
             === $productConfigurationAggregation->getProductConfigurationCount()
        ) {
            $item[static::COL_PRODUCT_TYPES] = static::CONFIGURABLE_PRODUCT_TYPE;

            return $item;
        }

        $item[static::COL_PRODUCT_TYPES] = sprintf(
            '%s, %s',
            $item[static::COL_PRODUCT_TYPES],
            static::CONFIGURABLE_PRODUCT_TYPE
        );

         return $item;
    }
}

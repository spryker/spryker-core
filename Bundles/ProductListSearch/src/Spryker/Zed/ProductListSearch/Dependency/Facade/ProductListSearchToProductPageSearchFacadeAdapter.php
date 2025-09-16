<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Dependency\Facade;

class ProductListSearchToProductPageSearchFacadeAdapter implements ProductListSearchToProductPageSearchFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface
     */
    protected $productPageSearchFacade;

    /**
     * @param \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface $productPageSearchFacade
     */
    public function __construct($productPageSearchFacade)
    {
        $this->productPageSearchFacade = $productPageSearchFacade;
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds): void
    {
        $this->productPageSearchFacade->publish($productAbstractIds);
    }

    /**
     * @param array<int, int> $productAbstractIdTimestampMap
     *
     * @return void
     */
    public function publishProductConcretes(array $productAbstractIdTimestampMap): void
    {
        if (!method_exists($this->productPageSearchFacade, 'publishWithTimestamp') === false) {
            $this->productPageSearchFacade->refresh(array_keys($productAbstractIdTimestampMap));
        }

        $this->productPageSearchFacade->publishProductConcretes($productAbstractIdTimestampMap);
    }

    /**
     * @param array<int, int> $productAbstractIdTimestampMap
     *
     * @return void
     */
    public function publishWithTimestamp(array $productAbstractIdTimestampMap): void
    {
        $this->productPageSearchFacade->publishWithTimestamp($productAbstractIdTimestampMap);
    }
}

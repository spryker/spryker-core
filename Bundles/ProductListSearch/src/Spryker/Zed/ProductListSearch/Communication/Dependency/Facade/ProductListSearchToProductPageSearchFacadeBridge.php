<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Communication\Dependency\Facade;

class ProductListSearchToProductPageSearchFacadeBridge implements ProductListSearchToProductPageSearchFacadeInterface
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
     * @param int[] $productAbstractIds
     * @param string[] $pageDataExpanderPluginNames
     *
     * @return void
     */
    public function refresh(array $productAbstractIds, array $pageDataExpanderPluginNames = []): void
    {
        $this->productPageSearchFacade->refresh($productAbstractIds, $pageDataExpanderPluginNames);
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds): void
    {
        $this->productPageSearchFacade->publish($productAbstractIds);
    }
}

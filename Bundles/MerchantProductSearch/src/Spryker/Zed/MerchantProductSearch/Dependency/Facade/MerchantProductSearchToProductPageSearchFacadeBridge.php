<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductSearch\Dependency\Facade;

class MerchantProductSearchToProductPageSearchFacadeBridge implements MerchantProductSearchToProductPageSearchFacadeInterface
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
     * @param array $pageDataExpanderPluginNames
     *
     * @return void
     */
    public function refresh(array $productAbstractIds, $pageDataExpanderPluginNames = [])
    {
        $this->productPageSearchFacade->refresh($productAbstractIds, $pageDataExpanderPluginNames);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleStorage\Dependency\Facade;

class ProductBundleStorageToProductBundleFacadeBridge implements ProductBundleStorageToProductBundleFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface
     */
    protected $productBundleFacade;

    /**
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface $productBundleFacade
     */
    public function __construct($productBundleFacade)
    {
        $this->productBundleFacade = $productBundleFacade;
    }
}

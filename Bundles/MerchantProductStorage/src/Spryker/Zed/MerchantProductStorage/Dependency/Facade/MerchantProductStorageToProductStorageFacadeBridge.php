<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductStorage\Dependency\Facade;

class MerchantProductStorageToProductStorageFacadeBridge implements MerchantProductStorageToProductStorageFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductStorage\Business\ProductStorageFacadeInterface
     */
    protected $productStorageFacade;

    /**
     * @param \Spryker\Zed\ProductStorage\Business\ProductStorageFacadeInterface $productStorageFacade
     */
    public function __construct($productStorageFacade)
    {
        $this->productStorageFacade = $productStorageFacade;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publishAbstractProducts(array $productAbstractIds)
    {
        $this->productStorageFacade->publishAbstractProducts($productAbstractIds);
    }
}

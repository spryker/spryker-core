<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOptionStorage\Dependency\Facade;

class MerchantProductOptionStorageToProductOptionStorageFacadeBridge implements MerchantProductOptionStorageToProductOptionStorageFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductOptionStorage\Business\ProductOptionStorageFacadeInterface
     */
    protected $productOptionStorageFacade;

    /**
     * @param \Spryker\Zed\ProductOptionStorage\Business\ProductOptionStorageFacadeInterface $productOptionStorageFacade
     */
    public function __construct($productOptionStorageFacade)
    {
        $this->productOptionStorageFacade = $productOptionStorageFacade;
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $this->productOptionStorageFacade->publish($productAbstractIds);
    }
}

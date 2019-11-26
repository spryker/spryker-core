<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;

class CategoryPageSearchToStoreFacadeBridge implements CategoryPageSearchToStoreFacadeInterface
{
    /**
     * @var \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Store\Business\StoreFacadeInterface $storeFacade
     */
    public function __construct($storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @return string[]
     */
    public function getLocales(): array
    {
        return $this->storeFacade->getLocales();
    }

    /**
     * @param string $storeName
     *
     * @return string[]
     */
    public function getLocalesPerStore(string $storeName): array
    {
        return $this->storeFacade->getLocalesPerStore($storeName);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getStoresWithSharedPersistence(StoreTransfer $storeTransfer)
    {
        return $this->storeFacade->getStoresWithSharedPersistence($storeTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore()
    {
        return $this->storeFacade->getCurrentStore();
    }
}

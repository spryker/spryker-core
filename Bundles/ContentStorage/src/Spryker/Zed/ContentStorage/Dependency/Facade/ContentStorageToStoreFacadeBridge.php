<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentStorage\Dependency\Facade;

class ContentStorageToStoreFacadeBridge implements ContentStorageToStoreFacadeInterface
{
    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct($store)
    {
        $this->store = $store;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getStoresWithSharedPersistence(): array
    {
        return $this->store->getStoresWithSharedPersistence();
    }

    /**
     * @return string[]
     */
    public function getLocales(): array
    {
        return $this->store->getLocales();
    }

    /**
     * @param string $storeName
     *
     * @return string[]
     */
    public function getLocalesPerStore(string $storeName): array
    {
        return $this->store->getLocalesPerStore($storeName);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGui\Dependency\Facade;

class StoreGuiToStoreFacadeBridge implements StoreGuiToStoreFacadeInterface
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
     * @return bool
     */
    public function isMultiStorePerZedEnabled(): bool
    {
        return $this->storeFacade->isMultiStorePerZedEnabled();
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getStoresAvailableForCurrentPersistence(): array
    {
        return $this->storeFacade->getStoresAvailableForCurrentPersistence();
    }
}

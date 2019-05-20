<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;

class CurrencyToStoreFacadeBridge implements CurrencyToStoreFacadeInterface
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
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getAllStores()
    {
        return $this->storeFacade->getAllStores();
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore()
    {
        return $this->storeFacade->getCurrentStore();
    }

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\StoreTransfer|null
     */
    public function findStoreByName(string $name): ?StoreTransfer
    {
        return $this->storeFacade->findStoreByName($name);
    }
}

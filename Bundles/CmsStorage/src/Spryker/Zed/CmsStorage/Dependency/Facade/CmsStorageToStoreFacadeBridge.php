<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsStorage\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;

class CmsStorageToStoreFacadeBridge implements CmsStorageToStoreFacadeInterface
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
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore()
    {
        return $this->storeFacade->getCurrentStore();
    }

    /**
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function getAllStores()
    {
        return $this->storeFacade->getAllStores();
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function getStoresWithSharedPersistence(StoreTransfer $storeTransfer): array
    {
        return $this->storeFacade->getStoresWithSharedPersistence($storeTransfer);
    }
}

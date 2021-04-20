<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentStorage\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;

interface ContentStorageToStoreFacadeInterface
{
    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore(): StoreTransfer;

    /**
     * @param StoreTransfer $storeTransfer
     *
     * @return array
     */
    public function getStoresWithSharedPersistence(StoreTransfer $storeTransfer): array;

    /**
     * @param int $idStore
     *
     * @return StoreTransfer
     */
    public function getStoreById($idStore): StoreTransfer;
}

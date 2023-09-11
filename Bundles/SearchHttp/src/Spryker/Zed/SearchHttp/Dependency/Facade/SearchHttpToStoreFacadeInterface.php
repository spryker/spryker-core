<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchHttp\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;

interface SearchHttpToStoreFacadeInterface
{
    /**
     * @param string $storeReference
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByStoreReference(string $storeReference): StoreTransfer;

    /**
     * @return list<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function getAllStores(): array;
}

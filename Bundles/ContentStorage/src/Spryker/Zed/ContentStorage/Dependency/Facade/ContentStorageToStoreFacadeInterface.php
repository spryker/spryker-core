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
     * @return string[]
     */
    public function getLocales(): array;

    /**
     * @return array
     */
    public function getStoresWithSharedPersistence(): array;

    /**
     * @param string $storeName
     *
     * @return string[]
     */
    public function getLocalesPerStore(string $storeName): array;
}

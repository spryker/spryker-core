<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StoreStorage;

use Generated\Shared\Transfer\StoreStorageTransfer;

interface StoreStorageClientInterface
{
    /**
     * Specification:
     * - Finds store in storage based on the name parameter.
     * - Returns null if the store is not found.
     *
     * @api
     *
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\StoreStorageTransfer|null
     */
    public function findStoreByName(string $name): ?StoreStorageTransfer;

    /**
     * Specification:
     * - Returns list of all stores.
     *
     * @api
     *
     * @return array<string>
     */
    public function getStoreNames(): array;
}

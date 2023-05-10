<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointStorage\Persistence;

use Generated\Shared\Transfer\ServicePointStorageTransfer;

interface ServicePointStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointStorageTransfer $servicePointStorageTransfer
     * @param string $storeName
     *
     * @return void
     */
    public function saveServicePointStorageForStore(
        ServicePointStorageTransfer $servicePointStorageTransfer,
        string $storeName
    ): void;

    /**
     * @param list<int> $servicePointIds
     * @param string|null $storeName
     *
     * @return void
     */
    public function deleteServicePointStorageByServicePointIds(array $servicePointIds, ?string $storeName = null): void;
}

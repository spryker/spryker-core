<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Store;

use Generated\Shared\Transfer\StoreTransfer;

interface StoreClientInterface
{
    /**
     * Specification:
     * - Retrieves the current Store as a transfer object.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore();

    /**
     * Specification:
     * - Retrieves a Store as a transfer object.
     * - Executes stack of {@link \Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface} plugins.
     *
     * @api
     *
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByName(string $storeName): StoreTransfer;

    /**
     * Specification:
     * - Returns true if dynamic store functionality enabled.
     *
     * @api
     *
     * @return bool
     */
    public function isDynamicStoreEnabled(): bool;

    /**
     * Specification:
     * - Returns true if the current store is provided in the application.
     *
     * @api
     *
     * @return bool
     */
    public function isCurrentStoreDefined(): bool;
}

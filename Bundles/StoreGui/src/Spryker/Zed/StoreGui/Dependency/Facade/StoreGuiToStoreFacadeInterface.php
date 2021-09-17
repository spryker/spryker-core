<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGui\Dependency\Facade;

interface StoreGuiToStoreFacadeInterface
{
    /**
     * @return bool
     */
    public function isMultiStorePerZedEnabled(): bool;

    /**
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function getStoresAvailableForCurrentPersistence(): array;
}

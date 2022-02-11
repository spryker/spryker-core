<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorageExtension\Dependency\Plugin;

/**
 * Provides capabilities to filter ProductAbstractStorage transfers before storing to storage.
 * Filtered transfers will be removed from storage.
 */
interface ProductAbstractStorageCollectionFilterPluginInterface
{
    /**
     * Specification:
     * - Filters product abstract storage transfers.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductAbstractStorageTransfer> $productAbstractStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractStorageTransfer>
     */
    public function filter(array $productAbstractStorageTransfers): array;
}

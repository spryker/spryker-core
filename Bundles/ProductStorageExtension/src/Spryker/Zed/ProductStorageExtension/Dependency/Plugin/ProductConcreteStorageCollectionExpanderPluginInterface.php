<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorageExtension\Dependency\Plugin;

/**
 * Provides ability to expand ProductConcreteStorage transfers.
 */
interface ProductConcreteStorageCollectionExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands ProductConcreteStorage transfers.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer> $productConcreteStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer>
     */
    public function expand(array $productConcreteStorageTransfers): array;
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionStorageExtension\Dependency\Plugin;

/**
 * Provides capabilities to filter ProductOption transfers before storing to storage.
 * Filtered product options will be removed from storage.
 */
interface ProductOptionCollectionFilterPluginInterface
{
    /**
     * Specification:
     * - Filters product option transfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOptionTransfer[] $productOptionTransfers
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer[]
     */
    public function filter(array $productOptionTransfers): array;
}

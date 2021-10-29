<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductViewTransfer;

/**
 * Provides product price collection expansion capabilities.
 *
 * Use this plugin interface for expanding collection of PriceProductTransfers.
 */
interface PriceProductExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands `PriceProductTransfer` collection.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function expand(array $priceProductTransfers, ProductViewTransfer $productViewTransfer): array;
}

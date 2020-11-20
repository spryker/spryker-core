<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnectorExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CartChangeTransfer;

/**
 * Use this plugin to expand the list of the product prices with additional ones base on the data from the cart.
 */
interface PriceProductExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands the list of price product transfers with additional ones based on the data from the cart.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function expandPriceProductTransfers(
        array $priceProductTransfers,
        CartChangeTransfer $cartChangeTransfer
    ): array;
}

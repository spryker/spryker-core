<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\Plugin;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Client\CartExtension\Dependency\Plugin\CartChangeRequestExpanderPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\Cart\CartClient getClient()
 */
class ProductSeparateCartChangeExpanderPlugin extends AbstractPlugin implements CartChangeRequestExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `CartChangeTransfer.quote` to be set.
     * - Requires `CartChangeTransfer.items.sku` to be set.
     * - Checks if `separate_product` parameter is specified, otherwise skips the extension.
     * - Checks that an item with the same SKU already exists in the cart.
     * - Expands the cart item with the group key prefix.
     * - Returns an expanded `CartChangeTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expand(CartChangeTransfer $cartChangeTransfer, array $params = []): CartChangeTransfer
    {
        return $this->getClient()
            ->expandCartItemsWithGroupKeyPrefix($cartChangeTransfer, $params);
    }
}

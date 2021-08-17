<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationCart\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Client\CartExtension\Dependency\Plugin\CartChangeRequestExpanderPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\ProductConfigurationCart\ProductConfigurationCartClientInterface getClient()
 */
class ProductConfigurationCartChangeRequestExpanderPlugin extends AbstractPlugin implements CartChangeRequestExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `CartChangeTransfer::items::sku` to be set.
     * - Checks if the item has a configuration, if it does, the default configuration will not be set.
     * - Expands the provided cart change transfer items with the corresponding product configuration instance.
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
        return $this->getClient()->expandCartChangeWithProductConfigurationInstance($cartChangeTransfer, $params);
    }
}

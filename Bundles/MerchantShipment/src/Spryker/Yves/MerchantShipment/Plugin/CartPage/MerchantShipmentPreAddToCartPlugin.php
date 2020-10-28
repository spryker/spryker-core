<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MerchantShipment\Plugin\CartPage;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CartPageExtension\Dependency\Plugin\PreAddToCartPluginInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\MerchantShipment\Communication\Plugin\Quote\MerchantShipmentQuoteExpanderPlugin} instead.
 */
class MerchantShipmentPreAddToCartPlugin extends AbstractPlugin implements PreAddToCartPluginInterface
{
    /**
     * {@inheritDoc}
     * - Sets ShipmentTransfer.merchantReference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function preAddToCart(ItemTransfer $itemTransfer, array $params): ItemTransfer
    {
        if (!$itemTransfer->getShipment()) {
            return $itemTransfer;
        }

        $itemTransfer->getShipment()->setMerchantReference($itemTransfer->getMerchantReference());

        return $itemTransfer;
    }
}

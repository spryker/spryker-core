<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantShipment\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\ItemExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantShipment\Business\MerchantShipmentFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantShipment\MerchantShipmentConfig getConfig()
 */
class MerchantShipmentItemExpanderPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * - Expects `cartChange.items.shipment` to be set.
     * - Sets `cartChange.items.shipment.merchantReference` from `cartChange.items.merchantReference`.
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     *@api
     *
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getFacade()->expandCartChangeShipmentWithMerchantReference($cartChangeTransfer);
    }
}

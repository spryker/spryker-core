<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MerchantShipment\Plugin\CustomerPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\CheckoutAddressStepPreGroupItemsByShipmentPluginInterface;

class MerchantShipmentCheckoutAddressStepPreGroupItemsByShipmentPlugin extends AbstractPlugin implements CheckoutAddressStepPreGroupItemsByShipmentPluginInterface
{
    /**
     * {@inheritDoc}
     * - Unsets ShipmentTransfer.merchantReference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preGroupItemsByShipment(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $shipmentTransfer = $itemTransfer->getShipment();

            if (!$shipmentTransfer) {
                continue;
            }

            $shipmentTransfer->setMerchantReference(null);
            $itemTransfer->setShipment($shipmentTransfer);
        }

        return $quoteTransfer;
    }
}

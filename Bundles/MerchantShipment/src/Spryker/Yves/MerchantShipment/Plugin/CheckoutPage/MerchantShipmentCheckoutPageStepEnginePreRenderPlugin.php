<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MerchantShipment\Plugin\CheckoutPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\StepEngine\CheckoutPageStepEnginePreRenderPluginInterface;

class MerchantShipmentCheckoutPageStepEnginePreRenderPlugin extends AbstractPlugin implements CheckoutPageStepEnginePreRenderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Sets ShipmentTransfer.merchantReference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(AbstractTransfer $quoteTransfer): AbstractTransfer
    {
        if (!$quoteTransfer instanceof QuoteTransfer) {
            return $quoteTransfer;
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $shipmentTransfer = $itemTransfer->getShipment();

            if (!$shipmentTransfer) {
                continue;
            }

            $shipmentTransfer->setMerchantReference($itemTransfer->getMerchantReference());
            $itemTransfer->setShipment($shipmentTransfer);
        }

        return $quoteTransfer;
    }
}

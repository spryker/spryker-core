<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeCart\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ShipmentTypeCart\ShipmentTypeCartConfig getConfig()
 * @method \Spryker\Zed\ShipmentTypeCart\Business\ShipmentTypeCartFacadeInterface getFacade()
 */
class ShipmentTypeCheckoutPreConditionPlugin extends AbstractPlugin implements CheckoutPreConditionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `QuoteTransfer.store.name` transfer property to be set.
     * - Expects `QuoteTransfer.items.shipment.shipmentTypeUuid` transfer property to be provided.
     * - Expects `QuoteTransfer.items.shipment.method.shipmentType.uuid` transfer property to be provided.
     * - Expects `QuoteTransfer.items.shipment.method.shipmentType.name` transfer property to be provided.
     * - Checks if selected shipment type matches selected shipment method's shipment type.
     * - Checks if selected shipment type is active and available for store provided in `QuoteTransfer.store`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        return $this->getFacade()->isQuoteReadyForCheckout($quoteTransfer, $checkoutResponseTransfer);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypesRestApi\Communication\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataValidatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ShipmentTypesRestApi\Business\ShipmentTypesRestApiFacadeInterface getFacade()
 */
class ShipmentTypeCheckoutDataValidatorPlugin extends AbstractPlugin implements CheckoutDataValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Does nothing if `idShipmentMethod` transfer property is empty for each element in `CheckoutDataTransfer.shipments` in case of multi shipment delivery.
     * - Does nothing if `CheckoutDataTransfer.shipment.idShipmentMethod` is empty in case of single shipment delivery.
     * - Requires `CheckoutDataTransfer.quote.store.idStore` transfer property to be set.
     * - Expects `CheckoutDataTransfer.shipments.shipmentMethod.idShipmentMethod` to be set in case of multi shipment delivery.
     * - Gets available shipment methods.
     * - Validates whether shipment type related to the shipment method is active and belongs to the quote store.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateCheckoutData(CheckoutDataTransfer $checkoutDataTransfer): CheckoutResponseTransfer
    {
        return $this->getFacade()->validateShipmentTypeCheckoutData($checkoutDataTransfer);
    }
}

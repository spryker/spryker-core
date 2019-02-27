<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Business\Shipment;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\ShipmentCheckoutConnector\Business\Model\ShipmentCheckoutPreCheck as ShipmentCheckoutPreCheckWithoutMultiShipment;

class ShipmentCheckoutPreCheck extends ShipmentCheckoutPreCheckWithoutMultiShipment
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkShipment(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): bool {
        $availableShipmentMethods = $this->shipmentFacade->getAvailableMethods($quoteTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() === null) {
                continue;
            }

            $idShipmentMethod = $itemTransfer->getShipment()->getMethod()->getIdShipmentMethod();
            $shipmentMethodTransfer = $this->filterAvailableMethodById($idShipmentMethod, $availableShipmentMethods);

            if ($idShipmentMethod === null || $shipmentMethodTransfer === null) {
                $checkoutErrorTransfer = $this->createCheckoutErrorTransfer();

                $checkoutResponseTransfer
                    ->setIsSuccess(false)
                    ->addError($checkoutErrorTransfer);

                return false;
            }
        }

        return true;
    }

    /**
     * @param int $idShipmentMethod
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $availableShipmentMethods
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    protected function filterAvailableMethodById(
        int $idShipmentMethod,
        ShipmentMethodsTransfer $availableShipmentMethods
    ): ?ShipmentMethodTransfer {
        foreach ($availableShipmentMethods->getMethods() as $shipentMethodTransfer) {
            if ($idShipmentMethod === $shipentMethodTransfer->getIdShipmentMethod()) {
                return $shipentMethodTransfer;
            }
        }

        return null;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Business\Shipment;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\ShipmentCheckoutConnector\Dependency\Facade\ShipmentCheckoutConnectorToShipmentFacadeInterface;

class ShipmentCheckoutPreCheck implements ShipmentCheckoutPreCheckInterface
{
    public const TRANSLATION_KEY_SHIPMENT_NOT_VALID = 'checkout.pre.check.shipment.failed';

    /**
     * @var \Spryker\Zed\ShipmentCheckoutConnector\Dependency\Facade\ShipmentCheckoutConnectorToShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @param \Spryker\Zed\ShipmentCheckoutConnector\Dependency\Facade\ShipmentCheckoutConnectorToShipmentFacadeInterface $shipmentFacade
     */
    public function __construct(ShipmentCheckoutConnectorToShipmentFacadeInterface $shipmentFacade)
    {
        $this->shipmentFacade = $shipmentFacade;
    }

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

        $checkShipmentStatus = true;
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $shipmentTransfer = $itemTransfer->getShipment();
            if ($shipmentTransfer === null) {
                continue;
            }

            $idShipmentMethod = $shipmentTransfer->getMethod()->getIdShipmentMethod();
            $shipmentMethodTransfer = $this->filterAvailableMethodById($idShipmentMethod, $availableShipmentMethods);

            if ($idShipmentMethod === null || $shipmentMethodTransfer === null) {
                $checkoutErrorTransfer = $this->createCheckoutErrorTransfer($shipmentTransfer->getMethod());

                $checkoutResponseTransfer
                    ->setIsSuccess(false)
                    ->addError($checkoutErrorTransfer);

                $checkShipmentStatus = false;
                continue;
            }
        }

        return $checkShipmentStatus;
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

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutErrorTransfer(ShipmentMethodTransfer $shipmentMethodTransfer): CheckoutErrorTransfer
    {
        return (new CheckoutErrorTransfer())
            ->addParameters([
                '%method_name%' => $shipmentMethodTransfer->getName(),
                '%carrier_name%' => $shipmentMethodTransfer->getCarrierName(),
            ])
            ->setErrorCode(ShipmentCheckoutConnectorConfig::ERROR_CODE_SHIPMENT_FAILED)
            ->setMessage(static::TRANSLATION_KEY_SHIPMENT_NOT_VALID);
    }
}

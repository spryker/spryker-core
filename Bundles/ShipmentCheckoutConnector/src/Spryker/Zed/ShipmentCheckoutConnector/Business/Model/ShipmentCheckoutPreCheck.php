<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Business\Model;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\ShipmentCheckoutConnector\ShipmentCheckoutConnectorConfig;
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
    public function checkShipment(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $availableShipmentMethods = $this->shipmentFacade->getAvailableMethods($quoteTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getShipment()) {
                continue;
            }

            $idShipmentMethod = $itemTransfer->getShipment()->getMethod()->getIdShipmentMethod();
            $shipmentMethodTransfer = current(array_filter(
                $availableShipmentMethods->getMethods()->getArrayCopy(),
                function ($shipmentMethodTransfer) use ($idShipmentMethod) {
                    return $idShipmentMethod == $shipmentMethodTransfer->getIdShipmentMethod();
                }
            ));

            if (!$idShipmentMethod || !$shipmentMethodTransfer) {
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
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutErrorTransfer()
    {
        return (new CheckoutErrorTransfer())
            ->setErrorCode(ShipmentCheckoutConnectorConfig::ERROR_CODE_SHIPMENT_FAILED)
            ->setMessage(static::TRANSLATION_KEY_SHIPMENT_NOT_VALID);
    }
}

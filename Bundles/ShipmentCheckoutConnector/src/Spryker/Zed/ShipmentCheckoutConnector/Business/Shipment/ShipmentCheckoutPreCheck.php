<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Business\Shipment;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\ShipmentCheckoutConnector\Dependency\Facade\ShipmentCheckoutConnectorToShipmentFacadeInterface;
use Spryker\Shared\ShipmentCheckoutConnector\ShipmentCheckoutConnectorConfig;

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
        $availableShipmentMethodCollectionTransfer = $this->shipmentFacade->getAvailableMethodsByShipment($quoteTransfer);

        $checkShipmentStatus = true;
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $quoteShipmentTransfer = $itemTransfer->getShipment();
            if ($quoteShipmentTransfer === null) {
                continue;
            }

            $quoteShipmentMethodTransfer = $quoteShipmentTransfer->getMethod();
            if($quoteShipmentMethodTransfer === null) {
                continue;
            }

            $quoteIddShipmentMethod = $quoteShipmentMethodTransfer->getIdShipmentMethod();
            if($quoteIddShipmentMethod === null) {
                continue;
            }

            $shipmentMethodTransfer = $this->filterAvailableMethodById($quoteIddShipmentMethod, $availableShipmentMethodCollectionTransfer);
            if ($shipmentMethodTransfer === null) {
                $checkoutErrorTransfer = $this->createCheckoutErrorTransfer($quoteShipmentTransfer->getMethod());

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
     * @param \Generated\Shared\Transfer\ShipmentGroupCollectionTransfer $availableShipmentMethodCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    protected function filterAvailableMethodById(
        int $idShipmentMethod,
        ShipmentGroupCollectionTransfer $availableShipmentMethodCollectionTransfer
    ): ?ShipmentMethodTransfer {
        foreach ($availableShipmentMethodCollectionTransfer->getGroups() as $shipmentGroupTransfer) {
            $shipmentTransfer = $shipmentGroupTransfer->getShipment();
            if($shipmentTransfer === null) {
                continue;
            }

            $shipmentMethodTransfer = $shipmentTransfer->getMethod();
            if($shipmentMethodTransfer === null) {
                continue;
            }

            if($idShipmentMethod === $shipmentMethodTransfer->getIdShipmentMethod()) {
                return $shipmentMethodTransfer;
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

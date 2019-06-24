<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Business\Shipment;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Shared\ShipmentCheckoutConnector\ShipmentCheckoutConnectorConfig;
use Spryker\Zed\ShipmentCheckoutConnector\Dependency\Facade\ShipmentCheckoutConnectorToShipmentFacadeInterface;
use Spryker\Zed\ShipmentCheckoutConnector\Dependency\Service\ShipmentCheckoutConnectorToShipmentServiceInterface;

class ShipmentCheckoutPreCheck implements ShipmentCheckoutPreCheckInterface
{
    public const TRANSLATION_KEY_SHIPMENT_NOT_VALID = 'checkout.pre.check.shipment.failed';

    /**
     * @var \Spryker\Zed\ShipmentCheckoutConnector\Dependency\Facade\ShipmentCheckoutConnectorToShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @var \Spryker\Zed\ShipmentCheckoutConnector\Dependency\Service\ShipmentCheckoutConnectorToShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @param \Spryker\Zed\ShipmentCheckoutConnector\Dependency\Facade\ShipmentCheckoutConnectorToShipmentFacadeInterface $shipmentFacade
     * @param \Spryker\Zed\ShipmentCheckoutConnector\Dependency\Service\ShipmentCheckoutConnectorToShipmentServiceInterface $shipmentService
     */
    public function __construct(
        ShipmentCheckoutConnectorToShipmentFacadeInterface $shipmentFacade,
        ShipmentCheckoutConnectorToShipmentServiceInterface $shipmentService
    ) {
        $this->shipmentFacade = $shipmentFacade;
        $this->shipmentService = $shipmentService;
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
        $shipmentGroupCollection = $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());

        $checkShipmentStatus = true;
        foreach ($shipmentGroupCollection as $shipmentGroupTransfer) {
            $shipmentTransfer = $shipmentGroupTransfer->getShipment();
            if ($shipmentTransfer === null) {
                continue;
            }

            $shipmentMethodTransfer = $shipmentTransfer->getMethod();

            if ($shipmentMethodTransfer !== null
                && $shipmentMethodTransfer->getIdShipmentMethod()
                && $this->shipmentFacade->isShipmentMethodActive($shipmentMethodTransfer->getIdShipmentMethod())
            ) {
                continue;
            }

            $checkoutErrorTransfer = $this->createCheckoutErrorTransfer($shipmentMethodTransfer);

            $checkoutResponseTransfer
                ->setIsSuccess(false)
                ->addError($checkoutErrorTransfer);
            $checkShipmentStatus = false;
        }

        return $checkShipmentStatus;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer|null $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutErrorTransfer(?ShipmentMethodTransfer $shipmentMethodTransfer): CheckoutErrorTransfer
    {
        return (new CheckoutErrorTransfer())
            ->addParameters([
                '%method_name%' => $shipmentMethodTransfer ? $shipmentMethodTransfer->getName() : 'null',
                '%carrier_name%' => $shipmentMethodTransfer ? $shipmentMethodTransfer->getCarrierName() : 'null',
            ])
            ->setErrorCode(ShipmentCheckoutConnectorConfig::ERROR_CODE_SHIPMENT_FAILED)
            ->setMessage(static::TRANSLATION_KEY_SHIPMENT_NOT_VALID);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCartConnector\Business\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToPriceFacadeInterface;
use Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToShipmentFacadeInterface;
use Spryker\Zed\ShipmentCartConnector\Dependency\Service\ShipmentCartConnectorToShipmentServiceInterface;

class ShipmentCartValidator implements ShipmentCartValidatorInterface
{
    public const CART_PRE_CHECK_SHIPMENT_FAILED_TRANSLATION_KEY = 'cart.pre.check.shipment.failed';

    /**
     * @var \Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @var \Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToPriceFacadeInterface
     */
    protected $priceFacade;

    /**
     * @var \Spryker\Zed\ShipmentCartConnector\Dependency\Service\ShipmentCartConnectorToShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @param \Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToShipmentFacadeInterface $shipmentFacade
     * @param \Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToPriceFacadeInterface $priceFacade
     * @param \Spryker\Zed\ShipmentCartConnector\Dependency\Service\ShipmentCartConnectorToShipmentServiceInterface $shipmentService
     */
    public function __construct(
        ShipmentCartConnectorToShipmentFacadeInterface $shipmentFacade,
        ShipmentCartConnectorToPriceFacadeInterface $priceFacade,
        ShipmentCartConnectorToShipmentServiceInterface $shipmentService
    ) {
        $this->shipmentFacade = $shipmentFacade;
        $this->priceFacade = $priceFacade;
        $this->shipmentService = $shipmentService;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateShipment(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $cartPreCheckResponseTransfer = (new CartPreCheckResponseTransfer())
            ->setIsSuccess(true);

        $quoteTransfer = $cartChangeTransfer->getQuote();

        $availableShipmentMethodsCollectionTransfer = $this->shipmentFacade->getAvailableMethodsByShipment($quoteTransfer);
        $shipmentGroupCollection = $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());

        foreach ($shipmentGroupCollection as $shipmentGroupTransfer) {
            $shipmentTransfer = $shipmentGroupTransfer->getShipment();
            if ($shipmentTransfer === null) {
                continue;
            }

            $cartShipmentMethodTransfer = $shipmentTransfer->getMethod();

            if ($cartShipmentMethodTransfer === null
                || $cartShipmentMethodTransfer->getIdShipmentMethod() === null
                || $this->isCurrencyChanged($shipmentTransfer, $quoteTransfer) === false
            ) {
                continue;
            }

            $availableShipmentMethods = $this->findAvailableShipmentMethodsByShipment(
                $availableShipmentMethodsCollectionTransfer,
                $shipmentTransfer
            );

            if ($availableShipmentMethods === null) {
                continue;
            }

            $shipmentMethodTransfer = $this->findAvailableShipmentMethodByIdShipmentMethod(
                $availableShipmentMethods,
                $cartShipmentMethodTransfer->getIdShipmentMethod()
            );

            if ($shipmentMethodTransfer !== null) {
                continue;
            }

            $cartPreCheckResponseTransfer
                ->setIsSuccess(false)
                ->addMessage($this->createMessage($cartShipmentMethodTransfer));
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer $shipmentMethodsCollection
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer|null
     */
    protected function findAvailableShipmentMethodsByShipment(
        ShipmentMethodsCollectionTransfer $shipmentMethodsCollection,
        ShipmentTransfer $shipmentTransfer
    ): ?ShipmentMethodsTransfer {
        $shipmentHash = $this->shipmentService->getShipmentHashKey($shipmentTransfer);
        foreach ($shipmentMethodsCollection->getShipmentMethods() as $shipmentMethodsTransfer) {
            if ($shipmentMethodsTransfer->getShipmentHash() === $shipmentHash) {
                return $shipmentMethodsTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $availableShipmentMethods
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    protected function findAvailableShipmentMethodByIdShipmentMethod(
        ShipmentMethodsTransfer $availableShipmentMethods,
        int $idShipmentMethod
    ): ?ShipmentMethodTransfer {
        foreach ($availableShipmentMethods->getMethods() as $shipmentMethodTransfer) {
            if ($idShipmentMethod === $shipmentMethodTransfer->getIdShipmentMethod()) {
                return $shipmentMethodTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isCurrencyChanged(ShipmentTransfer $shipmentTransfer, QuoteTransfer $quoteTransfer): bool
    {
        if ($shipmentTransfer->getMethod() === null) {
            return false;
        }

        $shipmentCurrencyIsoCode = $shipmentTransfer->getMethod()->getCurrencyIsoCode();
        if ($shipmentCurrencyIsoCode !== $quoteTransfer->getCurrency()->getCode()) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessage(ShipmentMethodTransfer $shipmentMethodTransfer): MessageTransfer
    {
        return (new MessageTransfer())
            ->addParameters([
                '%method_name%' => $shipmentMethodTransfer->getName(),
                '%carrier_name%' => $shipmentMethodTransfer->getCarrierName(),
            ])
            ->setValue(static::CART_PRE_CHECK_SHIPMENT_FAILED_TRANSLATION_KEY);
    }
}

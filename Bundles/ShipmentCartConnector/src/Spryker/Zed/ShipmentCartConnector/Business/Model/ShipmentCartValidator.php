<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCartConnector\Business\Model;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToPriceFacadeInterface;
use Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToShipmentFacadeInterface;

/**
 * @deprecated Use \Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartValidator instead.
 */
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
     * @param \Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToShipmentFacadeInterface $shipmentFacade
     * @param \Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToPriceFacadeInterface $priceFacade
     */
    public function __construct(
        ShipmentCartConnectorToShipmentFacadeInterface $shipmentFacade,
        ShipmentCartConnectorToPriceFacadeInterface $priceFacade
    ) {
        $this->shipmentFacade = $shipmentFacade;
        $this->priceFacade = $priceFacade;
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

        $availableShipmentMethods = $this->shipmentFacade->getAvailableMethods($quoteTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $shipmentMethod = $itemTransfer->getShipment();
            $skipValidation = (
                $shipmentMethod === null
                || $this->isCurrencyChanged($shipmentMethod, $quoteTransfer) === false
            );

            if ($skipValidation) {
                continue;
            }

            $idShipmentMethod = $shipmentMethod->getMethod()->getIdShipmentMethod();
            $shipmentMethodTransfer = $this->filterAvailableMethodById($idShipmentMethod, $availableShipmentMethods);

            if ($shipmentMethodTransfer === null) {
                $cartPreCheckResponseTransfer
                    ->setIsSuccess(false)
                    ->addMessage($this->createMessage());

                return $cartPreCheckResponseTransfer;
            }
        }

        return $cartPreCheckResponseTransfer;
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
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessage()
    {
        return (new MessageTransfer())
            ->setValue(static::CART_PRE_CHECK_SHIPMENT_FAILED_TRANSLATION_KEY);
    }
}

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
    public function validateShipment(CartChangeTransfer $cartChangeTransfer)
    {
        $cartPreCheckResponseTransfer = (new CartPreCheckResponseTransfer())
            ->setIsSuccess(true);

        $quoteTransfer = $cartChangeTransfer->getQuote();
        if (!$quoteTransfer->getShipment() || !$this->isCurrencyChanged($quoteTransfer)) {
            return $cartPreCheckResponseTransfer;
        }

        $idShipmentMethod = $quoteTransfer->getShipment()->getMethod()->getIdShipmentMethod();
        $shipmentMethodTransfer = $this->shipmentFacade->findAvailableMethodById($idShipmentMethod, $quoteTransfer);

        if (!$shipmentMethodTransfer) {
             $cartPreCheckResponseTransfer
                ->setIsSuccess(false)
                ->addMessage($this->createMessage());
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isCurrencyChanged(QuoteTransfer $quoteTransfer)
    {
        if (!$quoteTransfer->getShipment()->getMethod()) {
            return false;
        }

        $shipmentCurrency = $quoteTransfer->getShipment()->getMethod()->getCurrencyIsoCode();

        if ($shipmentCurrency !== $quoteTransfer->getCurrency()->getCode()) {
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

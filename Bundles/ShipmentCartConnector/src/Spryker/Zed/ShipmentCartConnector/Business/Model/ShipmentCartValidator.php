<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCartConnector\Business\Model;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToPriceFacadeInterface;
use Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToShipmentFacadeInterface;

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
     * @var \Spryker\Zed\ShipmentCartConnector\Business\Model\ShipmentCartExpanderHelper
     */
    protected $shipmentCartExpanderHelper;

    /**
     * @param \Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToShipmentFacadeInterface $shipmentFacade
     * @param \Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToPriceFacadeInterface $priceFacade
     * @param \Spryker\Zed\ShipmentCartConnector\Business\Model\ShipmentCartExpanderHelper $shipmentCartExpanderHelper
     */
    public function __construct(
        ShipmentCartConnectorToShipmentFacadeInterface $shipmentFacade,
        ShipmentCartConnectorToPriceFacadeInterface $priceFacade,
        ShipmentCartExpanderHelper $shipmentCartExpanderHelper
    ) {
        $this->shipmentFacade = $shipmentFacade;
        $this->priceFacade = $priceFacade;
        $this->shipmentCartExpanderHelper = $shipmentCartExpanderHelper;
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
            $skipValidation = (
                $itemTransfer->getShipment() === null
                || !$this->shipmentCartExpanderHelper->isCurrencyChanged($itemTransfer->getShipment(), $quoteTransfer)
            );

            if ($skipValidation) {
                continue;
            }

            $idShipmentMethod = $itemTransfer->getShipment()->getMethod()->getIdShipmentMethod();
            $shipmentMethodTransfer = $this->shipmentCartExpanderHelper
                ->findAvailableMethodById(
                    $idShipmentMethod,
                    $availableShipmentMethods
                );

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
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessage()
    {
        return (new MessageTransfer())
            ->setValue(static::CART_PRE_CHECK_SHIPMENT_FAILED_TRANSLATION_KEY);
    }
}

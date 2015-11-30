<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ShipmentCheckoutConnector\Business\Model;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use SprykerFeature\Zed\ShipmentCheckoutConnector\Persistence\ShipmentCheckoutConnectorQueryContainerInterface;

class ShipmentOrderHydrator implements ShipmentOrderHydratorInterface
{

    /**
     * @var ShipmentCheckoutConnectorQueryContainerInterface
     */
    protected $queryContainer;

    public function __construct(ShipmentCheckoutConnectorQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param OrderTransfer $order
     * @param CheckoutRequestTransfer $request
     */
    public function hydrateOrderTransfer(OrderTransfer $order, CheckoutRequestTransfer $request)
    {
        $idShipmentMethod = $request->getIdShipmentMethod();

        $shipmentMethodEntity = $this->queryContainer->queryShipmentOrderById($idShipmentMethod)->findOne();

        $shipmentMethodTransfer = new ShipmentMethodTransfer();
        $shipmentMethodTransfer->fromArray($shipmentMethodEntity->toArray());

        $order->setIdShipmentMethod($idShipmentMethod);
        $order->setShipmentMethod($shipmentMethodTransfer);
    }

}

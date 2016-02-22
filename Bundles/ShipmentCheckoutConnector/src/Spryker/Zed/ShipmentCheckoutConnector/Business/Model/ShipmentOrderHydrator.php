<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Business\Model;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\ShipmentCheckoutConnector\Persistence\ShipmentCheckoutConnectorQueryContainerInterface;

class ShipmentOrderHydrator implements ShipmentOrderHydratorInterface
{

    /**
     * @var \Spryker\Zed\ShipmentCheckoutConnector\Persistence\ShipmentCheckoutConnectorQueryContainerInterface
     */
    protected $queryContainer;

    public function __construct(ShipmentCheckoutConnectorQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $order
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $request
     *
     * @return void
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

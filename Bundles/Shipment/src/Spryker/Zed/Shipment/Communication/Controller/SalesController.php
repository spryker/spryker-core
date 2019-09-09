<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Controller;

use Spryker\Shared\Shipment\ShipmentConfig;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Shipment\Communication\ShipmentCommunicationFactory getFactory()
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface getRepository()
 */
class SalesController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        /** @var \Generated\Shared\Transfer\OrderTransfer $orderTransfer */
        $orderTransfer = $request->request->get('orderTransfer');

        $shipmentGroups = $this->getFactory()->getShipmentService()->groupItemsByShipment($orderTransfer->getItems());

        return $this->viewResponse([
            'shipmentGroups' => $shipmentGroups,
            'order' => $orderTransfer,
            'currencyIsoCode' => $orderTransfer->getCurrencyIsoCode(),
            'shipmentExpenseType' => ShipmentConfig::SHIPMENT_EXPENSE_TYPE,
        ]);
    }
}

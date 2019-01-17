<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Controller;

use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ShipmentGui\Business\Exception\ShipmentException;
use Spryker\Zed\ShipmentGui\ShipmentGuiConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ShipmentGui\Persistence\ShipmentGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ShipmentGui\Business\ShipmentGuiFacadeInterface getFacade()
 */
class EditController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idSalesOrder = $this->castId($request->get(ShipmentGuiConfig::PARAM_ID_SALES_ORDER));
        $orderTransfer = $this
            ->getFactory()
            ->getSalesFacade()
            ->findOrderByIdSalesOrder($idSalesOrder);

        if (!$orderTransfer) {
            $this->addErrorMessage((sprintf(
                'Order with #%d not found.',
                $idSalesOrder
            )));

            return $this->redirectResponse('/sales');
        }

        $idShipment = $this->castId($request->get(ShipmentGuiConfig::PARAM_ID_SHIPMENT));
        $shipmentTransfer = $this
            ->getFactory()
            ->getShipmentFacade()
            ->findShipmentById($idShipment);

        if (!$shipmentTransfer) {
            $this->addErrorMessage((sprintf(
                'Shipment with #%d not found.',
                $idShipment
            )));

            return $this->redirectResponse('/sales');
        }

        $orderItems = [];
        foreach ($orderTransfer->getItems() as $item) {
            $orderItems[$item->getId()] = $item;
        }

        $dataProvider = $this->getFactory()->createShipmentFormDataProvider();

        $form = $this->getFactory()
            ->getShipmentForm(
                $dataProvider->getData($shipmentTransfer),
                $dataProvider->getOptions($shipmentTransfer)
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $form->getData();
                $shipmentTransfer = $this->createShipmentTransfer($data);
                $this->getFactory()
                    ->getShipmentFacade()
                    ->saveShipment($shipmentTransfer);
                $this->addInfoMessage(sprintf(
                    'Shipment was saved succesfully.'
                ));
            } catch (ShipmentException $exception) {
                $this->addErrorMessage($exception->getMessage());
            }
        }

        return $this->viewResponse([
            'idSalesOrder' => $idSalesOrder,
            'shipmentForm' => $form->createView(),
            'orderItems' => $orderItems,
        ]);
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function createShipmentTransfer(array $data): ShipmentTransfer
    {
        return (new ShipmentTransfer())->fromArray($data, true);
    }
}

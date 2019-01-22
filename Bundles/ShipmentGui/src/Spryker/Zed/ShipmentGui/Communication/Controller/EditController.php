<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Controller;

use \ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ShipmentGui\Business\Exception\ShipmentException;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentForm;
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
    public function indexAction(Request $request): RedirectResponse
    {
        $orderTransfer = $this->getSalesOrderTransfer($request->get(ShipmentGuiConfig::PARAM_ID_SALES_ORDER));

        if (!$orderTransfer) {
            $this->addErrorMessage('Order with #%d not found.', ['%d' => $idSalesOrder]);

            return $this->redirectResponse('/sales');
        }

        $shipmentTransfer = $this->getSalesShipmentTransfer($request->get(ShipmentGuiConfig::PARAM_ID_SHIPMENT));

        if (!$shipmentTransfer) {
            $this->addErrorMessage('Shipment with #%d not found.', ['%d' => $idShipment]);

            return $this->redirectResponse('/sales');
        }

        $orderItems = [];
        foreach ($orderTransfer->getItems() as $item) {
            $orderItems[$item->getId()] = $item;
        }

        $dataProvider = $this->getFactory()->createShipmentFormDataProvider();
        $form = $this->getFactory()
            ->getShipmentForm(
                $dataProvider->getData($shipmentTransfer, $orderTransfer),
                $dataProvider->getOptions($shipmentTransfer)
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $form->getData();
                $shipmentTransfer = $this->createShipmentGroupTransfer($data);

                $this->getFactory()
                    ->getShipmentFacade()
                    ->updateShipmentTransaction($shipmentTransfer);
                
                $this->addInfoMessage('Shipment was saved succesfully.');
            } catch (ShipmentException $exception) {
                $this->addErrorMessage($exception->getMessage());
            }
        }

        return $this->viewResponse([
            'idSalesOrder' => $orderTransfer->getIdSalesOrder(),
            'shipmentForm' => $form->createView(),
        ]);
    }

    /**
     * @param mixed $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    protected function getSalesOrderTransfer($idSalesOrder): OrderTransfer
    {
        return $orderTransfer = $this
            ->getFactory()
            ->getSalesFacade()
            ->findOrderByIdSalesOrder($this->castId($idSalesOrder));
    }

    /**
     * @param mixed $idSalesShipment
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer|null
     */
    protected function getSalesShipmentTransfer($idSalesShipment): ShipmentTransfer
    {
        return $this
            ->getFactory()
            ->getShipmentFacade()
            ->findShipmentById($this->castId($idSalesShipment));
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    protected function createShipmentGroupTransfer(array $data): ShipmentGroupTransfer
    {
        $shipmentGroupTransfer = new ShipmentGroupTransfer();
        $shipmentGroupTransfer->setShipment(
            (new ShipmentTransfer)->fromArray($data, true)
        );

        if (!array_key_exists(ShipmentForm::FIELD_ORDER_ITEMS, $data)) {
            return $shipmentGroupTransfer;
        }

        foreach ($data[ShipmentForm::FIELD_ORDER_ITEMS] as $item) {
            $shipmentGroupTransfer->addItem($item);
        }

        return $shipmentGroupTransfer;
    }
}

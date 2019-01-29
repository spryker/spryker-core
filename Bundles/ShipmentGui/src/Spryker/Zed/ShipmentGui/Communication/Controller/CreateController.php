<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Controller;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentFormCreate;
use Spryker\Zed\ShipmentGui\ShipmentGuiConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 */
class CreateController extends AbstractController
{
    protected const PARAM_REDIRECT_URL = 'redirect-url';
    protected const REDIRECT_URL_DEFAULT = '/sales';

    protected const MESSAGE_SHIPMENT_CREATE_SUCCESS = 'Shipment has been successfully created.';
    protected const MESSAGE_SHIPMENT_CREATE_ERROR = 'Shipment create failed.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $redirectUrl = $request->get(static::PARAM_REDIRECT_URL, static::REDIRECT_URL_DEFAULT);

        $idSalesOrder = $request->query->get(ShipmentGuiConfig::PARAM_ID_SALES_ORDER);
        $dataProvider = $this->getFactory()->createShipmentFormCreateDataProvider();

        $form = $this->getFactory()
            ->createShipmentFormCreate(
                $dataProvider->getData($idSalesOrder),
                $dataProvider->getOptions($idSalesOrder)
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shipmentGroupTransfer = $this->createShipmentGroupTransfer($form->getData());

            $this->getFactory()
                ->getShipmentFacade()
                ->saveShipmentGroup($shipmentGroupTransfer, $idSalesOrder);

            return $this->redirectResponse($redirectUrl);
        }

        return $this->viewResponse([
            'idSalesOrder' => $idSalesOrder,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param array $formData
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    protected function createShipmentGroupTransfer(array $formData): ShipmentGroupTransfer
    {
        $shipmentGroupTransfer = new ShipmentGroupTransfer();
        $shipmentGroupTransfer = $this->addShipmentTransfer($shipmentGroupTransfer, $formData);
        $shipmentGroupTransfer = $this->addItems($shipmentGroupTransfer, $formData);

        return $shipmentGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param array $formData
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    protected function addShipmentTransfer(ShipmentGroupTransfer $shipmentGroupTransfer, array $formData): ShipmentGroupTransfer
    {
        $shipmentTransfer = (new ShipmentTransfer())->fromArray($formData, true);
        $shipmentMethodTransfer = $this
            ->getFactory()
            ->getShipmentFacade()
            ->findMethodById($formData[ShipmentFormCreate::FIELD_ID_SHIPMENT_METHOD]);
        $shipmentTransfer->setMethod($shipmentMethodTransfer);

        return $shipmentGroupTransfer->setShipment($shipmentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param array $formData
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    protected function addItems(ShipmentGroupTransfer $shipmentGroupTransfer, array $formData): ShipmentGroupTransfer
    {
        foreach ($formData[ShipmentFormCreate::FORM_SALES_ORDER_ITEMS] as $item) {
            if ($item['is_updated']) {
                $itemTransfer = (new ItemTransfer())
                    ->fromArray($item->toArray(), true)
                    ->setShipment($shipmentGroupTransfer->getShipment());
                $shipmentGroupTransfer->addItem($itemTransfer);
            }
        }

        return $shipmentGroupTransfer;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\SalesConfig;
use Spryker\Zed\ShipmentGui\Communication\Form\Item\ItemForm;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentFormCreate;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 */
class EditController extends AbstractController
{
    protected const PARAM_ID_SALES_ORDER = 'id-sales-order';
    protected const PARAM_ID_SALES_SHIPMENT = 'id-shipment';

    protected const REDIRECT_URL_DEFAULT = '/sales';
    protected const REDIRECT_URL = '/sales/detail';

    protected const MESSAGE_SHIPMENT_EDIT_SUCCESS = 'Shipment has been successfully created.';
    protected const MESSAGE_SHIPMENT_EDIT_ERROR = 'Shipment create failed.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idSalesOrder = $request->query->get(static::PARAM_ID_SALES_ORDER);
        $idSalesShipment = $request->query->get(static::PARAM_ID_SALES_SHIPMENT);
        $dataProvider = $this->getFactory()->createShipmentFormEditDataProvider();

        $form = $this->getFactory()
            ->createShipmentFormEdit(
                $dataProvider->getData($idSalesOrder, $idSalesShipment),
                $dataProvider->getOptions($idSalesOrder, $idSalesShipment)
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $orderTransfer = $this
                ->getFactory()
                ->getSalesFacade()
                ->findOrderByIdSalesOrder($idSalesOrder);

            if ($orderTransfer === null) {
                $this->addErrorMessage('Order does not exist');
                $redirectUrl = Url::generate(static::REDIRECT_URL_DEFAULT)->build();

                return $this->redirectResponse($redirectUrl);
            }

            $shipmentFormTransfer = $form->getData();
            $shipmentGroupTransfer = $this->getFactory()
                ->getShipmentFacade()
                ->createShipmentGroupTransfer($shipmentFormTransfer, $this->getItemListUpdatedStatus($form));

            $responseTransfer = $this->getFactory()
                ->getShipmentFacade()
                ->saveShipment($shipmentGroupTransfer, $orderTransfer);

            if ($responseTransfer->getIsSuccessful()) {
                $this->addSuccessMessage('Shipment has been updated successfully');
            }

            $redirectUrl = Url::generate(static::REDIRECT_URL, [
                SalesConfig::PARAM_ID_SALES_ORDER => $idSalesOrder,
            ])->build();

            return $this->redirectResponse($redirectUrl);
        }

        return $this->viewResponse([
            'idSalesOrder' => $idSalesOrder,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool[]
     */
    protected function getItemListUpdatedStatus(FormInterface $form): array
    {
        $requestedItems = [];
        foreach ($form->get(ShipmentFormCreate::FORM_SALES_ORDER_ITEMS) as $itemForm) {
            $itemTransfer = $itemForm->getData();
            /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
            $requestedItems[$itemTransfer->getIdSalesOrderItem()] = $itemForm->get(ItemForm::FIELD_IS_UPDATED)->getData();
        }

        return $requestedItems;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Controller;

use Generated\Shared\Transfer\ShipmentGroupResponseTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ShipmentGui\Communication\Form\Item\ItemFormType;
use Spryker\Zed\ShipmentGui\Communication\Form\Shipment\ShipmentGroupFormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 */
class CreateController extends AbstractController
{
    protected const PARAM_ID_SALES_ORDER = 'id-sales-order';

    protected const REDIRECT_URL_DEFAULT = '/sales/detail';

    protected const MESSAGE_SHIPMENT_CREATE_SUCCESS = 'Shipment has been successfully created.';
    protected const MESSAGE_SHIPMENT_CREATE_FAIL = 'Shipment has not been created.';
    protected const MESSAGE_ORDER_NOT_FOUND_ERROR = 'Sales order #%d not found.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idSalesOrder = $request->query->get(static::PARAM_ID_SALES_ORDER);

        $orderTransfer = $this->getFactory()
            ->getSalesFacade()
            ->findOrderByIdSalesOrder($idSalesOrder);

        if ($orderTransfer === null) {
            $this->addErrorMessage(static::MESSAGE_ORDER_NOT_FOUND_ERROR, ['%d' => $idSalesOrder]);
            $redirectUrl = Url::generate(static::REDIRECT_URL_DEFAULT)->build();

            return $this->redirectResponse($redirectUrl);
        }

        $dataProvider = $this->getFactory()->createShipmentFormDataProvider();
        $form = $this->getFactory()
            ->createShipmentCreateForm(
                $dataProvider->getData($orderTransfer, $this->createDefaultShipmentTransfer()),
                $dataProvider->getOptions($orderTransfer)
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shipmentGroupTransfer = $this->getFactory()
                ->getShipmentFacade()
                ->createShipmentGroupTransferWithListedItems($form->getData(), $this->getItemListUpdatedStatus($form));

            $responseTransfer = $this->getFactory()
                ->getShipmentFacade()
                ->saveShipment($shipmentGroupTransfer, $orderTransfer);

            $this->addStatusMessage($responseTransfer);

            $redirectUrl = Url::generate(
                static::REDIRECT_URL_DEFAULT,
                [static::PARAM_ID_SALES_ORDER => $idSalesOrder]
            )->build();

            return $this->redirectResponse($redirectUrl);
        }

        return $this->viewResponse([
            'idSalesOrder' => $idSalesOrder,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function createDefaultShipmentTransfer(): ShipmentTransfer
    {
        return new ShipmentTransfer();
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool[]
     */
    protected function getItemListUpdatedStatus(FormInterface $form): array
    {
        if (!$form->offsetExists(ShipmentGroupFormType::FIELD_SALES_ORDER_ITEMS_FORM)) {
            return [];
        }

        $itemFormTypeCollection = $form->get(ShipmentGroupFormType::FIELD_SALES_ORDER_ITEMS_FORM);
        $requestedItems = [];
        foreach ($itemFormTypeCollection as $itemFormType) {
            $itemTransfer = $itemFormType->getData();
            /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
            $requestedItems[$itemTransfer->getIdSalesOrderItem()] = $itemFormType->get(ItemFormType::FIELD_IS_UPDATED)->getData();
        }

        return $requestedItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function addStatusMessage(ShipmentGroupResponseTransfer $responseTransfer): void
    {
        if ($responseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_SHIPMENT_CREATE_SUCCESS);

            return;
        }

        $this->addErrorMessage(static::MESSAGE_SHIPMENT_CREATE_FAIL);
    }
}

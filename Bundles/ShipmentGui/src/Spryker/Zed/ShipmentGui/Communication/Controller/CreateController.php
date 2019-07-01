<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Controller;

use Generated\Shared\Transfer\ShipmentGroupResponseTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\SalesConfig;
use Spryker\Zed\ShipmentGui\Communication\Form\Item\ItemFormType;
use Spryker\Zed\ShipmentGui\Communication\Form\Shipment\ShipmentGroupFormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 */
class CreateController extends AbstractController
{
    public const PARAM_ID_SALES_ORDER = 'id-sales-order';

    protected const REDIRECT_URL_DEFAULT = '/sales/detail';

    protected const MESSAGE_SHIPMENT_CREATE_SUCCESS = 'Shipment has been successfully created.';
    protected const MESSAGE_SHIPMENT_CREATE_FAIL = 'Shipment has not been created.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idSalesOrder = $request->query->get(static::PARAM_ID_SALES_ORDER);

        $dataProvider = $this->getFactory()->createShipmentFormDataProvider();

        $form = $this->getFactory()
            ->createShipmentCreateForm(
                $dataProvider->getData($idSalesOrder),
                $dataProvider->getOptions($idSalesOrder, null)
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shipmentGroupTransfer = $this->getFactory()
                ->getShipmentFacade()
                ->createShipmentGroupTransferWithListedItems($form->getData(), $this->getItemListUpdatedStatus($form));

            $orderTransfer = $this
                ->getFactory()
                ->getSalesFacade()
                ->findOrderByIdSalesOrder($idSalesOrder);

            $responseTransfer = (new ShipmentGroupResponseTransfer())->setIsSuccessful(false);
            if ($orderTransfer !== null && $shipmentGroupTransfer !== null) {
                $responseTransfer = $this->getFactory()
                    ->getShipmentFacade()
                    ->saveShipment($shipmentGroupTransfer, $orderTransfer);
            }

            if ($responseTransfer->getIsSuccessful()) {
                $this->addSuccessMessage(static::MESSAGE_SHIPMENT_CREATE_SUCCESS);
            } else {
                $this->addErrorMessage(static::MESSAGE_SHIPMENT_CREATE_FAIL);
            }

            $redirectUrl = Url::generate(
                static::REDIRECT_URL_DEFAULT,
                [SalesConfig::PARAM_ID_SALES_ORDER => $idSalesOrder]
            )->build();

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
        if (!$form->offsetExists(ShipmentGroupFormType::FIELD_SALES_ORDER_ITEMS_FORM)) {
            return [];
        }

        $items = $form->get(ShipmentGroupFormType::FIELD_SALES_ORDER_ITEMS_FORM);
        $requestedItems = [];
        foreach ($items as $itemFormType) {
            $itemTransfer = $itemFormType->getData();
            /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
            $requestedItems[$itemTransfer->getIdSalesOrderItem()] = $itemFormType->get(ItemFormType::FIELD_IS_UPDATED)->getData();
        }

        return $requestedItems;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentGroupResponseTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Form\Shipment\MerchantShipmentGroupFormType;
use Spryker\Zed\ShipmentGui\Communication\Form\Item\ItemFormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * @method \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\MerchantSalesOrderMerchantUserGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Business\MerchantSalesOrderMerchantUserGuiFacadeInterface getFacade()
 */
class MerchantShipmentCreateController extends AbstractController
{
    protected const PARAM_ID_MERCHANT_SALES_ORDER = 'id-merchant-sales-order';

    protected const REDIRECT_URL_DEFAULT = '/merchant-sales-order-merchant-user-gui/detail';

    protected const MESSAGE_SHIPMENT_CREATE_SUCCESS = 'Shipment has been successfully created.';
    protected const MESSAGE_SHIPMENT_CREATE_FAIL = 'Shipment has not been created.';
    protected const MESSAGE_ORDER_NOT_FOUND_ERROR = 'Meerchant sales order #%d not found.';

    /**
     * @phpstan-return array<mixed>|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idMerchantSalesOrder = $request->query->getInt(static::PARAM_ID_MERCHANT_SALES_ORDER);
        $merchantUserTransfer = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser();

        $merchantOrderCriteriaTransfer = (new MerchantOrderCriteriaTransfer())
            ->setIdMerchantOrder($idMerchantSalesOrder)
            ->setWithItems(true)
            ->setWithOrder(true);

        $merchantOrderTransfer = $this->getFactory()
            ->getMerchantSalesOrderFacade()
            ->findMerchantOrder($merchantOrderCriteriaTransfer);

        if (!$merchantOrderTransfer) {
            $this->addErrorMessage(static::MESSAGE_ORDER_NOT_FOUND_ERROR, ['%d' => $idMerchantSalesOrder]);
            $redirectUrl = Url::generate(static::REDIRECT_URL_DEFAULT)->build();

            return $this->redirectResponse($redirectUrl);
        }

        if ($merchantUserTransfer->getMerchant()->getMerchantReference() !== $merchantOrderTransfer->getMerchantReference()) {
            throw new AccessDeniedHttpException('Access denied');
        }

        $merchantOrderTransfer = $this->getFactory()
            ->getMerchantOmsFacade()
            ->expandMerchantOrderItemsWithStateHistory($merchantOrderTransfer);

        $dataProvider = $this->getFactory()->createMerchantShipmentGroupFormDataProvider();
        $form = $this->getFactory()
            ->createMerchantShipmentGroupForm(
                $dataProvider->getData($merchantOrderTransfer, $this->createDefaultShipmentTransfer()),
                $dataProvider->getOptions($merchantOrderTransfer)
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shipmentGroupTransfer = $this->getFactory()
                ->getShipmentFacade()
                ->createShipmentGroupTransferWithListedItems($form->getData(), $this->getItemListUpdatedStatus($form));

            $responseTransfer = $this->getFactory()
                ->getShipmentFacade()
                ->saveShipment($shipmentGroupTransfer, $merchantOrderTransfer->getOrder());

            $this->addStatusMessage($responseTransfer);

            $redirectUrl = Url::generate(
                static::REDIRECT_URL_DEFAULT,
                [static::PARAM_ID_MERCHANT_SALES_ORDER => $idMerchantSalesOrder]
            )->build();

            return $this->redirectResponse($redirectUrl);
        }

        $merchantOrderItemsWithOrderItemIdKey = [];
        foreach ($merchantOrderTransfer->getMerchantOrderItems() as $merchantOrderItem) {
            $merchantOrderItemsWithOrderItemIdKey[$merchantOrderItem->getOrderItem()->getIdSalesOrderItem()] = $merchantOrderItem;
        }

        return $this->viewResponse([
            'idMerchantSalesOrder' => $idMerchantSalesOrder,
            'merchantOrder' => $merchantOrderTransfer,
            'merchantOrderItemsWithOrderItemIdKey' => $merchantOrderItemsWithOrderItemIdKey,
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
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $form
     *
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool[]
     */
    protected function getItemListUpdatedStatus(FormInterface $form): array
    {
        if (!$form->offsetExists(MerchantShipmentGroupFormType::FIELD_SALES_ORDER_ITEMS_FORM)) {
            return [];
        }

        $itemFormTypeCollection = $form->get(MerchantShipmentGroupFormType::FIELD_SALES_ORDER_ITEMS_FORM);
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

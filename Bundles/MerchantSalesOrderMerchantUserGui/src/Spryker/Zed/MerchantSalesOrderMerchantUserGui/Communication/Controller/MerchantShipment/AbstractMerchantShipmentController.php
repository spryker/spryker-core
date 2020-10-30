<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Controller\MerchantShipment;

use ArrayObject;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\MerchantShipmentCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentGroupResponseTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\MerchantSalesOrderMerchantUserGuiCommunicationFactory getFactory()
 */
abstract class AbstractMerchantShipmentController extends AbstractController
{
    protected const PARAM_ID_MERCHANT_SALES_ORDER = 'id-merchant-sales-order';
    protected const MESSAGE_MERCHANT_NOT_FOUND_ERROR = 'Merchant for current user not found.';

    /**
     * @uses \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Controller\DetailController::ROUTE_REDIRECT
     */
    protected const REDIRECT_URL_DEFAULT = '/merchant-sales-order-merchant-user-gui/detail';

    protected const MESSAGE_ORDER_NOT_FOUND_ERROR = 'Merchant sales order #%d not found.';

    /**
     * @uses \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Form\Shipment\MerchantShipmentGroupFormType::FIELD_SALES_ORDER_ITEMS_FORM
     */
    protected const FIELD_SALES_ORDER_ITEMS_FORM = 'items';

    /**
     * @uses \Spryker\Zed\ShipmentGui\Communication\Form\Item\ItemFormType::FIELD_IS_UPDATED
     */
    protected const FIELD_IS_UPDATED = 'is_updated';

    /**
     * @param int $idMerchantSalesOrder
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer|null
     */
    protected function findMerchantOrder(int $idMerchantSalesOrder): ?MerchantOrderTransfer
    {
        $merchantUserTransfer = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser();
        $merchantTransfer = $merchantUserTransfer->getMerchant();

        if (!$merchantTransfer) {
            return null;
        }

        $merchantOrderCriteriaTransfer = (new MerchantOrderCriteriaTransfer())
            ->setIdMerchantOrder($idMerchantSalesOrder)
            ->setMerchantReference($merchantTransfer->getMerchantReference())
            ->setWithItems(true)
            ->setWithOrder(true);

        $merchantOrderTransfer = $this->getFactory()
            ->getMerchantSalesOrderFacade()
            ->findMerchantOrder($merchantOrderCriteriaTransfer);

        if (!$merchantOrderTransfer) {
            return null;
        }

        $merchantOrderItemIds = $this->extractMerchantOrderItemIds($merchantOrderTransfer->getMerchantOrderItems());
        $merchantOrderItemsStateHistory = $this->getFactory()
            ->getMerchantOmsFacade()
            ->getMerchantOrderItemsStateHistory($merchantOrderItemIds);

        return $this->mapMerchantOrderItemsStateHistoryToMerchantOrderItems(
            $merchantOrderTransfer,
            $merchantOrderItemsStateHistory
        );
    }

    /**
     * @param string $merchantReference
     * @param int|null $idShipment
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer|null
     */
    protected function findShipment(string $merchantReference, ?int $idShipment = null): ?ShipmentTransfer
    {
        if (!$idShipment) {
            return new ShipmentTransfer();
        }

        $merchantShipmentCriteriaTransfer = (new MerchantShipmentCriteriaTransfer())
            ->setMerchantReference($merchantReference)
            ->setIdShipment($idShipment);

        return $this->getFactory()
            ->getMerchantShipmentFacade()
            ->findShipment($merchantShipmentCriteriaTransfer);
    }

    /**
     * @phpstan-return array<int|string, \Generated\Shared\Transfer\MerchantOrderItemTransfer>
     *
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return array
     */
    protected function groupMerchantOrderItemsByIdSalesOrderItem(MerchantOrderTransfer $merchantOrderTransfer): array
    {
        $groupedMerchantOrderItems = [];

        foreach ($merchantOrderTransfer->getMerchantOrderItems() as $merchantOrderItem) {
            $itemTransfer = $merchantOrderItem->getOrderItem();

            if (!$itemTransfer) {
                continue;
            }

            $groupedMerchantOrderItems[$itemTransfer->getIdSalesOrderItem()] = $merchantOrderItem;
        }

        return $groupedMerchantOrderItems;
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
        if (!$form->offsetExists(static::FIELD_SALES_ORDER_ITEMS_FORM)) {
            return [];
        }

        $itemFormTypeCollection = $form->get(static::FIELD_SALES_ORDER_ITEMS_FORM);
        $requestedItems = [];
        foreach ($itemFormTypeCollection as $itemFormType) {
            $itemTransfer = $itemFormType->getData();
            /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
            $requestedItems[$itemTransfer->getIdSalesOrderItem()] = $itemFormType->get(static::FIELD_IS_UPDATED)->getData();
        }

        return $requestedItems;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $form
     *
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupResponseTransfer
     */
    protected function saveMerchantOrderShipment(FormInterface $form, MerchantOrderTransfer $merchantOrderTransfer): ShipmentGroupResponseTransfer
    {
        $shipmentGroupTransfer = $this->getFactory()
            ->getShipmentFacade()
            ->createShipmentGroupTransferWithListedItems($form->getData(), $this->getItemListUpdatedStatus($form));

        $orderTransfer = $merchantOrderTransfer->getOrder();

        if (!$orderTransfer) {
            return (new ShipmentGroupResponseTransfer())->setIsSuccessful(false);
        }

        return $this->getFactory()
            ->getShipmentFacade()
            ->saveShipment($shipmentGroupTransfer, $orderTransfer);
    }

    /**
     * @phpstan-param \ArrayObject<int,\Generated\Shared\Transfer\MerchantOrderItemTransfer> $merchantOrderItems
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\MerchantOrderItemTransfer[] $merchantOrderItems
     *
     * @return int[]
     */
    protected function extractMerchantOrderItemIds(ArrayObject $merchantOrderItems): array
    {
        return array_map(
            function (MerchantOrderItemTransfer $merchantOrderItemTransfer) {
                return $merchantOrderItemTransfer->getIdMerchantOrderItem();
            },
            $merchantOrderItems->getArrayCopy()
        );
    }

    /**
     * @phpstan-param array<int, array<\Generated\Shared\Transfer\StateMachineItemTransfer>> $merchantOrderItemsStateHistory
     *
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     * @param array $merchantOrderItemsStateHistory
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    protected function mapMerchantOrderItemsStateHistoryToMerchantOrderItems(
        MerchantOrderTransfer $merchantOrderTransfer,
        array $merchantOrderItemsStateHistory
    ): MerchantOrderTransfer {
        foreach ($merchantOrderTransfer->getMerchantOrderItems() as $merchantOrderItemTransfer) {
            if (!isset($merchantOrderItemsStateHistory[$merchantOrderItemTransfer->getIdMerchantOrderItem()])) {
                continue;
            }

            $merchantOrderItemTransfer->setStateHistory(
                new ArrayObject($merchantOrderItemsStateHistory[$merchantOrderItemTransfer->getIdMerchantOrderItem()])
            );
        }

        return $merchantOrderTransfer;
    }
}

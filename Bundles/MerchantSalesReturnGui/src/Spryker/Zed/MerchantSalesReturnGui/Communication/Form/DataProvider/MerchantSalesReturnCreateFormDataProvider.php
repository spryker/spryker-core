<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnGui\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Spryker\Zed\MerchantSalesReturnGui\Communication\Form\MerchantOrderReturnCreateSubForm;
use Spryker\Zed\MerchantSalesReturnGui\Communication\Form\MerchantSalesReturnCreateForm;
use Spryker\Zed\MerchantSalesReturnGui\Dependency\Facade\MerchantSalesReturnGuiToMerchantSalesOrderFacadeInterface;

class MerchantSalesReturnCreateFormDataProvider
{
    /**
     * @var string
     */
    public const MERCHANT_ORDER_DATA_ORDER_ITEM_KEY = ReturnItemTransfer::ORDER_ITEM;

    /**
     * @var string
     */
    protected const MERCHANT_ORDER_DATA_MERCHANT_KEY = MerchantOrderTransfer::MERCHANT;

    /**
     * @var string
     */
    protected const MERCHANT_ORDER_DATA_ORDER_KEY = MerchantOrderTransfer::ORDER;

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Form\ReturnCreateForm::FIELD_RETURN_ITEMS
     *
     * @var string
     */
    protected const FIELD_RETURN_ITEMS = 'returnItems';

    /**
     * @var \Spryker\Zed\MerchantSalesReturnGui\Dependency\Facade\MerchantSalesReturnGuiToMerchantSalesOrderFacadeInterface
     */
    protected MerchantSalesReturnGuiToMerchantSalesOrderFacadeInterface $merchantSalesOrderFacade;

    /**
     * @param \Spryker\Zed\MerchantSalesReturnGui\Dependency\Facade\MerchantSalesReturnGuiToMerchantSalesOrderFacadeInterface $merchantSalesOrderFacade
     */
    public function __construct(MerchantSalesReturnGuiToMerchantSalesOrderFacadeInterface $merchantSalesOrderFacade)
    {
        $this->merchantSalesOrderFacade = $merchantSalesOrderFacade;
    }

    /**
     * @param array<string, mixed> $returnCreateFormData
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array<string, mixed>
     */
    public function getData(array $returnCreateFormData, OrderTransfer $orderTransfer): array
    {
        $returnCreateFormData = $this->excludeMerchantOrderItems($returnCreateFormData);

        return $this->addMerchantOrderItems($returnCreateFormData, $orderTransfer);
    }

    /**
     * @param array<string, mixed> $returnCreateFormData
     *
     * @return array<string, mixed>
     */
    protected function excludeMerchantOrderItems(array $returnCreateFormData): array
    {
        $filteredReturnItems = [];
        $returnItems = $returnCreateFormData[static::FIELD_RETURN_ITEMS] ?? [];

        foreach ($returnItems as $returnItem) {
            /** @var \Generated\Shared\Transfer\ItemTransfer|null $itemTransfer */
            $itemTransfer = $returnItem[static::MERCHANT_ORDER_DATA_ORDER_ITEM_KEY] ?? null;

            if (!$itemTransfer) {
                continue;
            }

            if (!$itemTransfer->getMerchantOrderReference()) {
                $filteredReturnItems[] = [static::MERCHANT_ORDER_DATA_ORDER_ITEM_KEY => $itemTransfer];
            }
        }

        $returnCreateFormData[static::FIELD_RETURN_ITEMS] = $filteredReturnItems;

        return $returnCreateFormData;
    }

    /**
     * @param array<string, mixed> $returnCreateFormData
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array<string, mixed>
     */
    protected function addMerchantOrderItems(array $returnCreateFormData, OrderTransfer $orderTransfer): array
    {
        $merchantOrdersReferences = $this->getMerchantOrdersReferences($orderTransfer->getItems());
        if (!$merchantOrdersReferences) {
            return $returnCreateFormData;
        }
        $returnCreateFormData[MerchantSalesReturnCreateForm::FIELD_RETURN_MERCHANT_ORDERS] =
            $this->getReturnMerchantOrdersData($merchantOrdersReferences);

        return $returnCreateFormData;
    }

    /**
     * @param list<string> $merchantOrdersReferences
     *
     * @return array<string, array<string, mixed>>
     */
    protected function getReturnMerchantOrdersData(array $merchantOrdersReferences): array
    {
        $returnMerchantOrdersData = [];
        $merchantOrderCriteriaTransfer = $this->createMerchantOrderCriteriaTransfer($merchantOrdersReferences);
        $merchantOrderCollectionTransfer = $this->merchantSalesOrderFacade->getMerchantOrderCollection(
            $merchantOrderCriteriaTransfer,
        );

        if (!$merchantOrderCollectionTransfer->getMerchantOrders()->count()) {
            return $returnMerchantOrdersData;
        }

        foreach ($merchantOrderCollectionTransfer->getMerchantOrders() as $merchantOrderTransfer) {
            $returnMerchantOrdersData[$merchantOrderTransfer->getMerchantOrderReference()] =
                $this->getMerchantOrderData($merchantOrderTransfer);
        }

        return $returnMerchantOrdersData;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<string>
     */
    public function getMerchantOrdersReferences(ArrayObject $itemTransfers): array
    {
        $merchantOrdersReferences = [];

        foreach ($itemTransfers as $itemTransfer) {
            if (!$itemTransfer->getMerchantOrderReference()) {
                continue;
            }
            $merchantOrdersReferences[] = $itemTransfer->getMerchantOrderReference();
        }

        return array_unique($merchantOrdersReferences);
    }

    /**
     * @param list<string> $merchantOrderReferences
     *
     * @return \Generated\Shared\Transfer\MerchantOrderCriteriaTransfer
     */
    protected function createMerchantOrderCriteriaTransfer(array $merchantOrderReferences): MerchantOrderCriteriaTransfer
    {
        return (new MerchantOrderCriteriaTransfer())
            ->setMerchantOrderReferences($merchantOrderReferences)
            ->setWithMerchant(true)
            ->setWithOrder(true)
            ->setWithItems(true);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return array<string, mixed>
     */
    protected function getMerchantOrderData(MerchantOrderTransfer $merchantOrderTransfer): array
    {
        $itemTransfers = $this->getItemTransfers($merchantOrderTransfer->getMerchantOrderItems());

        return [
            static::MERCHANT_ORDER_DATA_MERCHANT_KEY => $merchantOrderTransfer->getMerchant(),
            static::MERCHANT_ORDER_DATA_ORDER_KEY => $merchantOrderTransfer->getOrder(),
            MerchantOrderReturnCreateSubForm::FIELD_RETURN_MERCHANT_ORDER_ITEMS => $itemTransfers,
        ];
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\MerchantOrderItemTransfer> $merchantOrderItemTransfers
     *
     * @return list<array<string, \Generated\Shared\Transfer\ItemTransfer>>
     */
    protected function getItemTransfers(ArrayObject $merchantOrderItemTransfers): array
    {
        $itemTransfers = [];
        foreach ($merchantOrderItemTransfers as $merchantOrderItemTransfer) {
            $itemTransfers[] = [static::MERCHANT_ORDER_DATA_ORDER_ITEM_KEY => $merchantOrderItemTransfer->getOrderItemOrFail()];
        }

        return $itemTransfers;
    }
}

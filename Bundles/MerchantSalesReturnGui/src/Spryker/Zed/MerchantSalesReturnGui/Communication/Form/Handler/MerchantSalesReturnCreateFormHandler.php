<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnGui\Communication\Form\Handler;

use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Spryker\Zed\MerchantSalesReturnGui\Communication\Form\DataProvider\MerchantSalesReturnCreateFormDataProvider;
use Spryker\Zed\MerchantSalesReturnGui\Communication\Form\MerchantOrderItemsReturnCreateSubForm;
use Spryker\Zed\MerchantSalesReturnGui\Communication\Form\MerchantOrderReturnCreateSubForm;
use Spryker\Zed\MerchantSalesReturnGui\Communication\Form\MerchantSalesReturnCreateForm;

class MerchantSalesReturnCreateFormHandler implements MerchantSalesReturnCreateFormHandlerInterface
{
    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Form\DataProvider\ReturnCreateFormDataProvider::CUSTOM_REASON_KEY
     *
     * @var string
     */
    protected const CUSTOM_REASON_KEY = 'custom_reason';

    /**
     * @param array<string, mixed> $returnCreateFormData
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCreateRequestTransfer
     */
    public function handle(
        array $returnCreateFormData,
        ReturnCreateRequestTransfer $returnCreateRequestTransfer
    ): ReturnCreateRequestTransfer {
        $returnMerchantOrdersFormData = $returnCreateFormData[MerchantSalesReturnCreateForm::FIELD_RETURN_MERCHANT_ORDERS] ?? [];

        foreach ($returnMerchantOrdersFormData as $returnMerchantOrderFormData) {
            $returnMerchantOrderItemsData = $returnMerchantOrderFormData[MerchantOrderReturnCreateSubForm::FIELD_RETURN_MERCHANT_ORDER_ITEMS] ?? [];

            $returnCreateRequestTransfer = $this->appendReturnCreateRequestTransfer(
                $returnMerchantOrderItemsData,
                $returnCreateRequestTransfer,
            );
        }

        return $returnCreateRequestTransfer;
    }

    /**
     * @param array<string, mixed> $returnMerchantOrderItemsData
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCreateRequestTransfer
     */
    protected function appendReturnCreateRequestTransfer(
        array $returnMerchantOrderItemsData,
        ReturnCreateRequestTransfer $returnCreateRequestTransfer
    ): ReturnCreateRequestTransfer {
        foreach ($returnMerchantOrderItemsData as $returnMerchantOrderItemData) {
            if (!$this->isOrderItemToReturn($returnMerchantOrderItemData)) {
                continue;
            }
            $returnCreateRequestTransfer = $this->addMerchantOrderItemToRequest(
                $returnMerchantOrderItemData,
                $returnCreateRequestTransfer,
            );
        }

        return $returnCreateRequestTransfer;
    }

    /**
     * @param array<string, mixed> $returnMerchantOrderItemData
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCreateRequestTransfer
     */
    protected function addMerchantOrderItemToRequest(
        array $returnMerchantOrderItemData,
        ReturnCreateRequestTransfer $returnCreateRequestTransfer
    ): ReturnCreateRequestTransfer {
        $returnReason = $this->extractReason($returnMerchantOrderItemData);
        $returnItemTransfer = (new ReturnItemTransfer())
            ->setOrderItem($returnMerchantOrderItemData[MerchantSalesReturnCreateFormDataProvider::MERCHANT_ORDER_DATA_ORDER_ITEM_KEY])
            ->setReason($returnReason);

        $returnCreateRequestTransfer->addReturnItem($returnItemTransfer);

        return $returnCreateRequestTransfer;
    }

    /**
     * @param array<string, mixed> $returnMerchantOrderItemData
     *
     * @return string|null
     */
    protected function extractReason(array $returnMerchantOrderItemData): ?string
    {
        if (isset($returnMerchantOrderItemData[MerchantOrderItemsReturnCreateSubForm::FIELD_REASON])) {
            return null;
        }

        if (
            $returnMerchantOrderItemData[MerchantOrderItemsReturnCreateSubForm::FIELD_REASON] === static::CUSTOM_REASON_KEY &&
            $returnMerchantOrderItemData[MerchantOrderItemsReturnCreateSubForm::FIELD_CUSTOM_REASON]
        ) {
            return $returnMerchantOrderItemData[MerchantOrderItemsReturnCreateSubForm::FIELD_CUSTOM_REASON];
        }

        return $returnMerchantOrderItemData[MerchantOrderItemsReturnCreateSubForm::FIELD_REASON];
    }

    /**
     * @param array<string, mixed> $returnMerchantOrderItemData
     *
     * @return bool
     */
    protected function isOrderItemToReturn(array $returnMerchantOrderItemData): bool
    {
        if (
            !isset($returnMerchantOrderItemData[MerchantSalesReturnCreateFormDataProvider::MERCHANT_ORDER_DATA_ORDER_ITEM_KEY]) ||
            !isset($returnMerchantOrderItemData[MerchantOrderItemsReturnCreateSubForm::FIELD_IS_RETURNABLE])
        ) {
            return false;
        }
        $itemTransfer = $returnMerchantOrderItemData[MerchantSalesReturnCreateFormDataProvider::MERCHANT_ORDER_DATA_ORDER_ITEM_KEY];

        return $itemTransfer->getIsReturnable() &&
            $returnMerchantOrderItemData[MerchantOrderItemsReturnCreateSubForm::FIELD_IS_RETURNABLE];
    }
}

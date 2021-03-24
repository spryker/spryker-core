<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToGlossaryFacadeInterface;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToSalesReturnFacadeInterface;
use Spryker\Zed\SalesReturnGui\Communication\Form\ReturnCreateForm;

class ReturnCreateFormDataProvider
{
    public const CUSTOM_REASON = 'Custom reason';
    public const CUSTOM_REASON_KEY = 'custom_reason';

    /**
     * @var \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToSalesReturnFacadeInterface
     */
    protected $salesReturnFacade;

    /**
     * @var \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToSalesReturnFacadeInterface $salesReturnFacade
     * @param \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(
        MerchantSalesReturnMerchantUserGuiToSalesReturnFacadeInterface $salesReturnFacade,
        MerchantSalesReturnMerchantUserGuiToGlossaryFacadeInterface $glossaryFacade
    ) {
        $this->salesReturnFacade = $salesReturnFacade;
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function getData(OrderTransfer $orderTransfer): array
    {
        $orderTransfer = $this->translateReturnPolicyMessages($orderTransfer);

        return [
            ReturnCreateForm::FIELD_RETURN_ITEMS => $this->mapReturnItemTransfers($orderTransfer),
        ];
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            ReturnCreateForm::OPTION_RETURN_REASONS => $this->prepareReturnReasonChoices(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function mapReturnItemTransfers(OrderTransfer $orderTransfer): array
    {
        $returnItemTransfers = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $returnItemTransfers[] = [ReturnItemTransfer::ORDER_ITEM => $itemTransfer];
        }

        return $returnItemTransfers;
    }

    /**
     * @return string[]
     */
    protected function prepareReturnReasonChoices(): array
    {
        $returnReasonChoices = [];
        $returnReasonTransfers = $this->salesReturnFacade
            ->getReturnReasons(new ReturnReasonFilterTransfer())
            ->getReturnReasons();

        foreach ($returnReasonTransfers as $returnReasonTransfer) {
            $returnReason = $this->glossaryFacade->translate($returnReasonTransfer->getGlossaryKeyReason());

            $returnReasonChoices[$returnReason] = $returnReason;
        }

        $returnReasonChoices[static::CUSTOM_REASON] = static::CUSTOM_REASON_KEY;

        return $returnReasonChoices;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function translateReturnPolicyMessages(OrderTransfer $orderTransfer): OrderTransfer
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $this->translateReturnPolicyMessage($itemTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function translateReturnPolicyMessage(ItemTransfer $itemTransfer): ItemTransfer
    {
        if (!$itemTransfer->getReturnPolicyMessages()->count()) {
            return $itemTransfer;
        }

        foreach ($itemTransfer->getReturnPolicyMessages() as $returnPolicyMessage) {
            if (!$returnPolicyMessage->getValue()) {
                continue;
            }

            $translatedMessage = $this->glossaryFacade->translate(
                $returnPolicyMessage->getValue(),
                $returnPolicyMessage->getParameters()
            );

            $returnPolicyMessage->setMessage($translatedMessage);
        }

        return $itemTransfer;
    }
}

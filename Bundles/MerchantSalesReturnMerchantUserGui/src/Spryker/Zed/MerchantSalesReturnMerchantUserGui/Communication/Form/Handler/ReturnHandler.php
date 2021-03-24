<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\Handler;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\DataProvider\ReturnCreateFormDataProvider;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\ReturnCreateForm;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\ReturnCreateItemsSubForm;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToSalesReturnFacadeInterface;

class ReturnHandler implements ReturnHandlerInterface
{
    /**
     * @var \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToSalesReturnFacadeInterface
     */
    protected $salesReturnFacade;

    /**
     * @param \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToSalesReturnFacadeInterface $salesReturnFacade
     */
    public function __construct(
        MerchantSalesReturnMerchantUserGuiToSalesReturnFacadeInterface $salesReturnFacade
    ) {
        $this->salesReturnFacade = $salesReturnFacade;
    }

    /**
     * @param array $returnCreateFormData
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function createReturn(array $returnCreateFormData, OrderTransfer $orderTransfer): ReturnResponseTransfer
    {
        $returnCreateRequestTransfer = $this->buildReturnCreateRequestTransfer($returnCreateFormData, $orderTransfer);

        if ($returnCreateRequestTransfer->getReturnItems()->count()) {
            return $this->salesReturnFacade->createReturn($returnCreateRequestTransfer);
        }

        return (new ReturnResponseTransfer())
            ->setIsSuccessful(false);
    }

    /**
     * @param array $returnCreateFormData
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCreateRequestTransfer
     */
    protected function buildReturnCreateRequestTransfer(array $returnCreateFormData, OrderTransfer $orderTransfer): ReturnCreateRequestTransfer
    {
        $returnCreateRequestTransfer = (new ReturnCreateRequestTransfer())
            ->setCustomer($orderTransfer->getCustomer())
            ->setStore($orderTransfer->getStore())
            ->setReturnItems(new ArrayObject());

        $returnItemsFormData = $returnCreateFormData[ReturnCreateForm::FIELD_RETURN_ITEMS] ?? [];

        foreach ($returnItemsFormData as $returnItemFormData) {
            if (!$this->isReturnItemChecked($returnItemFormData)) {
                continue;
            }

            $returnItemTransfer = (new ReturnItemTransfer())
                ->fromArray($returnItemFormData, true)
                ->setReason($this->extractReason($returnItemFormData));

            $returnCreateRequestTransfer->addReturnItem($returnItemTransfer);
        }

        return $returnCreateRequestTransfer;
    }

    /**
     * @param array $returnItemFormData
     *
     * @return string|null
     */
    protected function extractReason(array $returnItemFormData): ?string
    {
        if ($returnItemFormData[ReturnItemTransfer::REASON] === ReturnCreateFormDataProvider::CUSTOM_REASON_KEY && $returnItemFormData[ReturnCreateItemsSubForm::FIELD_CUSTOM_REASON]) {
            return $returnItemFormData[ReturnCreateItemsSubForm::FIELD_CUSTOM_REASON];
        }

        return $returnItemFormData[ReturnItemTransfer::REASON];
    }

    /**
     * @param array $returnItemFormData
     *
     * @return bool
     */
    protected function isReturnItemChecked(array $returnItemFormData): bool
    {
        return !empty($returnItemFormData[ItemTransfer::IS_RETURNABLE]);
    }
}

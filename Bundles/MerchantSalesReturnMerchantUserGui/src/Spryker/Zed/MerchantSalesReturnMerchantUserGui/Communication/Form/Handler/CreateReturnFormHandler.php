<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\Handler;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToSalesReturnFacadeInterface;
use Symfony\Component\Form\FormInterface;

class CreateReturnFormHandler implements CreateReturnFormHandlerInterface
{
    /**
     * @uses \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\ReturnCreateItemsSubForm::FIELD_CUSTOM_REASON
     */
    protected const FIELD_CUSTOM_REASON = 'customReason';

    /**
     * @uses \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\ReturnCreateForm::FIELD_RETURN_ITEMS
     */
    protected const FIELD_RETURN_ITEMS = 'returnItems';

    /**
     * @uses \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\DataProvider\ReturnCreateFormDataProvider::CUSTOM_REASON_KEY
     */
    protected const CUSTOM_REASON_KEY = 'custom_reason';

    /**
     * @var \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToSalesReturnFacadeInterface
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
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $returnCreateForm
     *
     * @param \Symfony\Component\Form\FormInterface $returnCreateForm
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function handleForm(FormInterface $returnCreateForm, OrderTransfer $orderTransfer): ReturnResponseTransfer
    {
        $returnCreateFormData = $returnCreateForm->getData();
        $returnCreateRequestTransfer = $this->buildReturnCreateRequestTransfer($returnCreateFormData, $orderTransfer);

        if ($returnCreateRequestTransfer->getReturnItems()->count()) {
            return $this->salesReturnFacade->createReturn($returnCreateRequestTransfer);
        }

        $returnResponseTransfer = (new ReturnResponseTransfer())
            ->setIsSuccessful(false);

        return $returnResponseTransfer;
    }

    /**
     * @phpstan-param array<string, mixed> $returnCreateFormData
     *
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

        $returnItemsFormData = $returnCreateFormData[static::FIELD_RETURN_ITEMS] ?? [];

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
     * @phpstan-param array<string, mixed> $returnItemFormData
     *
     * @param array $returnItemFormData
     *
     * @return string|null
     */
    protected function extractReason(array $returnItemFormData): ?string
    {
        if ($returnItemFormData[ReturnItemTransfer::REASON] === static::CUSTOM_REASON_KEY && $returnItemFormData[static::FIELD_CUSTOM_REASON]) {
            return $returnItemFormData[static::FIELD_CUSTOM_REASON];
        }

        return $returnItemFormData[ReturnItemTransfer::REASON];
    }

    /**
     * @phpstan-param array<string, mixed> $returnItemFormData
     *
     * @param array $returnItemFormData
     *
     * @return bool
     */
    protected function isReturnItemChecked(array $returnItemFormData): bool
    {
        return !empty($returnItemFormData[ItemTransfer::IS_RETURNABLE]);
    }
}

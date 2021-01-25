<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication\Form\Handler;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;
use Spryker\Zed\SalesReturnGui\Communication\Form\DataProvider\ReturnCreateFormDataProvider;
use Spryker\Zed\SalesReturnGui\Communication\Form\ReturnCreateForm;
use Spryker\Zed\SalesReturnGui\Communication\Form\ReturnCreateItemsSubForm;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToSalesReturnFacadeInterface;

class ReturnHandler implements ReturnHandlerInterface
{
    /**
     * @var \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToSalesReturnFacadeInterface
     */
    protected $salesReturnFacade;

    /**
     * @var \Spryker\Zed\SalesReturnGuiExtension\Dependency\Plugin\ReturnCreateFormHandlerPluginInterface[]
     */
    protected $returnCreateFormHandlerPlugins;

    /**
     * @param \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToSalesReturnFacadeInterface $salesReturnFacade
     * @param \Spryker\Zed\SalesReturnGuiExtension\Dependency\Plugin\ReturnCreateFormHandlerPluginInterface[] $returnCreateFormHandlerPlugins
     */
    public function __construct(
        SalesReturnGuiToSalesReturnFacadeInterface $salesReturnFacade,
        array $returnCreateFormHandlerPlugins
    ) {
        $this->salesReturnFacade = $salesReturnFacade;
        $this->returnCreateFormHandlerPlugins = $returnCreateFormHandlerPlugins;
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
        $returnCreateRequestTransfer = $this->executeReturnCreateFormExpanderPlugins($returnCreateFormData, $returnCreateRequestTransfer);

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

    /**
     * @param array $returnCreateFormData
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCreateRequestTransfer
     */
    protected function executeReturnCreateFormExpanderPlugins(
        array $returnCreateFormData,
        ReturnCreateRequestTransfer $returnCreateRequestTransfer
    ): ReturnCreateRequestTransfer {
        foreach ($this->returnCreateFormHandlerPlugins as $returnCreateFormHandlerPlugin) {
            $returnCreateRequestTransfer = $returnCreateFormHandlerPlugin->handle($returnCreateFormData, $returnCreateRequestTransfer);
        }

        return $returnCreateRequestTransfer;
    }
}

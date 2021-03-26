<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;
use Spryker\Zed\SalesReturnGui\Communication\Form\ReturnCreateForm;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToGlossaryFacadeInterface;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToSalesReturnFacadeInterface;

class ReturnCreateFormDataProvider
{
    public const CUSTOM_REASON = 'Custom reason';
    public const CUSTOM_REASON_KEY = 'custom_reason';

    /**
     * @var \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToSalesReturnFacadeInterface
     */
    protected $salesReturnFacade;

    /**
     * @var \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\SalesReturnGuiExtension\Dependency\Plugin\ReturnCreateFormHandlerPluginInterface[]
     */
    protected $returnCreateFormHandlerPlugins;

    /**
     * @param \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToSalesReturnFacadeInterface $salesReturnFacade
     * @param \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\SalesReturnGuiExtension\Dependency\Plugin\ReturnCreateFormHandlerPluginInterface[] $returnCreateFormHandlerPlugins
     */
    public function __construct(
        SalesReturnGuiToSalesReturnFacadeInterface $salesReturnFacade,
        SalesReturnGuiToGlossaryFacadeInterface $glossaryFacade,
        array $returnCreateFormHandlerPlugins
    ) {
        $this->salesReturnFacade = $salesReturnFacade;
        $this->glossaryFacade = $glossaryFacade;
        $this->returnCreateFormHandlerPlugins = $returnCreateFormHandlerPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function getData(OrderTransfer $orderTransfer): array
    {
        $orderTransfer = $this->translateReturnPolicyMessages($orderTransfer);

        $returnCreateFormData = [
            ReturnCreateForm::FIELD_RETURN_ITEMS => $this->mapReturnItemTransfers($orderTransfer),
        ];

        return $this->executeReturnCreateFormExpanderPlugins($returnCreateFormData, $orderTransfer);
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
            $returnReason = $this->glossaryFacade->translate($returnReasonTransfer->getGlossaryKeyReasonOrFail());

            $returnReasonChoices[$returnReason] = $returnReason;
        }

        $returnReasonChoices[static::CUSTOM_REASON] = static::CUSTOM_REASON_KEY;

        return $returnReasonChoices;
    }

    /**
     * @param array $returnCreateFormData
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function executeReturnCreateFormExpanderPlugins(array $returnCreateFormData, OrderTransfer $orderTransfer): array
    {
        foreach ($this->returnCreateFormHandlerPlugins as $returnCreateFormHandlerPlugin) {
            $returnCreateFormData = $returnCreateFormHandlerPlugin->expandData($returnCreateFormData, $orderTransfer);
        }

        return $returnCreateFormData;
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
                $returnPolicyMessage->getValueOrFail(),
                $returnPolicyMessage->getParameters()
            );

            $returnPolicyMessage->setMessage($translatedMessage);
        }

        return $itemTransfer;
    }
}

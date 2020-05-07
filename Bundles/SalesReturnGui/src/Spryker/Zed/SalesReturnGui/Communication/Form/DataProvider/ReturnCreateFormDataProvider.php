<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;
use Spryker\Zed\SalesReturnGui\Communication\Form\ReturnCreateForm;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToGlossaryFacadeInterface;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToSalesReturnFacadeInterface;

class ReturnCreateFormDataProvider
{
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
     * @param \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToSalesReturnFacadeInterface $salesReturnFacade
     * @param \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(
        SalesReturnGuiToSalesReturnFacadeInterface $salesReturnFacade,
        SalesReturnGuiToGlossaryFacadeInterface $glossaryFacade
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
        return [
            ReturnCreateForm::FIELD_RETURN_ITEMS => $this->createReturnItemTransfersCollection($orderTransfer),
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
    protected function createReturnItemTransfersCollection(OrderTransfer $orderTransfer): array
    {
        $returnItemTransfers = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $returnItemTransfers[] = [ReturnItemTransfer::ORDER_ITEM => $itemTransfer];
        }

        return $returnItemTransfers;
    }

    /**
     * @return array
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

        $returnReasonChoices['Custom reason'] = static::CUSTOM_REASON_KEY;

        return $returnReasonChoices;
    }
}

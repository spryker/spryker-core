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
     * @param \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToSalesReturnFacadeInterface $salesReturnFacade
     */
    public function __construct(SalesReturnGuiToSalesReturnFacadeInterface $salesReturnFacade)
    {
        $this->salesReturnFacade = $salesReturnFacade;
    }

    /**
     * @param array $returnItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function createReturn(array $returnItems, OrderTransfer $orderTransfer): ReturnResponseTransfer
    {
        $returnItemData = isset($returnItems[ReturnCreateForm::FIELD_RETURN_ITEMS])
            ? $returnItems[ReturnCreateForm::FIELD_RETURN_ITEMS]
            : [];

        $returnCreateRequestTransfer = (new ReturnCreateRequestTransfer())
            ->setCustomer($orderTransfer->getCustomer())
            ->setStore($orderTransfer->getStore())
            ->setReturnItems(new ArrayObject());

        foreach ($returnItemData as $returnItem) {
            if (!isset($returnItem[ItemTransfer::IS_RETURNABLE]) || !$returnItem[ItemTransfer::IS_RETURNABLE]) {
                continue;
            }

            $returnItemTransfer = (new ReturnItemTransfer())->fromArray($returnItem, true);

            if ($returnItem[ReturnItemTransfer::REASON] === ReturnCreateFormDataProvider::CUSTOM_REASON_KEY && $returnItem[ReturnCreateItemsSubForm::FIELD_CUSTOM_REASON]) {
                $returnItemTransfer->setReason($returnItem[ReturnCreateItemsSubForm::FIELD_CUSTOM_REASON]);
            }

            $returnCreateRequestTransfer->addReturnItem($returnItemTransfer);
        }

        if (!$returnCreateRequestTransfer->getReturnItems()->count()) {
            return (new ReturnResponseTransfer())->setIsSuccessful(false);
        }

        return $this->salesReturnFacade->createReturn($returnCreateRequestTransfer);
    }
}

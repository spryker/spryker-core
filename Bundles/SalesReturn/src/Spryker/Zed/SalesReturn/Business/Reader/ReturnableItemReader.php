<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Generated\Shared\Transfer\ReturnableItemFilterTransfer;
use Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToSalesFacadeInterface;
use Spryker\Zed\SalesReturn\SalesReturnConfig;

class ReturnableItemReader implements ReturnableItemReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @var \Spryker\Zed\SalesReturn\SalesReturnConfig
     */
    protected $salesReturnConfig;

    /**
     * @param \Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\SalesReturn\SalesReturnConfig $salesReturnConfig
     */
    public function __construct(
        SalesReturnToSalesFacadeInterface $salesFacade,
        SalesReturnConfig $salesReturnConfig
    ) {
        $this->salesFacade = $salesFacade;
        $this->salesReturnConfig = $salesReturnConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnableItemFilterTransfer $returnableItemFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function getReturnableItems(ReturnableItemFilterTransfer $returnableItemFilterTransfer): ItemCollectionTransfer
    {
        $returnableItemFilterTransfer->requireCustomerReference();
        $orderItemFilterTransfer = $this->createOrderItemFilter($returnableItemFilterTransfer);

        $itemTransfers = $this->salesFacade
            ->getOrderItems($orderItemFilterTransfer)
            ->getItems();

        $itemTransfers = $this->excludeNonReturnableItems($itemTransfers);

        return (new ItemCollectionTransfer())
            ->setItems($itemTransfers);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function excludeNonReturnableItems(ArrayObject $itemTransfers): ArrayObject
    {
        $returnableItemTransfers = [];

        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getIsReturnable()) {
                $returnableItemTransfers[] = $itemTransfer;
            }
        }

        return new ArrayObject($returnableItemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnableItemFilterTransfer $returnableItemFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OrderItemFilterTransfer
     */
    protected function createOrderItemFilter(ReturnableItemFilterTransfer $returnableItemFilterTransfer): OrderItemFilterTransfer
    {
        return (new OrderItemFilterTransfer())
            ->fromArray($returnableItemFilterTransfer->toArray(), true)
            ->setItemStates($this->salesReturnConfig->getReturnableStateNames());
    }
}

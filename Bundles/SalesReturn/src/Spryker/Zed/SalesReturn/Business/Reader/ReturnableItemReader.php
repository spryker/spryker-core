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
use Spryker\Zed\SalesReturn\Business\Checker\OrderItemCheckerInterface;
use Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToSalesFacadeInterface;

class ReturnableItemReader implements ReturnableItemReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @var \Spryker\Zed\SalesReturn\Business\Checker\OrderItemCheckerInterface
     */
    protected $orderItemChecker;

    /**
     * @param \Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\SalesReturn\Business\Checker\OrderItemCheckerInterface $orderItemChecker
     */
    public function __construct(
        SalesReturnToSalesFacadeInterface $salesFacade,
        OrderItemCheckerInterface $orderItemChecker
    ) {
        $this->salesFacade = $salesFacade;
        $this->orderItemChecker = $orderItemChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnableItemFilterTransfer $returnableItemFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function getReturnableItems(ReturnableItemFilterTransfer $returnableItemFilterTransfer): ItemCollectionTransfer
    {
        $returnableItemFilterTransfer->requireCustomerReference();

        $orderItemFilterTransfer = (new OrderItemFilterTransfer())
            ->fromArray($returnableItemFilterTransfer->toArray(), true);

        $itemTransfers = $this->salesFacade
            ->getOrderItems($orderItemFilterTransfer)
            ->getItems();

        $itemTransfers = $this->extractNonReturnableItems($itemTransfers);

        // TODO: execute ReturnPolicyPluginInterface plugin stack.

        return (new ItemCollectionTransfer())
            ->setItems(new ArrayObject($itemTransfers));
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function extractNonReturnableItems(ArrayObject $itemTransfers): array
    {
        $returnableItemTransfers = [];

        foreach ($itemTransfers as $itemTransfer) {
            if ($this->orderItemChecker->isOrderItemInReturnableStates($itemTransfer)) {
                $returnableItemTransfers[] = $itemTransfer;
            }
        }

        return $returnableItemTransfers;
    }
}

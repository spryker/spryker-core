<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Validator;

use Generated\Shared\Transfer\PickingStartedRequestTransfer;
use Generated\Shared\Transfer\PickingStartedResponseTransfer;
use Spryker\Zed\PickingList\Business\Reader\PickingListReaderInterface;
use Spryker\Zed\PickingList\PickingListConfig;

class PickingListPickingStartedValidator implements PickingListPickingStartedValidatorInterface
{
    /**
     * @var \Spryker\Zed\PickingList\Business\Reader\PickingListReaderInterface
     */
    protected PickingListReaderInterface $pickingListReader;

    /**
     * @var \Spryker\Zed\PickingList\PickingListConfig
     */
    protected PickingListConfig $pickingListConfig;

    /**
     * @param \Spryker\Zed\PickingList\Business\Reader\PickingListReaderInterface $pickingListReader
     * @param \Spryker\Zed\PickingList\PickingListConfig $pickingListConfig
     */
    public function __construct(
        PickingListReaderInterface $pickingListReader,
        PickingListConfig $pickingListConfig
    ) {
        $this->pickingListReader = $pickingListReader;
        $this->pickingListConfig = $pickingListConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingStartedRequestTransfer $pickingStartedRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingStartedResponseTransfer
     */
    public function isPickingStarted(
        PickingStartedRequestTransfer $pickingStartedRequestTransfer
    ): PickingStartedResponseTransfer {
        /** @var \ArrayObject<int, \Generated\Shared\Transfer\OrderTransfer> $orderTransfers */
        $orderTransfers = $pickingStartedRequestTransfer->getOrders();
        if (!$orderTransfers->count()) {
            return new PickingStartedResponseTransfer();
        }

        $pickingListTransfersGroupedByIdSalesOrder = $this->pickingListReader->getPickingListTransfersGroupedByIdSalesOrder($orderTransfers);

        foreach ($orderTransfers as $orderTransfer) {
            $pickingListTransfers = $pickingListTransfersGroupedByIdSalesOrder[$orderTransfer->getIdSalesOrderOrFail()] ?? [];

            $orderTransfer->setIsPickingStarted($this->isPickingStartedForOrder($pickingListTransfers));
        }

        return (new PickingStartedResponseTransfer())->setOrders($orderTransfers);
    }

    /**
     * @param list<\Generated\Shared\Transfer\PickingListTransfer> $pickingListTransfers
     *
     * @return bool
     */
    protected function isPickingStartedForOrder(array $pickingListTransfers): bool
    {
        $orderPickingListStartedStatuses = $this->pickingListConfig->getOrderPickingListStartedStatuses();
        foreach ($pickingListTransfers as $pickingListTransfer) {
            if (in_array($pickingListTransfer->getStatus(), $orderPickingListStartedStatuses, true)) {
                return true;
            }
        }

        return false;
    }
}

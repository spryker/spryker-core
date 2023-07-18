<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Validator;

use Generated\Shared\Transfer\PickingFinishedRequestTransfer;
use Generated\Shared\Transfer\PickingFinishedResponseTransfer;
use Spryker\Shared\PickingList\PickingListConfig as PickingListSharedConfig;
use Spryker\Zed\PickingList\Business\Reader\PickingListReaderInterface;

class PickingListPickingFinishedValidator implements PickingListPickingFinishedValidatorInterface
{
    /**
     * @var \Spryker\Zed\PickingList\Business\Reader\PickingListReaderInterface
     */
    protected PickingListReaderInterface $pickingListReader;

    /**
     * @param \Spryker\Zed\PickingList\Business\Reader\PickingListReaderInterface $pickingListReader
     */
    public function __construct(PickingListReaderInterface $pickingListReader)
    {
        $this->pickingListReader = $pickingListReader;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingFinishedRequestTransfer $pickingFinishedRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingFinishedResponseTransfer
     */
    public function isPickingFinished(
        PickingFinishedRequestTransfer $pickingFinishedRequestTransfer
    ): PickingFinishedResponseTransfer {
        /** @var \ArrayObject<int, \Generated\Shared\Transfer\OrderTransfer> $orderTransfers */
        $orderTransfers = $pickingFinishedRequestTransfer->getOrders();
        if (!$orderTransfers->count()) {
            return new PickingFinishedResponseTransfer();
        }

        $pickingListTransfersGroupedByIdSalesOrder = $this->pickingListReader->getPickingListTransfersGroupedByIdSalesOrder($orderTransfers);

        foreach ($orderTransfers as $orderTransfer) {
            $pickingListTransfers = $pickingListTransfersGroupedByIdSalesOrder[$orderTransfer->getIdSalesOrderOrFail()] ?? [];

            $orderTransfer->setIsPickingFinished($this->isPickingFinishedForOrder($pickingListTransfers));
        }

        return (new PickingFinishedResponseTransfer())->setOrders($orderTransfers);
    }

    /**
     * @param list<\Generated\Shared\Transfer\PickingListTransfer> $pickingListTransfers
     *
     * @return bool
     */
    protected function isPickingFinishedForOrder(array $pickingListTransfers): bool
    {
        $totalPickingListsCount = count($pickingListTransfers);
        if ($totalPickingListsCount === 0) {
            return false;
        }

        $finishedPickingListCount = 0;
        foreach ($pickingListTransfers as $pickingListTransfer) {
            if ($pickingListTransfer->getStatus() === PickingListSharedConfig::STATUS_PICKING_FINISHED) {
                $finishedPickingListCount++;
            }
        }

        return $totalPickingListsCount === $finishedPickingListCount;
    }
}

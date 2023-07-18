<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Validator;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PickingListGenerationFinishedRequestTransfer;
use Generated\Shared\Transfer\PickingListGenerationFinishedResponseTransfer;
use Spryker\Zed\PickingList\Business\Reader\PickingListReaderInterface;

class PickingListGenerationFinishedValidator implements PickingListGenerationFinishedValidatorInterface
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
     * @param \Generated\Shared\Transfer\PickingListGenerationFinishedRequestTransfer $pickingListGenerationFinishedRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListGenerationFinishedResponseTransfer
     */
    public function isPickingListGenerationFinished(
        PickingListGenerationFinishedRequestTransfer $pickingListGenerationFinishedRequestTransfer
    ): PickingListGenerationFinishedResponseTransfer {
        /** @var \ArrayObject<int, \Generated\Shared\Transfer\OrderTransfer> $orderTransfers */
        $orderTransfers = $pickingListGenerationFinishedRequestTransfer->getOrders();
        if (!$orderTransfers->count()) {
            return new PickingListGenerationFinishedResponseTransfer();
        }

        $pickingListTransfersGroupedByIdSalesOrder = $this->pickingListReader->getPickingListTransfersGroupedByIdSalesOrder($orderTransfers);

        foreach ($orderTransfers as $orderTransfer) {
            $pickingListTransfers = $pickingListTransfersGroupedByIdSalesOrder[$orderTransfer->getIdSalesOrderOrFail()] ?? [];

            $orderTransfer->setIsPickingListGenerationFinished(
                $this->isPickingListGenerationFinishedForOrder($orderTransfer, $pickingListTransfers),
            );
        }

        return (new PickingListGenerationFinishedResponseTransfer())->setOrders($orderTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param list<\Generated\Shared\Transfer\PickingListTransfer> $pickingListTransfers
     *
     * @return bool
     */
    protected function isPickingListGenerationFinishedForOrder(
        OrderTransfer $orderTransfer,
        array $pickingListTransfers
    ): bool {
        $pickingListItemCount = 0;
        foreach ($pickingListTransfers as $pickingListTransfer) {
            $pickingListItemCount += count($pickingListTransfer->getPickingListItems());
        }

        return $pickingListItemCount === count($orderTransfer->getItems());
    }
}

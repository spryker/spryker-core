<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\MerchantOrder;

use Generated\Shared\Transfer\MerchantOrderCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\MerchantSalesOrder\Business\MerchantOrderItem\MerchantOrderItemWriterInterface;
use Spryker\Zed\MerchantSalesOrder\Business\MerchantOrderTotals\MerchantOrderTotalsWriterInterface;

class MerchantOrderCreator implements MerchantOrderCreatorInterface
{
    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Business\MerchantOrder\MerchantOrderWriterInterface
     */
    protected $merchantOrderWriter;

    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Business\MerchantOrderItem\MerchantOrderItemWriterInterface
     */
    protected $merchantOrderItemWriter;

    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Business\MerchantOrderTotals\MerchantOrderTotalsWriterInterface
     */
    protected $merchantOrderTotalsWriter;

    /**
     * @param \Spryker\Zed\MerchantSalesOrder\Business\MerchantOrder\MerchantOrderWriterInterface $merchantOrderWriter
     * @param \Spryker\Zed\MerchantSalesOrder\Business\MerchantOrderItem\MerchantOrderItemWriterInterface $merchantOrderItemWriter
     * @param \Spryker\Zed\MerchantSalesOrder\Business\MerchantOrderTotals\MerchantOrderTotalsWriterInterface $merchantOrderTotalsWriter
     */
    public function __construct(
        MerchantOrderWriterInterface $merchantOrderWriter,
        MerchantOrderItemWriterInterface $merchantOrderItemWriter,
        MerchantOrderTotalsWriterInterface $merchantOrderTotalsWriter
    ) {
        $this->merchantOrderWriter = $merchantOrderWriter;
        $this->merchantOrderItemWriter = $merchantOrderItemWriter;
        $this->merchantOrderTotalsWriter = $merchantOrderTotalsWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderCollectionTransfer
     */
    public function createMerchantOrderCollection(OrderTransfer $orderTransfer): MerchantOrderCollectionTransfer
    {
        $orderTransfer->requireIdSalesOrder()
            ->requireOrderReference()
            ->requireItems();

        $merchantOrderCollectionTransfer = new MerchantOrderCollectionTransfer();
        $orderItemsGroupedByMerchantReference = $this->getOrderItemsGroupedByMerchantReference($orderTransfer);

        foreach ($orderItemsGroupedByMerchantReference as $merchantReference => $itemTransferList) {
            $merchantOrderCollectionTransfer->addMerchantOrder(
                $this->createCompleteMerchantOrder($orderTransfer, $merchantReference, $itemTransferList)
            );
        }

        return $merchantOrderCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[][]
     */
    protected function getOrderItemsGroupedByMerchantReference(OrderTransfer $orderTransfer): array
    {
        $orderItemsGroupedByMerchantReference = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getMerchantReference()) {
                continue;
            }

            $orderItemsGroupedByMerchantReference[$itemTransfer->getMerchantReference()][] = $itemTransfer;
        }

        return $orderItemsGroupedByMerchantReference;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $merchantReference
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransferList
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    protected function createCompleteMerchantOrder(
        OrderTransfer $orderTransfer,
        string $merchantReference,
        array $itemTransferList
    ): MerchantOrderTransfer {
        $merchantOrderTransfer = $this->merchantOrderWriter->createMerchantOrder(
            $orderTransfer,
            $merchantReference
        );
        $merchantOrderTransfer = $this->addMerchantOrderItemsToMerchantOrder(
            $merchantOrderTransfer,
            $itemTransferList
        );

        return $merchantOrderTransfer->setTotals(
            $this->merchantOrderTotalsWriter->createMerchantOrderTotals($merchantOrderTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransferList
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    protected function addMerchantOrderItemsToMerchantOrder(
        MerchantOrderTransfer $merchantOrderTransfer,
        array $itemTransferList
    ): MerchantOrderTransfer {
        foreach ($itemTransferList as $itemTransfer) {
            $merchantOrderTransfer->addMerchantOrderItem(
                $this->merchantOrderItemWriter->createMerchantOrderItem($itemTransfer, $merchantOrderTransfer)
            );
        }

        return $merchantOrderTransfer;
    }
}

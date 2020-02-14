<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrder;

use Generated\Shared\Transfer\MerchantOrderCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderItem\MerchantSalesOrderItemWriterInterface;
use Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderTotals\MerchantSalesOrderTotalsWriterInterface;

class MerchantSalesOrderCreator implements MerchantSalesOrderCreatorInterface
{
    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrder\MerchantSalesOrderWriterInterface
     */
    protected $merchantSalesOrderWriter;

    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderItem\MerchantSalesOrderItemWriterInterface
     */
    protected $merchantSalesOrderItemWriter;

    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderTotals\MerchantSalesOrderTotalsWriterInterface
     */
    protected $merchantSalesOrderTotalsWriter;

    /**
     * @var \Generated\Shared\Transfer\MerchantOrderTransfer[]
     */
    protected $merchantOrderBuffer = [];

    /**
     * @param \Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrder\MerchantSalesOrderWriterInterface $merchantSalesOrderWriter
     * @param \Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderItem\MerchantSalesOrderItemWriterInterface $merchantSalesOrderItemWriter
     * @param \Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderTotals\MerchantSalesOrderTotalsWriterInterface $merchantSalesOrderTotalsWriter
     */
    public function __construct(
        MerchantSalesOrderWriterInterface $merchantSalesOrderWriter,
        MerchantSalesOrderItemWriterInterface $merchantSalesOrderItemWriter,
        MerchantSalesOrderTotalsWriterInterface $merchantSalesOrderTotalsWriter
    ) {
        $this->merchantSalesOrderWriter = $merchantSalesOrderWriter;
        $this->merchantSalesOrderItemWriter = $merchantSalesOrderItemWriter;
        $this->merchantSalesOrderTotalsWriter = $merchantSalesOrderTotalsWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderCollectionTransfer
     */
    public function createMerchantSalesOrders(OrderTransfer $orderTransfer): MerchantOrderCollectionTransfer
    {
        $orderTransfer->requireIdSalesOrder();
        $orderTransfer->requireOrderReference();
        $orderTransfer->requireItems();

        $this->merchantOrderBuffer = [];
        $merchantOrderCollectionTransfer = new MerchantOrderCollectionTransfer();

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getMerchantReference()) {
                continue;
            }

            $merchantOrderTransfer = $this->persistMerchantOrder($orderTransfer, $itemTransfer->getMerchantReference());
            $merchantOrderItemTransfer = $this->merchantSalesOrderItemWriter
                ->createMerchantSalesOrderItem($itemTransfer, $merchantOrderTransfer);
            $merchantOrderItemTransfer->setItem($itemTransfer);
            $merchantOrderTransfer->addMerchantOrderItem($merchantOrderItemTransfer);
        }

        $merchantOrderCollectionTransfer->getOrders()->exchangeArray(array_values($this->merchantOrderBuffer));

        foreach ($merchantOrderCollectionTransfer->getOrders() as $merchantSalesOrder) {
            $totalsTransfer = $this->merchantSalesOrderTotalsWriter->createMerchantOrderTotals($merchantSalesOrder);
            $merchantSalesOrder->setTotals($totalsTransfer);
        }

        return $merchantOrderCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    protected function persistMerchantOrder(
        OrderTransfer $orderTransfer,
        string $merchantReference
    ): MerchantOrderTransfer {
        if (isset($this->merchantOrderBuffer[$merchantReference])) {
            return $this->merchantOrderBuffer[$merchantReference];
        }

        $merchantOrderTransfer = $this->merchantSalesOrderWriter
            ->createMerchantSalesOrder($orderTransfer, $merchantReference);
        $this->merchantOrderBuffer[$merchantReference] = $merchantOrderTransfer;

        return $merchantOrderTransfer;
    }
}

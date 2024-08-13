<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business\Merchant;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderExpenseTransfer;
use Generated\Shared\Transfer\OrderItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransmissionResponseTransfer;
use Spryker\Zed\SalesPaymentMerchant\Business\Expander\OrderItemExpanderInterface;
use Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Calculator\MerchantPayoutCalculatorInterface;
use Spryker\Zed\SalesPaymentMerchant\Business\Reader\TransferEndpointReaderInterface;
use Spryker\Zed\SalesPaymentMerchant\Business\Sender\TransferRequestSenderInterface;
use Spryker\Zed\SalesPaymentMerchant\Persistence\SalesPaymentMerchantEntityManagerInterface;

abstract class AbstractMerchantTransfer
{
    /**
     * @var \Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Calculator\MerchantPayoutCalculatorInterface
     */
    protected MerchantPayoutCalculatorInterface $merchantPayoutCalculator;

    /**
     * @var \Spryker\Zed\SalesPaymentMerchant\Business\Reader\TransferEndpointReaderInterface
     */
    protected TransferEndpointReaderInterface $transferEndpointReader;

    /**
     * @var \Spryker\Zed\SalesPaymentMerchant\Business\Sender\TransferRequestSenderInterface
     */
    protected TransferRequestSenderInterface $transferRequestSender;

    /**
     * @var \Spryker\Zed\SalesPaymentMerchant\Persistence\SalesPaymentMerchantEntityManagerInterface
     */
    protected SalesPaymentMerchantEntityManagerInterface $salesPaymentMerchantEntityManager;

    /**
     * @var \Spryker\Zed\SalesPaymentMerchant\Business\Expander\OrderItemExpanderInterface
     */
    protected OrderItemExpanderInterface $orderItemExpander;

    /**
     * @param \Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Calculator\MerchantPayoutCalculatorInterface $merchantPayoutCalculator
     * @param \Spryker\Zed\SalesPaymentMerchant\Business\Reader\TransferEndpointReaderInterface $transferEndpointReader
     * @param \Spryker\Zed\SalesPaymentMerchant\Business\Sender\TransferRequestSenderInterface $transferRequestSender
     * @param \Spryker\Zed\SalesPaymentMerchant\Persistence\SalesPaymentMerchantEntityManagerInterface $salesPaymentMerchantEntityManager
     * @param \Spryker\Zed\SalesPaymentMerchant\Business\Expander\OrderItemExpanderInterface $orderItemExpander
     */
    public function __construct(
        MerchantPayoutCalculatorInterface $merchantPayoutCalculator,
        TransferEndpointReaderInterface $transferEndpointReader,
        TransferRequestSenderInterface $transferRequestSender,
        SalesPaymentMerchantEntityManagerInterface $salesPaymentMerchantEntityManager,
        OrderItemExpanderInterface $orderItemExpander
    ) {
        $this->merchantPayoutCalculator = $merchantPayoutCalculator;
        $this->transferEndpointReader = $transferEndpointReader;
        $this->transferRequestSender = $transferRequestSender;
        $this->salesPaymentMerchantEntityManager = $salesPaymentMerchantEntityManager;
        $this->orderItemExpander = $orderItemExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    abstract protected function calculatePayoutAmount(ItemTransfer $itemTransfer, OrderTransfer $orderTransfer): int;

    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $salesOrderItemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return list<\Generated\Shared\Transfer\OrderItemTransfer>
     */
    protected function getOrderItemsForTransfer(array $salesOrderItemTransfers, OrderTransfer $orderTransfer): array
    {
        $orderItemTransfers = [];
        foreach ($salesOrderItemTransfers as $itemTransfer) {
            if (!$itemTransfer->getMerchantReference()) {
                continue;
            }

            $orderItemTransfers[] = (new OrderItemTransfer())
                ->fromArray($itemTransfer->toArray(), true)
                ->setMerchantReference($itemTransfer->getMerchantReferenceOrFail())
                ->setOrderReference($orderTransfer->getOrderReferenceOrFail())
                ->setItemReference($itemTransfer->getOrderItemReferenceOrFail())
                ->setAmount((string)$this->calculatePayoutAmount($itemTransfer, $orderTransfer));
        }

        return $orderItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return list<\Generated\Shared\Transfer\OrderExpenseTransfer>
     */
    protected function getOrderExpensesForTransfer(OrderTransfer $orderTransfer): array
    {
        $orderExpenseTransfers = [];
        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            $orderExpenseTransfers[] = (new OrderExpenseTransfer())
                ->fromArray($expenseTransfer->toArray(), true)
                ->setMerchantReference($expenseTransfer->getMerchantReferenceOrFail())
                ->setOrderReference($orderTransfer->getOrderReferenceOrFail())
                ->setExpenseReference($expenseTransfer->getUuidOrFail())
                ->setAmount((string)$expenseTransfer->getSumPriceToPayAggregationOrFail());
        }

        return $orderExpenseTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransmissionResponseTransfer $paymentTransmissionResponseTransfer
     *
     * @return string
     */
    protected function getItemReferences(PaymentTransmissionResponseTransfer $paymentTransmissionResponseTransfer): string
    {
        $itemReferences = [];

        foreach ($paymentTransmissionResponseTransfer->getOrderItems() as $orderItemTransfer) {
            $itemReferences[] = $orderItemTransfer->getItemReferenceOrFail();
        }

        return implode(',', $itemReferences);
    }

    /**
     * @param list<\Generated\Shared\Transfer\OrderItemTransfer> $orderItemTransfers
     * @param list<\Generated\Shared\Transfer\OrderExpenseTransfer> $orderExpenseTransfers
     *
     * @return array<string, array<int, array<string, mixed>>|int>
     */
    protected function createTransferRequestData(array $orderItemTransfers, array $orderExpenseTransfers): array
    {
        $orderAmountTotal = $this->merchantPayoutCalculator->calculatePayoutAmountForOrder($orderItemTransfers, $orderExpenseTransfers);

        $orderItemTransfersArray = array_map(fn ($itemTransfer) => $itemTransfer->toArray(), $orderItemTransfers);
        $orderExpenseTransfersArray = array_map(fn ($expenseTransfer) => $expenseTransfer->toArray(), $orderExpenseTransfers);

        return [
            'orderItems' => $orderItemTransfersArray,
            'orderExpenses' => $orderExpenseTransfersArray,
            'orderAmountTotal' => $orderAmountTotal,
        ];
    }
}

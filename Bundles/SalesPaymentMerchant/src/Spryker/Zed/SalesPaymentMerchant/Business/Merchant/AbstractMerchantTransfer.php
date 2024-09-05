<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business\Merchant;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransmissionItemTransfer;
use Generated\Shared\Transfer\PaymentTransmissionResponseTransfer;
use Spryker\Zed\SalesPaymentMerchant\Business\Expander\PaymentTransmissionItemExpanderInterface;
use Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Calculator\MerchantPayoutCalculatorInterface;
use Spryker\Zed\SalesPaymentMerchant\Business\Reader\OrderExpenseReaderInterface;
use Spryker\Zed\SalesPaymentMerchant\Business\Reader\TransferEndpointReaderInterface;
use Spryker\Zed\SalesPaymentMerchant\Business\Sender\TransferRequestSenderInterface;
use Spryker\Zed\SalesPaymentMerchant\Persistence\SalesPaymentMerchantEntityManagerInterface;
use Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig;

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
     * @var \Spryker\Zed\SalesPaymentMerchant\Business\Expander\PaymentTransmissionItemExpanderInterface
     */
    protected PaymentTransmissionItemExpanderInterface $paymentTransmissionItemExpander;

    /**
     * @var \Spryker\Zed\SalesPaymentMerchant\Business\Reader\OrderExpenseReaderInterface
     */
    protected OrderExpenseReaderInterface $orderExpenseReader;

    /**
     * @var \Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig
     */
    protected SalesPaymentMerchantConfig $salesPaymentMerchantConfig;

    /**
     * @param \Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Calculator\MerchantPayoutCalculatorInterface $merchantPayoutCalculator
     * @param \Spryker\Zed\SalesPaymentMerchant\Business\Reader\TransferEndpointReaderInterface $transferEndpointReader
     * @param \Spryker\Zed\SalesPaymentMerchant\Business\Sender\TransferRequestSenderInterface $transferRequestSender
     * @param \Spryker\Zed\SalesPaymentMerchant\Persistence\SalesPaymentMerchantEntityManagerInterface $salesPaymentMerchantEntityManager
     * @param \Spryker\Zed\SalesPaymentMerchant\Business\Expander\PaymentTransmissionItemExpanderInterface $paymentTransmissionItemExpander
     * @param \Spryker\Zed\SalesPaymentMerchant\Business\Reader\OrderExpenseReaderInterface $orderExpenseReader
     * @param \Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig $salesPaymentMerchantConfig
     */
    public function __construct(
        MerchantPayoutCalculatorInterface $merchantPayoutCalculator,
        TransferEndpointReaderInterface $transferEndpointReader,
        TransferRequestSenderInterface $transferRequestSender,
        SalesPaymentMerchantEntityManagerInterface $salesPaymentMerchantEntityManager,
        PaymentTransmissionItemExpanderInterface $paymentTransmissionItemExpander,
        OrderExpenseReaderInterface $orderExpenseReader,
        SalesPaymentMerchantConfig $salesPaymentMerchantConfig
    ) {
        $this->merchantPayoutCalculator = $merchantPayoutCalculator;
        $this->transferEndpointReader = $transferEndpointReader;
        $this->transferRequestSender = $transferRequestSender;
        $this->salesPaymentMerchantEntityManager = $salesPaymentMerchantEntityManager;
        $this->paymentTransmissionItemExpander = $paymentTransmissionItemExpander;
        $this->orderExpenseReader = $orderExpenseReader;
        $this->salesPaymentMerchantConfig = $salesPaymentMerchantConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    abstract protected function calculatePayoutAmount(ItemTransfer $itemTransfer, OrderTransfer $orderTransfer): int;

    /**
     * @param \Generated\Shared\Transfer\PaymentTransmissionResponseTransfer $paymentTransmissionResponseTransfer
     *
     * @return void
     */
    abstract protected function savePaymentTransmissionResponse(
        PaymentTransmissionResponseTransfer $paymentTransmissionResponseTransfer
    ): void;

    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $salesOrderItemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return list<\Generated\Shared\Transfer\PaymentTransmissionItemTransfer>
     */
    protected function getOrderItemsForTransfer(array $salesOrderItemTransfers, OrderTransfer $orderTransfer): array
    {
        $orderItemPaymentTransmissionItemTransfers = [];
        foreach ($salesOrderItemTransfers as $itemTransfer) {
            if (!$itemTransfer->getMerchantReference()) {
                continue;
            }

            $orderItemPaymentTransmissionItemTransfers[] = (new PaymentTransmissionItemTransfer())
                ->fromArray($itemTransfer->toArray(), true)
                ->setType(SalesPaymentMerchantConfig::PAYMENT_TRANSMISSION_ITEM_TYPE_ORDER_ITEM)
                ->setMerchantReference($itemTransfer->getMerchantReferenceOrFail())
                ->setOrderReference($orderTransfer->getOrderReferenceOrFail())
                ->setItemReference($itemTransfer->getOrderItemReferenceOrFail())
                ->setAmount((string)$this->calculatePayoutAmount($itemTransfer, $orderTransfer));
        }

        return $orderItemPaymentTransmissionItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransmissionResponseTransfer $paymentTransmissionResponseTransfer
     *
     * @return string
     */
    protected function getItemReferences(
        PaymentTransmissionResponseTransfer $paymentTransmissionResponseTransfer
    ): string {
        $paymentTransmissionItemTransfers = $paymentTransmissionResponseTransfer->getPaymentTransmissionItems()->count() > 0 ?
            $paymentTransmissionResponseTransfer->getPaymentTransmissionItems() :
            $paymentTransmissionResponseTransfer->getOrderItems();

        $itemReferences = [];
        foreach ($paymentTransmissionItemTransfers as $paymentTransmissionItemTransfer) {
            $itemReferences[] = $paymentTransmissionItemTransfer->getItemReferenceOrFail();
        }

        return implode(',', $itemReferences);
    }

    /**
     * @param list<\Generated\Shared\Transfer\PaymentTransmissionItemTransfer> $paymentTransmissionItemTransfers
     * @param string $transferEndpointUrl
     *
     * @return void
     */
    protected function executePayoutTransmissionTransaction(
        array $paymentTransmissionItemTransfers,
        string $transferEndpointUrl
    ): void {
        $transferRequestData = $this->createTransferRequestData($paymentTransmissionItemTransfers);
        $paymentTransmissionResponseCollectionTransfer = $this->transferRequestSender->requestTransfer(
            $transferRequestData,
            $transferEndpointUrl,
        );

        /** @var \Generated\Shared\Transfer\PaymentTransmissionResponseTransfer $paymentTransmissionResponseTransfer */
        foreach ($paymentTransmissionResponseCollectionTransfer->getPaymentTransmissions() as $paymentTransmissionResponseTransfer) {
            $paymentTransmissionResponseTransfer->setItemReferences($this->getItemReferences($paymentTransmissionResponseTransfer));

            $this->savePaymentTransmissionResponse($paymentTransmissionResponseTransfer);
        }
    }

    /**
     * @param list<\Generated\Shared\Transfer\PaymentTransmissionItemTransfer> $paymentTransmissionItemTransfers
     *
     * @return array<string, array<int, array<string, mixed>>|int>
     */
    protected function createTransferRequestData(
        array $paymentTransmissionItemTransfers
    ): array {
        $paymentTransmissionItemTransfersArray = array_map(
            fn (PaymentTransmissionItemTransfer $paymentTransmissionItem) => $paymentTransmissionItem->toArray(),
            $paymentTransmissionItemTransfers,
        );

        return [
            'orderItems' => $paymentTransmissionItemTransfersArray,
            'paymentTransmissionItems' => $paymentTransmissionItemTransfersArray,
        ];
    }
}

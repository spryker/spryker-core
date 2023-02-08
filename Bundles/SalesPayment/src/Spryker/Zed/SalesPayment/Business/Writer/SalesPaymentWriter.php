<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Business\Writer;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesPaymentTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SalesPayment\Persistence\SalesPaymentEntityManagerInterface;

class SalesPaymentWriter implements SalesPaymentWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\SalesPayment\Persistence\SalesPaymentEntityManagerInterface
     */
    protected SalesPaymentEntityManagerInterface $salesPaymentEntityManager;

    /**
     * @var array<\Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\PaymentMapKeyBuilderStrategyPluginInterface>
     */
    protected array $paymentMapKeyBuilderStrategyPlugins;

    /**
     * @param \Spryker\Zed\SalesPayment\Persistence\SalesPaymentEntityManagerInterface $entityManager
     * @param array<\Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\PaymentMapKeyBuilderStrategyPluginInterface> $paymentMapKeyBuilderStrategyPlugins
     */
    public function __construct(
        SalesPaymentEntityManagerInterface $entityManager,
        array $paymentMapKeyBuilderStrategyPlugins
    ) {
        $this->salesPaymentEntityManager = $entityManager;
        $this->paymentMapKeyBuilderStrategyPlugins = $paymentMapKeyBuilderStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderPayments(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $idSalesOrder = $saveOrderTransfer->getIdSalesOrderOrFail();

        $this->getTransactionHandler()->handleTransaction(function () use ($quoteTransfer, $idSalesOrder): void {
            $this->executeSavePaymentMethodsTransaction($quoteTransfer, $idSalesOrder);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idSalesOrder
     *
     * @return void
     */
    protected function executeSavePaymentMethodsTransaction(QuoteTransfer $quoteTransfer, int $idSalesOrder): void
    {
        $paymentTransfers = $this->getPaymentTransfers($quoteTransfer);
        $this->savePaymentTransfers($paymentTransfers, $idSalesOrder);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<\Generated\Shared\Transfer\PaymentTransfer>
     */
    protected function getPaymentTransfers(QuoteTransfer $quoteTransfer): array
    {
        $result = [];
        foreach ($quoteTransfer->getPayments() as $paymentTransfer) {
            $result[$this->createPaymentMapKey($paymentTransfer)] = $paymentTransfer;
        }

        $singlePaymentTransfer = $quoteTransfer->getPayment();

        if ($singlePaymentTransfer) {
            $result[$this->createPaymentMapKey($singlePaymentTransfer)] = $singlePaymentTransfer;
        }

        return $result;
    }

    /**
     * @param array<\Generated\Shared\Transfer\PaymentTransfer> $paymentTransfers
     * @param int $idSalesOrder
     *
     * @return void
     */
    protected function savePaymentTransfers(array $paymentTransfers, int $idSalesOrder): void
    {
        foreach ($paymentTransfers as $paymentTransfer) {
            $salesPaymentTransfer = $this->salesPaymentEntityManager->createSalesPayment(
                (new SalesPaymentTransfer())
                    ->setFkSalesOrder($idSalesOrder)
                    ->setPaymentMethod($paymentTransfer->getPaymentMethod())
                    ->setPaymentProvider($paymentTransfer->getPaymentProvider())
                    ->setAmount($paymentTransfer->getAmount()),
            );

            $paymentTransfer->setIdSalesPayment($salesPaymentTransfer->getIdSalesPayment());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return string
     */
    protected function createPaymentMapKey(PaymentTransfer $paymentTransfer): string
    {
        foreach ($this->paymentMapKeyBuilderStrategyPlugins as $paymentMapKeyBuilderStrategyPlugin) {
            if ($paymentMapKeyBuilderStrategyPlugin->isApplicable($paymentTransfer)) {
                return $paymentMapKeyBuilderStrategyPlugin->buildPaymentMapKey($paymentTransfer);
            }
        }

        return sprintf(
            '%s-%s',
            $paymentTransfer->getPaymentProvider(),
            $paymentTransfer->getPaymentMethod(),
        );
    }
}

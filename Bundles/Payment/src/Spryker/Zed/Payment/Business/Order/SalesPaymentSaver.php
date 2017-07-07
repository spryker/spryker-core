<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Order;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Payment\Persistence\SpySalesPayment;
use Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class SalesPaymentSaver implements SalesPaymentSaverInterface
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface
     */
    protected $paymentQueryContainer;

    /**
     * @param \Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface $paymentQueryContainer
     */
    public function __construct(PaymentQueryContainerInterface $paymentQueryContainer)
    {
        $this->paymentQueryContainer = $paymentQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveOrderPayments(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $checkoutResponse->requireSaveOrder()
            ->getSaveOrder()
            ->requireIdSalesOrder();

        $idSalesOrder = $checkoutResponse->getSaveOrder()->getIdSalesOrder();
        $this->handleDatabaseTransaction(function () use ($quoteTransfer, $idSalesOrder) {
            $this->executeSavePaymentMethodsTransaction($quoteTransfer, $idSalesOrder);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idSalesOrder
     *
     * @return void
     */
    protected function executeSavePaymentMethodsTransaction(QuoteTransfer $quoteTransfer, $idSalesOrder)
    {
        $this->saveSinglePayment($quoteTransfer, $idSalesOrder);
        foreach ($quoteTransfer->getPayments() as $paymentTransfer) {
            $salesPaymentEntity = $this->mapSalesPaymentEntity($paymentTransfer, $idSalesOrder);
            $salesPaymentEntity->save();
            $paymentTransfer->setIdSalesPayment($salesPaymentEntity->getIdSalesPayment());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Payment\Persistence\SpySalesPayment
     */
    protected function mapSalesPaymentEntity(PaymentTransfer $paymentTransfer, $idSalesOrder)
    {
        $paymentMethodTypeEntity = $this->paymentQueryContainer
            ->queryPaymentMethodType(
                $paymentTransfer->getPaymentProvider(),
                $paymentTransfer->getPaymentMethod()
            )
            ->findOneOrCreate();

        if ($paymentMethodTypeEntity->isNew()) {
            $paymentMethodTypeEntity->save();
        }

        $salesPaymentEntity = new SpySalesPayment();
        $salesPaymentEntity->setFkSalesOrder($idSalesOrder);
        $salesPaymentEntity->setFkSalesPaymentMethodType($paymentMethodTypeEntity->getIdSalesPaymentMethodType());
        $salesPaymentEntity->setAmount($paymentTransfer->getAmount());

        return $salesPaymentEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idSalesOrder
     *
     * @return void
     */
    protected function saveSinglePayment(QuoteTransfer $quoteTransfer, $idSalesOrder)
    {
        $paymentTransfer = $quoteTransfer->getPayment();
        $salesPaymentEntity = $this->mapSalesPaymentEntity($paymentTransfer, $idSalesOrder);
        $numberOfPayments = $quoteTransfer->getPayments()->count();
        if ($numberOfPayments === 0) {
            $salesPaymentEntity->setAmount($quoteTransfer->getTotals()->getGrandTotal());
        }
        $paymentTransfer->setIdSalesPayment($salesPaymentEntity->getIdSalesPayment());
        $salesPaymentEntity->save();
    }

}

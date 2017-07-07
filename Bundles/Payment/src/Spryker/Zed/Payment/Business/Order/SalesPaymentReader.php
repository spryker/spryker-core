<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Order;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\SalesPaymentTransfer;
use Orm\Zed\Payment\Persistence\SpySalesPayment;
use Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface;

class SalesPaymentReader implements SalesPaymentReaderInterface
{

    /**
     * @var \Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface
     */
    protected $paymentQueryContainer;

     /**
      * @var \Spryker\Zed\Payment\Business\Order\PaymentHydratorExecutorInterface
      */
    protected $paymentHydratorExecutor;

    /**
     * @param \Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface $paymentQueryContainer
     * @param \Spryker\Zed\Payment\Business\Order\PaymentHydratorExecutorInterface $paymentHydratorExecutor
     */
    public function __construct(
        PaymentQueryContainerInterface $paymentQueryContainer,
        PaymentHydratorExecutorInterface $paymentHydratorExecutor
    ) {

        $this->paymentQueryContainer = $paymentQueryContainer;
        $this->paymentHydratorExecutor = $paymentHydratorExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesPaymentTransfer $paymentTransfer
     *
     * @return int
     */
    public function getPaymentMethodPriceToPay(SalesPaymentTransfer $paymentTransfer)
    {
        $salesPaymentEntity = $this->paymentQueryContainer->queryPaymentMethodPriceToPay(
            $paymentTransfer->getFkSalesOrder(),
            $paymentTransfer->getPaymentProvider(),
            $paymentTransfer->getPaymentMethod()
        )->findOne();

        return $salesPaymentEntity->getAmount();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderWithPayment(OrderTransfer $orderTransfer)
    {
        $orderTransfer->requireIdSalesOrder();

        $salesPayments = $this->paymentQueryContainer
            ->queryPaymentMethodsByIdSalesOrder($orderTransfer->getIdSalesOrder())
            ->find();

        foreach ($salesPayments as $salesPaymentEntity) {
            $paymentTransfer = $this->mapPaymentTransfer($salesPaymentEntity);
            $orderTransfer->addPayment($paymentTransfer);
        }

        $orderTransfer = $this->paymentHydratorExecutor->hydrate($orderTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Payment\Persistence\SpySalesPayment $salesPaymentEntity
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function mapPaymentTransfer(SpySalesPayment $salesPaymentEntity)
    {
        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentProvider($salesPaymentEntity->getSalesPaymentMethodType()->getPaymentProvider());
        $paymentTransfer->setPaymentMethod($salesPaymentEntity->getSalesPaymentMethodType()->getPaymentMethod());
        $paymentTransfer->fromArray($salesPaymentEntity->toArray(), true);

        return $paymentTransfer;
    }

}

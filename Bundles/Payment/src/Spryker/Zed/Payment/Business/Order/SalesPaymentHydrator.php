<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Order;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Orm\Zed\Payment\Persistence\SpySalesPayment;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface;

class SalesPaymentHydrator implements SalesPaymentHydratorInterface
{

    /**
     * @var \Spryker\Zed\Payment\Dependency\Plugin\Sales\PaymentHydratorPluginInterface[]
     */
    protected $paymentHydratePlugins = [];

    /**
     * @var \Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface
     */
    protected $paymentQueryContainer;

    /**
     * @param \Spryker\Zed\Payment\Dependency\Plugin\Sales\PaymentHydratorPluginInterface[] $paymentHydratePlugins
     * @param \Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface $paymentQueryContainer
     */
    public function __construct(
        array $paymentHydratePlugins,
        PaymentQueryContainerInterface $paymentQueryContainer
    ) {
        $this->paymentHydratePlugins = $paymentHydratePlugins;
        $this->paymentQueryContainer = $paymentQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderWithPayment(OrderTransfer $orderTransfer)
    {
        $orderTransfer->requireIdSalesOrder();

        $salesPayments = $this->findSalesPaymentByIdSalesOrder($orderTransfer);
        $orderTransfer = $this->hydrate($salesPayments, $orderTransfer);

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

    /**
     *
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Payment\Persistence\SpySalesPayment[] $objectCollection
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function hydrate(ObjectCollection $objectCollection, OrderTransfer $orderTransfer)
    {
        foreach ($objectCollection as $salesPaymentEntity) {
            $paymentTransfer = $this->mapPaymentTransfer($salesPaymentEntity);
            $paymentTransfer = $this->executePaymentHydratorPlugin($paymentTransfer, $orderTransfer);
            $orderTransfer->addPayment($paymentTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function executePaymentHydratorPlugin(PaymentTransfer $paymentTransfer, OrderTransfer $orderTransfer)
    {
        if (isset($this->paymentHydratePlugins[$paymentTransfer->getPaymentProvider()])) {
            return $this->paymentHydratePlugins[$paymentTransfer->getPaymentProvider()]->hydrate($orderTransfer, $paymentTransfer);
        }

        return $paymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Orm\Zed\Payment\Persistence\SpySalesPayment[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findSalesPaymentByIdSalesOrder(OrderTransfer $orderTransfer)
    {
        return $this->paymentQueryContainer
            ->queryPaymentMethodsByIdSalesOrder($orderTransfer->getIdSalesOrder())
            ->find();
    }

}

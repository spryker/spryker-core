<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Order;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;

class PaymentHydratorExecutor implements PaymentHydratorExecutorInterface
{

    /**
     * @var \Spryker\Zed\Payment\Dependency\Plugin\PaymentHydratorPluginInterface[]
     */
    protected $paymentHydratePlugins = [];

    /**
     * @param \Spryker\Zed\Payment\Dependency\Plugin\PaymentHydratorPluginInterface[] $paymentHydratePlugins
     */
    public function __construct(array $paymentHydratePlugins)
    {
        $this->paymentHydratePlugins = $paymentHydratePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrate(OrderTransfer $orderTransfer)
    {
        $updatedPayments = new ArrayObject();
        foreach ($orderTransfer->getPayments() as $paymentTransfer) {
            $updatedPayments[] = $this->executePaymentHydratorPlugin($paymentTransfer, $orderTransfer);
        }

        $orderTransfer->setPayments($updatedPayments);

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

}

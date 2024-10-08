<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentDetail\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\PaymentCreatedTransfer;
use Generated\Shared\Transfer\PaymentUpdatedTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface;

/**
 * @deprecated Use `\Spryker\Zed\SalesPaymentDetail\Communication\Plugin\MessageBroker\SalesPaymentDetailMessageHandlerPlugin` instead.
 *
 * @method \Spryker\Zed\SalesPaymentDetail\Business\SalesPaymentDetailFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesPaymentDetail\SalesPaymentDetailConfig getConfig()
 */
class PaymentCreatedMessageHandlerPlugin extends AbstractPlugin implements MessageHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentCreatedTransfer $paymentCreatedTransfer
     *
     * @return void
     */
    public function onPaymentCreated(PaymentCreatedTransfer $paymentCreatedTransfer): void
    {
        $this->getFacade()->handlePaymentCreated($paymentCreatedTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentUpdatedTransfer $paymentUpdatedTransfer
     *
     * @return void
     */
    public function onPaymentUpdated(PaymentUpdatedTransfer $paymentUpdatedTransfer): void
    {
        $this->getFacade()->handlePaymentUpdated($paymentUpdatedTransfer);
    }

    /**
     * {@inheritDoc}
     * Return an array where the key is the class name to be handled and the value is the callable that handles the message.
     *
     * @api
     *
     * @return array<string, callable>
     */
    public function handles(): iterable
    {
        return [
            PaymentCreatedTransfer::class => [$this, 'onPaymentCreated'],
            PaymentUpdatedTransfer::class => [$this, 'onPaymentUpdated'],
        ];
    }
}

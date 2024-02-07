<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\DeletePaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentMethodDeletedTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\Payment\Communication\Plugin\MessageBroker\PaymentMethodMessageHandlerPlugin} instead.
 *
 * @method \Spryker\Zed\Payment\Business\PaymentFacadeInterface getFacade()
 * @method \Spryker\Zed\Payment\PaymentConfig getConfig()
 * @method \Spryker\Zed\Payment\Communication\PaymentCommunicationFactory getFactory()
 * @method \Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface getQueryContainer()
 */
class PaymentMethodDeletedMessageHandlerPlugin extends AbstractPlugin implements MessageHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Triggers an OMS event for PaymentMethodDeletedTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodDeletedTransfer $paymentMethodDeletedTransfer
     *
     * @return void
     */
    public function onPaymentMethodDeleted(PaymentMethodDeletedTransfer $paymentMethodDeletedTransfer): void
    {
        $deletePaymentMethodTransfer = (new DeletePaymentMethodTransfer())->fromArray($paymentMethodDeletedTransfer->toArray(), true);

        $this->getFacade()->disableForeignPaymentMethod($deletePaymentMethodTransfer);
    }

    /**
     * {@inheritDoc}
     * - Return an array where the key is the class name to be handled and the value is the callable that handles the message.
     *
     * @api
     *
     * @return array<string, callable>
     */
    public function handles(): iterable
    {
        yield PaymentMethodDeletedTransfer::class => [$this, 'onPaymentMethodDeleted'];
    }
}

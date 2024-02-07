<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\AddPaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentMethodAddedTransfer;
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
class PaymentMethodAddedMessageHandlerPlugin extends AbstractPlugin implements MessageHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Triggers an OMS event for PaymentMethodAddedTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodAddedTransfer $paymentMethodAddedTransfer
     *
     * @return void
     */
    public function onPaymentMethodAdded(PaymentMethodAddedTransfer $paymentMethodAddedTransfer): void
    {
        $addPaymentMethodTransfer = (new AddPaymentMethodTransfer())->fromArray($paymentMethodAddedTransfer->toArray(), true);

        $this->getFacade()->enableForeignPaymentMethod($addPaymentMethodTransfer);
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
        yield PaymentMethodAddedTransfer::class => [$this, 'onPaymentMethodAdded'];
    }
}

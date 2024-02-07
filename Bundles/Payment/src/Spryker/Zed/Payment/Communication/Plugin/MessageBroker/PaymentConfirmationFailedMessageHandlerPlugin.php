<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\PaymentCaptureFailedTransfer;
use Generated\Shared\Transfer\PaymentConfirmationFailedTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\Payment\Communication\Plugin\MessageBroker\PaymentOperationsMessageHandlerPlugin} instead.
 *
 * @method \Spryker\Zed\Payment\Business\PaymentFacadeInterface getFacade()
 * @method \Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Payment\PaymentConfig getConfig()
 * @method \Spryker\Zed\Payment\Communication\PaymentCommunicationFactory getFactory()
 */
class PaymentConfirmationFailedMessageHandlerPlugin extends AbstractPlugin implements MessageHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Triggers an OMS event for PaymentConfirmationFailedTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentConfirmationFailedTransfer $paymentConfirmationFailedTransfer
     *
     * @return void
     */
    public function onPaymentConfirmationFailed(PaymentConfirmationFailedTransfer $paymentConfirmationFailedTransfer): void
    {
        $capturePaymentFailedTransfer = (new PaymentCaptureFailedTransfer())->fromArray($paymentConfirmationFailedTransfer->toArray(), true);

        $this->getFacade()->triggerPaymentMessageOmsEvent($capturePaymentFailedTransfer);
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
        yield PaymentConfirmationFailedTransfer::class => [$this, 'onPaymentConfirmationFailed'];
    }
}

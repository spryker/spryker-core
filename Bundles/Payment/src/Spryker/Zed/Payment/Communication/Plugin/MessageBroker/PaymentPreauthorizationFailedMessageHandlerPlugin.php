<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\PaymentPreauthorizationFailedTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface;

/**
 * @method \Spryker\Zed\Payment\Business\PaymentFacadeInterface getFacade()
 * @method \Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Payment\PaymentConfig getConfig()
 * @method \Spryker\Zed\Payment\Communication\PaymentCommunicationFactory getFactory()
 */
class PaymentPreauthorizationFailedMessageHandlerPlugin extends AbstractPlugin implements MessageHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Triggers an OMS event for PaymentPreauthorizationFailedTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentPreauthorizationFailedTransfer $paymentPreauthorizationFailedTransfer
     *
     * @return void
     */
    public function onPaymentPreauthorizationFailed(PaymentPreauthorizationFailedTransfer $paymentPreauthorizationFailedTransfer): void
    {
        $this->getFacade()->triggerPaymentMessageOmsEvent($paymentPreauthorizationFailedTransfer);
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
        yield PaymentPreauthorizationFailedTransfer::class => [$this, 'onPaymentPreauthorizationFailed'];
    }
}

<?php
// phpcs:ignoreFile
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\AddPaymentMethodTransfer;
use Generated\Shared\Transfer\DeletePaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentMethodAddedTransfer;
use Generated\Shared\Transfer\PaymentMethodDeletedTransfer;
use Generated\Shared\Transfer\UpdatePaymentMethodTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface;

/**
 * @method \Spryker\Zed\Payment\Business\PaymentFacadeInterface getFacade()
 * @method \Spryker\Zed\Payment\Communication\PaymentCommunicationFactory getFactory()
 * @method \Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Payment\PaymentConfig getConfig()
 */
class PaymentMethodMessageHandlerPlugin extends AbstractPlugin implements MessageHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return iterable
     */
    public function handles(): iterable
    {
        yield AddPaymentMethodTransfer::class => [$this->getFacade(), 'consumePaymentMethodMessage'];
        yield UpdatePaymentMethodTransfer::class => [$this->getFacade(), 'consumePaymentMethodMessage'];
        yield DeletePaymentMethodTransfer::class => [$this->getFacade(), 'consumePaymentMethodMessage'];
            // @deprecated
        yield PaymentMethodAddedTransfer::class => [$this, 'onPaymentMethodAdded'];
        yield PaymentMethodDeletedTransfer::class => [$this, 'onPaymentMethodDeleted'];
    }

    /**
     * @deprecated Don't use this method directly, this method is only used for BC reasons.
     *
     * @param \Generated\Shared\Transfer\PaymentMethodAddedTransfer $paymentMethodAddedTransfer
     *
     * @return void
     */
    public function onPaymentMethodAdded(PaymentMethodAddedTransfer $paymentMethodAddedTransfer): void
    {
        $addPaymentMethodTransfer = (new AddPaymentMethodTransfer())->fromArray($paymentMethodAddedTransfer->toArray(), true);

        $this->getFacade()->addPaymentMethod($addPaymentMethodTransfer);
    }

    /**
     * @deprecated Don't use this method directly, this method is only used for BC reasons.
     *
     * @param \Generated\Shared\Transfer\PaymentMethodDeletedTransfer $paymentMethodDeletedTransfer
     *
     * @return void
     */
    public function onPaymentMethodDeleted(PaymentMethodDeletedTransfer $paymentMethodDeletedTransfer): void
    {
        $deletePaymentMethodTransfer = (new DeletePaymentMethodTransfer())->fromArray($paymentMethodDeletedTransfer->toArray(), true);

        $this->getFacade()->deletePaymentMethod($deletePaymentMethodTransfer);
    }
}

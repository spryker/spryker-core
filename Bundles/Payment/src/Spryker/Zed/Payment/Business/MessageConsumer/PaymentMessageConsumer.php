<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\MessageConsumer;

use Generated\Shared\Transfer\AddPaymentMethodTransfer;
use Generated\Shared\Transfer\DeletePaymentMethodTransfer;
use Generated\Shared\Transfer\UpdatePaymentMethodTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\Payment\Business\Exception\PaymentMethodMessageConsumeException;
use Spryker\Zed\Payment\Business\Method\PaymentMethodUpdaterInterface;

class PaymentMessageConsumer implements PaymentMessageConsumerInterface
{
    protected PaymentMethodUpdaterInterface $paymentMethodUpdater;

    /**
     * @param \Spryker\Zed\Payment\Business\Method\PaymentMethodUpdaterInterface $paymentMethodUpdater
     */
    public function __construct(PaymentMethodUpdaterInterface $paymentMethodUpdater)
    {
        $this->paymentMethodUpdater = $paymentMethodUpdater;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $messageTransfer
     *
     * @throws \Spryker\Zed\Payment\Business\Exception\PaymentMethodMessageConsumeException
     *
     * @return void
     */
    public function consumePaymentMessage(AbstractTransfer $messageTransfer): void
    {
        match (get_class($messageTransfer)) {
            AddPaymentMethodTransfer::class => $this->addPaymentMethod($messageTransfer),
            UpdatePaymentMethodTransfer::class => $this->updatePaymentMethod($messageTransfer),
            DeletePaymentMethodTransfer::class => $this->deletePaymentMethod($messageTransfer),
            default => throw new PaymentMethodMessageConsumeException(sprintf(
                'Unsupported message type. Expected one of: "%s", "%s", "%s" but got "%s".',
                AddPaymentMethodTransfer::class,
                UpdatePaymentMethodTransfer::class,
                DeletePaymentMethodTransfer::class,
                get_class($messageTransfer),
            ))
        };
    }

    /**
     * @param \Generated\Shared\Transfer\AddPaymentMethodTransfer $addPaymentMethodTransfer
     *
     * @return void
     */
    protected function addPaymentMethod(AddPaymentMethodTransfer $addPaymentMethodTransfer): void
    {
        $this->paymentMethodUpdater->addPaymentMethod($addPaymentMethodTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UpdatePaymentMethodTransfer $updatePaymentMethodTransfer
     *
     * @return void
     */
    protected function updatePaymentMethod(UpdatePaymentMethodTransfer $updatePaymentMethodTransfer): void
    {
        $this->paymentMethodUpdater->updatePaymentMethod($updatePaymentMethodTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DeletePaymentMethodTransfer $deletePaymentMethodTransfer
     *
     * @return void
     */
    protected function deletePaymentMethod(DeletePaymentMethodTransfer $deletePaymentMethodTransfer): void
    {
        $this->paymentMethodUpdater->deletePaymentMethod($deletePaymentMethodTransfer);
    }
}

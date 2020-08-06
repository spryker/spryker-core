<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Method;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PaymentMethodResponseTransfer;
use Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface;

class PaymentMethodFinder implements PaymentMethodFinderInterface
{
    protected const MESSAGE_PAYMENT_METHOD_NOT_FOUND = 'Payment method not found';

    /**
     * @var \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface
     */
    protected $paymentRepository;

    /**
     * @param \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface $paymentRepository
     */
    public function __construct(PaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @param int $idPaymentMethod
     *
     * @return \Generated\Shared\Transfer\PaymentMethodResponseTransfer
     */
    public function findPaymentMethodById(int $idPaymentMethod): PaymentMethodResponseTransfer
    {
        $paymentMethodResponseTransfer = new PaymentMethodResponseTransfer();
        $paymentMethodTransfer = $this->paymentRepository->findPaymentMethodById($idPaymentMethod);

        if ($paymentMethodTransfer === null) {
            $paymentMethodResponseTransfer->setIsSuccessful(false);
            $paymentMethodResponseTransfer->addMessage($this->getMessageNotFound());

            return $paymentMethodResponseTransfer;
        }

        $paymentMethodTransfer->requirePaymentProvider();

        $paymentMethodResponseTransfer->setPaymentMethod($paymentMethodTransfer)
            ->setIsSuccessful(true);

        return $paymentMethodResponseTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function getMessageNotFound(): MessageTransfer
    {
        return (new MessageTransfer())->setValue(static::MESSAGE_PAYMENT_METHOD_NOT_FOUND);
    }
}

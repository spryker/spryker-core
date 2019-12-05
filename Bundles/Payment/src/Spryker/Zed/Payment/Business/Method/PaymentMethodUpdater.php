<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Method;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PaymentMethodResponseTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface;

class PaymentMethodUpdater implements PaymentMethodUpdaterInterface
{
    use TransactionTrait;

    protected const MESSAGE_UPDATE_ERROR = 'It is impossible to update this payment method';

    /**
     * @var \Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface
     */
    protected $paymentEntityManager;

    /**
     * @var \Spryker\Zed\Payment\Business\Method\PaymentMethodStoreRelationUpdaterInterface
     */
    protected $storeRelationUpdater;

    /**
     * @param \Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface $paymentEntityManager
     * @param \Spryker\Zed\Payment\Business\Method\PaymentMethodStoreRelationUpdaterInterface $storeRelationUpdater
     */
    public function __construct(
        PaymentEntityManagerInterface $paymentEntityManager,
        PaymentMethodStoreRelationUpdaterInterface $storeRelationUpdater
    ) {
        $this->paymentEntityManager = $paymentEntityManager;
        $this->storeRelationUpdater = $storeRelationUpdater;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodResponseTransfer
     */
    public function updatePaymentMethod(
        PaymentMethodTransfer $paymentMethodTransfer
    ): PaymentMethodResponseTransfer {
        return $this->getTransactionHandler()->handleTransaction(function () use ($paymentMethodTransfer) {
            return $this->executeUpdatePaymentMethodTransaction($paymentMethodTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodResponseTransfer
     */
    protected function executeUpdatePaymentMethodTransaction(
        PaymentMethodTransfer $paymentMethodTransfer
    ): PaymentMethodResponseTransfer {
        $paymentMethodTransfer->requireIdPaymentMethod()
            ->requireStoreRelation();

        $storeRelationTransfer = $paymentMethodTransfer->getStoreRelation()
            ->setIdEntity($paymentMethodTransfer->getIdPaymentMethod());

        $paymentMethodTransfer = $this->paymentEntityManager
            ->updatePaymentMethod($paymentMethodTransfer);

        if ($paymentMethodTransfer === null) {
            return (new PaymentMethodResponseTransfer())
                ->setIsSuccessful(false)
                ->addMessage($this->getErrorMessageTransfer(static::MESSAGE_UPDATE_ERROR));
        }

        $this->storeRelationUpdater->update($storeRelationTransfer);

        return (new PaymentMethodResponseTransfer())
            ->setIsSuccessful(true)
            ->setPaymentMethod($paymentMethodTransfer);
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function getErrorMessageTransfer(string $message): MessageTransfer
    {
        return (new MessageTransfer())->setValue($message);
    }
}

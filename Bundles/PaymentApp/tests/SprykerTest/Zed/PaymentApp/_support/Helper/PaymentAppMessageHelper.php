<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\PaymentApp\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Generated\Shared\DataBuilder\PaymentAuthorizationFailedBuilder;
use Generated\Shared\DataBuilder\PaymentAuthorizedBuilder;
use Generated\Shared\DataBuilder\PaymentCanceledBuilder;
use Generated\Shared\DataBuilder\PaymentCancellationFailedBuilder;
use Generated\Shared\DataBuilder\PaymentCapturedBuilder;
use Generated\Shared\DataBuilder\PaymentCaptureFailedBuilder;
use Generated\Shared\DataBuilder\PaymentOverpaidBuilder;
use Generated\Shared\DataBuilder\PaymentUnderpaidBuilder;
use Generated\Shared\Transfer\PaymentAuthorizedTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Shared\PaymentApp\Status\PaymentStatus;
use Spryker\Zed\TestifyAsyncApi\Business\Codeception\Helper\AsyncApiHelperTrait;
use SprykerTest\Shared\Sales\Helper\SalesOmsHelperTrait;

class PaymentAppMessageHelper extends Module
{
    use SalesOmsHelperTrait;
    use AsyncApiHelperTrait;

    protected string $transactionReference;

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        parent::_before($test);

        $this->transactionReference = Uuid::uuid4()->toString();
        $this->getSalesOmsHelper()->setupStateMachine();

        $this->getSalesOmsHelper()->haveOrderItemInState(PaymentStatus::STATUS_NEW);
    }

    /**
     * @param array $seed
     *
     * @return void
     */
    public function receivePaymentCanceledMessage(array $seed = []): void
    {
        $seed = $this->addDefaultDataToSeed($seed);

        $paymentCanceledBuilder = new PaymentCanceledBuilder($seed);
        $paymentCanceledTransfer = $paymentCanceledBuilder
            ->withMessageAttributes()
            ->build();

        // Act
        $this->getAsyncApiHelper()->runMessageReceiveTest($paymentCanceledTransfer, 'payment-events');
    }

    /**
     * @param array $seed
     *
     * @return void
     */
    public function receivePaymentCancellationFailedMessage(array $seed = []): void
    {
        $seed = $this->addDefaultDataToSeed($seed);

        $paymentCancellationFailedBuilder = new PaymentCancellationFailedBuilder($seed);
        $cancellationFailedTransfer = $paymentCancellationFailedBuilder
            ->withMessageAttributes()
            ->build();

        // Act
        $this->getAsyncApiHelper()->runMessageReceiveTest($cancellationFailedTransfer, 'payment-events');
    }

    /**
     * @param array $seed
     *
     * @return void
     */
    public function receivePaymentCapturedMessage(array $seed = []): void
    {
        $seed = $this->addDefaultDataToSeed($seed);

        $paymentCapturedBuilder = new PaymentCapturedBuilder($seed);
        $paymentCapturedTransfer = $paymentCapturedBuilder
            ->withMessageAttributes()
            ->build();

        // Act
        $this->getAsyncApiHelper()->runMessageReceiveTest($paymentCapturedTransfer, 'payment-events');
    }

    /**
     * @param array $seed
     *
     * @return void
     */
    public function receivePaymentCaptureFailedMessage(array $seed = []): void
    {
        $seed = $this->addDefaultDataToSeed($seed);

        $paymentCaptureFailedBuilder = new PaymentCaptureFailedBuilder($seed);
        $paymentCaptureFailedTransfer = $paymentCaptureFailedBuilder
            ->withMessageAttributes()
            ->build();

        // Act
        $this->getAsyncApiHelper()->runMessageReceiveTest($paymentCaptureFailedTransfer, 'payment-events');
    }

    /**
     * @param array $seed
     *
     * @return void
     */
    public function receivePaymentAuthorizedMessage(array $seed = []): void
    {
        $seed = $this->addDefaultDataToSeed($seed);

        $paymentAuthorizedBuilder = new PaymentAuthorizedBuilder($seed);
        $paymentAuthorizedTransfer = $paymentAuthorizedBuilder
            ->withMessageAttributes()
            ->build();

        // Act
        $this->getAsyncApiHelper()->runMessageReceiveTest($paymentAuthorizedTransfer, 'payment-events');
    }

    /**
     * @param array $seed
     *
     * @return void
     */
    public function receivePaymentAuthorizationFailedMessage(array $seed = []): void
    {
        $seed = $this->addDefaultDataToSeed($seed);

        $paymentAuthorizationFailedBuilder = new PaymentAuthorizationFailedBuilder($seed);
        $authorizationFailedTransfer = $paymentAuthorizationFailedBuilder
            ->withMessageAttributes()
            ->build();

        // Act
        $this->getAsyncApiHelper()->runMessageReceiveTest($authorizationFailedTransfer, 'payment-events');
    }

    /**
     * @param array $seed
     *
     * @return void
     */
    public function receivePaymentOverpaidMessage(array $seed = []): void
    {
        $seed = $this->addDefaultDataToSeed($seed);

        $paymentOverpaidBuilder = new PaymentOverpaidBuilder($seed);
        $paymentOverpaidTransfer = $paymentOverpaidBuilder
            ->withMessageAttributes()
            ->build();

        // Act
        $this->getAsyncApiHelper()->runMessageReceiveTest($paymentOverpaidTransfer, 'payment-events');
    }

    /**
     * @param array $seed
     *
     * @return void
     */
    public function receivePaymentUnderpaidMessage(array $seed = []): void
    {
        $seed = $this->addDefaultDataToSeed($seed);

        $paymentUnderpaidBuilder = new PaymentUnderpaidBuilder($seed);
        $paymentUnderpaidTransfer = $paymentUnderpaidBuilder
            ->withMessageAttributes()
            ->build();

        // Act
        $this->getAsyncApiHelper()->runMessageReceiveTest($paymentUnderpaidTransfer, 'payment-events');
    }

    /**
     * @param array $seed
     *
     * @return array
     */
    protected function addDefaultDataToSeed(array $seed = []): array
    {
        return array_merge($seed, [
            PaymentAuthorizedTransfer::ORDER_REFERENCE => $seed[PaymentAuthorizedTransfer::ORDER_REFERENCE] ?? $this->getSalesOmsHelper()->getOrderReference(),
            PaymentAuthorizedTransfer::ORDER_ITEM_IDS => $seed[PaymentAuthorizedTransfer::ORDER_ITEM_IDS] ?? [$this->getSalesOmsHelper()->getSalesOrderItemEntity()->getOrderItemReference()],
        ]);
    }
}

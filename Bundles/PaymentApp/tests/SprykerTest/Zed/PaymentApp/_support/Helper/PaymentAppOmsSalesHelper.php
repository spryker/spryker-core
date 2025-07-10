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
use Generated\Shared\Transfer\PaymentAuthorizedTransfer;
use Generated\Shared\Transfer\PaymentCreatedTransfer;
use Generated\Shared\Transfer\PaymentUpdatedTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Zed\TestifyAsyncApi\Business\Codeception\Helper\AsyncApiHelperTrait;
use SprykerTest\Shared\Sales\Helper\SalesOmsHelperTrait;

class PaymentAppOmsSalesHelper extends Module
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
    }

    /**
     * @param array $seed
     *
     * @return void
     */
    public function receivePaymentCanceled(array $seed = []): void
    {
        $seed = $this->addDefaultDataToSeed($seed);

        $paymentCanceledBuilder = new PaymentCanceledBuilder($seed);
        $paymentCanceledTransfer = $paymentCanceledBuilder->build();

        // Act
        $this->getAsyncApiHelper()->runMessageReceiveTest($paymentCanceledTransfer, 'payment-events');
    }

    /**
     * @param array $seed
     *
     * @return void
     */
    public function receivePaymentCancellationFailed(array $seed = []): void
    {
        $seed = $this->addDefaultDataToSeed($seed);

        $paymentCancellationFailedBuilder = new PaymentCancellationFailedBuilder($seed);
        $cancellationFailedTransfer = $paymentCancellationFailedBuilder->build();

        // Act
        $this->getAsyncApiHelper()->runMessageReceiveTest($cancellationFailedTransfer, 'payment-events');
    }

    /**
     * @param array $seed
     *
     * @return void
     */
    public function receivePaymentCaptured(array $seed = []): void
    {
        $seed = $this->addDefaultDataToSeed($seed);

        $paymentCapturedBuilder = new PaymentCapturedBuilder($seed);
        $paymentCapturedTransfer = $paymentCapturedBuilder->build();

        // Act
        $this->getAsyncApiHelper()->runMessageReceiveTest($paymentCapturedTransfer, 'payment-events');
    }

    /**
     * @param array $seed
     *
     * @return void
     */
    public function receivePaymentCaptureFailed(array $seed = []): void
    {
        $seed = $this->addDefaultDataToSeed($seed);

        $paymentCaptureFailedBuilder = new PaymentCaptureFailedBuilder($seed);
        $paymentCaptureFailedTransfer = $paymentCaptureFailedBuilder->build();

        // Act
        $this->getAsyncApiHelper()->runMessageReceiveTest($paymentCaptureFailedTransfer, 'payment-events');
    }

    /**
     * @param array $seed
     *
     * @return void
     */
    public function receivePaymentAuthorized(array $seed = []): void
    {
        $seed = $this->addDefaultDataToSeed($seed);

        $paymentAuthorizedBuilder = new PaymentAuthorizedBuilder($seed);
        $paymentAuthorizedTransfer = $paymentAuthorizedBuilder->build();

        // Act
        $this->getAsyncApiHelper()->runMessageReceiveTest($paymentAuthorizedTransfer, 'payment-events');
    }

    /**
     * @param array $seed
     *
     * @return void
     */
    public function receivePaymentAuthorizationFailed(array $seed = []): void
    {
        $seed = $this->addDefaultDataToSeed($seed);

        $paymentAuthorizationFailedBuilder = new PaymentAuthorizationFailedBuilder($seed);
        $authorizationFailedTransfer = $paymentAuthorizationFailedBuilder->build();

        // Act
        $this->getAsyncApiHelper()->runMessageReceiveTest($authorizationFailedTransfer, 'payment-events');
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

    /**
     * @param string $sourceStatus
     * @param string $targetStatus
     *
     * @return void
     */
    public function receivePaymentCreatedMessage(string $sourceStatus, string $targetStatus): void
    {
        $salesOmsHelper = $this->getSalesOmsHelper();

        $paymentCreatedTransfer = new PaymentCreatedTransfer();
        $paymentCreatedTransfer
            ->setEntityReference($salesOmsHelper->getOrderReference())
            ->setPaymentReference($this->transactionReference)
            ->setDetails(json_encode(['sourceStatus' => $sourceStatus, 'targetStatus' => $targetStatus]));

        // Act
        $this->getAsyncApiHelper()->runMessageReceiveTest($paymentCreatedTransfer, 'payment-events');
    }

    /**
     * @param string $sourceStatus
     * @param string $targetStatus
     *
     * @return void
     */
    public function receivePaymentUpdatedMessage(string $sourceStatus, string $targetStatus): void
    {
        $salesOmsHelper = $this->getSalesOmsHelper();

        $paymentUpdatedTransfer = new PaymentUpdatedTransfer();
        $paymentUpdatedTransfer
            ->setEntityReference($salesOmsHelper->getOrderReference())
            ->setPaymentReference($this->transactionReference)
            ->setDetails(json_encode(['sourceStatus' => $sourceStatus, 'targetStatus' => $targetStatus]));

        // Act
        $this->getAsyncApiHelper()->runMessageReceiveTest($paymentUpdatedTransfer, 'payment-events');
    }
}

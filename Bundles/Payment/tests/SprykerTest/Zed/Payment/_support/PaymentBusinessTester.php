<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Payment;

use Codeception\Actor;
use Generated\Shared\DataBuilder\PaymentMethodAddedBuilder;
use Generated\Shared\DataBuilder\PaymentMethodBuilder;
use Generated\Shared\DataBuilder\PaymentMethodDeletedBuilder;
use Generated\Shared\DataBuilder\StoreBuilder;
use Generated\Shared\Transfer\PaymentMethodAddedTransfer;
use Generated\Shared\Transfer\PaymentMethodDeletedTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\StoreTransfer;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class PaymentBusinessTester extends Actor
{
    use _generated\PaymentBusinessTesterActions;

    /**
     * @param array<mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function getPaymentMethodTransfer(array $seedData = []): PaymentMethodTransfer
    {
        return (new PaymentMethodBuilder($seedData))->build();
    }

    /**
     * @param array<mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreTransfer(array $seedData = []): StoreTransfer
    {
        return (new StoreBuilder($seedData))->build();
    }

    /**
     * @param array<mixed> $seedData
     * @param array<mixed> $messageAttributesSeedData
     *
     * @return \Generated\Shared\Transfer\PaymentMethodAddedTransfer
     */
    public function getPaymentMethodAddedTransfer(array $seedData = [], array $messageAttributesSeedData = []): PaymentMethodAddedTransfer
    {
        return (new PaymentMethodAddedBuilder($seedData))
            ->withMessageAttributes($messageAttributesSeedData)
            ->build();
    }

    /**
     * @param array<mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\PaymentMethodDeletedTransfer
     */
    public function getPaymentMethodDeletedTransfer(array $seedData = []): PaymentMethodDeletedTransfer
    {
        return (new PaymentMethodDeletedBuilder($seedData))->build();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodDeletedTransfer $paymentMethodDeletedTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodDeletedTransfer
     */
    public function mapPaymentMethodTransferToPaymentMethodDeletedTransfer(
        PaymentMethodTransfer $paymentMethodTransfer,
        PaymentMethodDeletedTransfer $paymentMethodDeletedTransfer
    ): PaymentMethodDeletedTransfer {
        $paymentMethodDeletedTransfer
            ->setName($paymentMethodTransfer->getLabelName())
            ->setProviderName($paymentMethodTransfer->getGroupName());

        return $paymentMethodDeletedTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodAddedTransfer $paymentMethodAddedTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodAddedTransfer
     */
    public function mapPaymentMethodTransferToPaymentMethodAddedTransfer(
        PaymentMethodTransfer $paymentMethodTransfer,
        PaymentMethodAddedTransfer $paymentMethodAddedTransfer
    ): PaymentMethodAddedTransfer {
        $paymentMethodAddedTransfer
            ->setName($paymentMethodTransfer->getLabelName())
            ->setProviderName($paymentMethodTransfer->getGroupName())
            ->setPaymentAuthorizationEndpoint($paymentMethodTransfer->getPaymentAuthorizationEndpoint());

        return $paymentMethodAddedTransfer;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\PaymentApp\Communication\Plugin\Oms;

use Spryker\Shared\PaymentApp\Status\PaymentStatus;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;
use Spryker\Zed\PaymentApp\Communication\Plugin\Oms\IsPaymentAppPaymentStatusAuthorizationFailedConditionPlugin;
use SprykerTest\Zed\PaymentApp\PaymentAppCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PaymentApp
 * @group Communication
 * @group Plugin
 * @group Oms
 * @group IsPaymentAppPaymentAppPaymentStatusAuthorizationFailedStatusConditionPluginTest
 * Add your own group annotations below this line
 */
class IsPaymentAppPaymentAppPaymentStatusAuthorizationFailedStatusConditionPluginTest extends AbstractIsPaymentAppPaymentStatusConditionPluginTest
{
    protected PaymentAppCommunicationTester $tester;

    /**
     * @inheritDoc
     */
    public static function statusProvider(): array
    {
        return [
            PaymentStatus::STATUS_NEW . ' payments will NOT MATCH' => [PaymentStatus::STATUS_NEW, false],
            PaymentStatus::STATUS_AUTHORIZED . ' payments will NOT MATCH' => [PaymentStatus::STATUS_AUTHORIZED, false],
            PaymentStatus::STATUS_AUTHORIZATION_FAILED . ' payments will MATCH' => [PaymentStatus::STATUS_AUTHORIZATION_FAILED, true], // End state
            PaymentStatus::STATUS_CAPTURED . ' payments will NOT MATCH' => [PaymentStatus::STATUS_CAPTURED, false],
            PaymentStatus::STATUS_CAPTURE_FAILED . ' payments will NOT MATCH' => [PaymentStatus::STATUS_CAPTURE_FAILED, false],
            PaymentStatus::STATUS_CAPTURE_REQUESTED . ' payments will NOT MATCH' => [PaymentStatus::STATUS_CAPTURE_REQUESTED, false],
            PaymentStatus::STATUS_CANCELED . ' payments will NOT MATCH' => [PaymentStatus::STATUS_CANCELED, false],
            PaymentStatus::STATUS_CANCELLATION_FAILED . ' payments will NOT MATCH' => [PaymentStatus::STATUS_CANCELLATION_FAILED, false], // End state
            PaymentStatus::STATUS_OVERPAID . ' payments will NOT MATCH' => [PaymentStatus::STATUS_OVERPAID, false], // End state
            PaymentStatus::STATUS_UNDERPAID . ' payments will NOT MATCH' => [PaymentStatus::STATUS_UNDERPAID, false], // End state
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getConditionPluginToTest(): ConditionInterface
    {
        return new IsPaymentAppPaymentStatusAuthorizationFailedConditionPlugin();
    }
}

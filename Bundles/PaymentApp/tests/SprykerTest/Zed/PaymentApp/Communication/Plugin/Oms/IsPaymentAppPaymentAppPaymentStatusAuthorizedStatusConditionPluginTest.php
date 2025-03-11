<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\PaymentApp\Communication\Plugin\Oms;

use Spryker\Shared\PaymentApp\Status\PaymentStatus;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;
use Spryker\Zed\PaymentApp\Communication\Plugin\Oms\IsPaymentAppPaymentStatusAuthorizedConditionPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PaymentApp
 * @group Communication
 * @group Plugin
 * @group Oms
 * @group IsPaymentAppPaymentAppPaymentStatusAuthorizedStatusConditionPluginTest
 * Add your own group annotations below this line
 */
class IsPaymentAppPaymentAppPaymentStatusAuthorizedStatusConditionPluginTest extends AbstractIsPaymentAppPaymentStatusConditionPluginTest
{
    /**
     * @inheritDoc
     */
    public static function statusProvider(): array
    {
        return [
            PaymentStatus::STATUS_NEW . ' payments will NOT MATCH' => [PaymentStatus::STATUS_NEW, false],
            PaymentStatus::STATUS_AUTHORIZED . ' payments will MATCH' => [PaymentStatus::STATUS_AUTHORIZED, true],
            PaymentStatus::STATUS_AUTHORIZATION_FAILED . ' payments will NOT MATCH' => [PaymentStatus::STATUS_AUTHORIZATION_FAILED, false], // End state
            PaymentStatus::STATUS_CAPTURED . ' payments will MATCH' => [PaymentStatus::STATUS_CAPTURED, true], // Was PaymentStatus::STATUS_AUTHORIZED before
            PaymentStatus::STATUS_CAPTURE_FAILED . ' payments will MATCH' => [PaymentStatus::STATUS_CAPTURE_FAILED, true], // Was PaymentStatus::STATUS_AUTHORIZED before
            PaymentStatus::STATUS_CAPTURE_REQUESTED . ' payments will NOT MATCH' => [PaymentStatus::STATUS_CAPTURE_REQUESTED, false],
            PaymentStatus::STATUS_CANCELED . ' payments will NOT MATCH' => [PaymentStatus::STATUS_CANCELED, false],
            PaymentStatus::STATUS_CANCELLATION_FAILED . ' payments will NOT MATCH' => [PaymentStatus::STATUS_CANCELLATION_FAILED, false], // End state
            PaymentStatus::STATUS_OVERPAID . ' payments will MATCH' => [PaymentStatus::STATUS_OVERPAID, true], // End state
            PaymentStatus::STATUS_UNDERPAID . ' payments will MATCH' => [PaymentStatus::STATUS_UNDERPAID, true], // End state
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getConditionPluginToTest(): ConditionInterface
    {
        return new IsPaymentAppPaymentStatusAuthorizedConditionPlugin();
    }
}

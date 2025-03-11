<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\PaymentApp\Communication\Plugin\Oms;

use Codeception\Test\Unit;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;
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
 * @group AbstractIsPaymentAppPaymentStatusConditionPluginTest
 * Add your own group annotations below this line
 */
abstract class AbstractIsPaymentAppPaymentStatusConditionPluginTest extends Unit
{
    protected PaymentAppCommunicationTester $tester;

    /**
     * @return array<string, array<string|bool>>
     */
    abstract public static function statusProvider(): array;

    /**
     * @return \Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface
     */
    abstract protected function getConditionPluginToTest(): ConditionInterface;

    /**
     * @dataProvider statusProvider
     *
     * @param string $paymentStatus
     * @param bool $expectedConditionResult
     *
     * @return void
     */
    public function testConditionReturnsExpectedBooleanBasedOnTheCurrentStateOfAPayment(string $paymentStatus, bool $expectedConditionResult): void
    {
        // Arrange
        $salesOrderEntity = $this->tester->haveSalesOrderEntity();

        $salesOrderItem = new SpySalesOrderItem();
        $salesOrderItem
            ->setOrder($salesOrderEntity);

        $this->tester->havePaymentAppPaymentStatusEntity($salesOrderEntity->getOrderReference(), $paymentStatus);

        // Act
        $isPaymentConditionPlugin = $this->getConditionPluginToTest();
        $isConditionMet = $isPaymentConditionPlugin->check($salesOrderItem);

        // Assert
        $shouldMatch = str_ends_with($this->dataName(), 'will MATCH') ? 'match' : 'not match';

        // Assert that the data set name is testing the correct payment status
        $this->assertStringStartsWith($paymentStatus, $this->dataName(), sprintf('Your data set name "%s" does not start with the payment status "%s"', $this->dataName(), $paymentStatus));

        // Assert that the data set name is testing the correct condition result
        $this->assertStringContainsString($expectedConditionResult ? 'will MATCH' : 'will NOT MATCH', $this->dataName(), sprintf('Your data set name "%s" indicates that the condition should "%s" but your $expectedConditionResult "%s" says the opposite', $this->dataName(), $shouldMatch, $expectedConditionResult ? 'true' : 'false'));
        $this->assertSame($expectedConditionResult, $isConditionMet, sprintf('Expected that when the payment is in state "%s" the condition returns "%s"', $paymentStatus, $expectedConditionResult ? 'true' : 'false'));
    }

    /**
     * @param string $paymentStatus
     *
     * @return string
     */
    protected function paymentStatusToCamelCase(string $paymentStatus): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($paymentStatus))));
    }
}

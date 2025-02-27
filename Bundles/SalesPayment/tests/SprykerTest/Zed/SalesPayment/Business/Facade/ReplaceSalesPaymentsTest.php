<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesPayment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesPaymentTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\SalesPayment\SalesPaymentDependencyProvider;
use Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\PaymentMapKeyBuilderStrategyPluginInterface;
use Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\SalesPaymentPreDeletePluginInterface;
use SprykerTest\Zed\SalesPayment\SalesPaymentBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesPayment
 * @group Business
 * @group Facade
 * @group ReplaceSalesPaymentsTest
 * Add your own group annotations below this line
 */
class ReplaceSalesPaymentsTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\SalesPayment\SalesPaymentBusinessTester
     */
    protected SalesPaymentBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testThrowsNullValueExceptionWhenIdSalesOrderIsNotSetInSaveOrderTransfer(): void
    {
        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "idSalesOrder" of transfer `Generated\Shared\Transfer\SaveOrderTransfer` is null.');

        // Act
        $this->tester->getFacade()->replaceSalesPayments(new QuoteTransfer(), new SaveOrderTransfer());
    }

    /**
     * @return void
     */
    public function testExecutesSalesPaymentPreDeletePluginInterfacePluginStackBeforeEntityDeletion(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $this->haveSalesPayment($saveOrderTransfer, 100, 'Payment Provider 1', 'Payment Method 1');

        $salesPaymentPreDeletePluginMock = $this->getSalesPaymentPreDeletePluginMock();

        // Assert
        $salesPaymentPreDeletePluginMock
            ->expects($this->once())
            ->method('preDelete');

        // Act
        $this->tester->getFacade()->replaceSalesPayments(new QuoteTransfer(), $saveOrderTransfer);
    }

    /**
     * @return void
     */
    public function testDoesNotExecuteSalesPaymentPreDeletePluginInterfacePluginStackWhenThereAreNoEntitiesToDelete(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $salesPaymentPreDeletePluginMock = $this->getSalesPaymentPreDeletePluginMock();

        // Assert
        $salesPaymentPreDeletePluginMock
            ->expects($this->never())
            ->method('preDelete');

        // Act
        $this->tester->getFacade()->replaceSalesPayments(new QuoteTransfer(), $saveOrderTransfer);
    }

    /**
     * @return void
     */
    public function testExecutesPaymentMapKeyBuilderStrategyPluginInterfacePluginStack(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $paymentMapKeyBuilderStrategyPluginMock = $this->getPaymentMapKeyBuilderStrategyPluginMock();
        $quoteTransfer = (new QuoteTransfer())->addPayment(
            (new PaymentTransfer())
                ->setAmount(100)
                ->setPaymentProvider('Payment Provider 1')
                ->setPaymentMethod('Payment Method 1'),
        );

        // Assert
        $paymentMapKeyBuilderStrategyPluginMock
            ->expects($this->once())
            ->method('isApplicable');

        // Act
        $this->tester->getFacade()->replaceSalesPayments($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * @return void
     */
    public function testReplacesOrderRelatedPaymentAndDoesNotAffectOtherPayments(): void
    {
        // Arrange
        $saveOrderTransfer1 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $saveOrderTransfer2 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $this->haveSalesPayment($saveOrderTransfer1, 100, 'Payment Provider 1', 'Payment Method 1');
        $this->haveSalesPayment($saveOrderTransfer2, 200, 'Payment Provider 2', 'Payment Method 2');

        $quoteTransfer = (new QuoteTransfer())->addPayment(
            (new PaymentTransfer())
                ->setAmount(300)
                ->setPaymentProvider('Payment Provider 3')
                ->setPaymentMethod('Payment Method 3'),
        );

        // Act
        $this->tester->getFacade()->replaceSalesPayments($quoteTransfer, $saveOrderTransfer1);

        // Assert
        $salesPaymentTransfers = $this->tester->getSalesPaymentsIndexedByIdSalesOrder([
            $saveOrderTransfer1->getIdSalesOrderOrFail(),
            $saveOrderTransfer2->getIdSalesOrderOrFail(),
        ]);

        $this->assertCount(2, $salesPaymentTransfers);
        $this->assertSalesPayment($salesPaymentTransfers, $saveOrderTransfer1, 300, 'Payment Provider 3', 'Payment Method 3');
        $this->assertSalesPayment($salesPaymentTransfers, $saveOrderTransfer2, 200, 'Payment Provider 2', 'Payment Method 2');
    }

    /**
     * @return void
     */
    public function testAddsNewPaymentWhenNoOtherOrderRelatedPaymentsExist(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $quoteTransfer = (new QuoteTransfer())->addPayment(
            (new PaymentTransfer())
                ->setAmount(100)
                ->setPaymentProvider('Payment Provider 1')
                ->setPaymentMethod('Payment Method 1'),
        );

        // Act
        $this->tester->getFacade()->replaceSalesPayments($quoteTransfer, $saveOrderTransfer);

        // Assert
        $salesPaymentTransfers = $this->tester->getSalesPaymentsIndexedByIdSalesOrder([$saveOrderTransfer->getIdSalesOrderOrFail()]);

        $this->assertCount(1, $salesPaymentTransfers);
        $this->assertSalesPayment($salesPaymentTransfers, $saveOrderTransfer, 100, 'Payment Provider 1', 'Payment Method 1');
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\SalesPaymentTransfer> $salesPaymentTransfers
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param int $amount
     * @param string $paymentProvider
     * @param string $paymentMethod
     *
     * @return void
     */
    protected function assertSalesPayment(
        array $salesPaymentTransfers,
        SaveOrderTransfer $saveOrderTransfer,
        int $amount,
        string $paymentProvider,
        string $paymentMethod
    ): void {
        $this->assertArrayHasKey($saveOrderTransfer->getIdSalesOrderOrFail(), $salesPaymentTransfers);
        $salesPaymentTransfer1 = $salesPaymentTransfers[$saveOrderTransfer->getIdSalesOrderOrFail()];
        $this->assertSame($amount, $salesPaymentTransfer1->getAmount());
        $this->assertSame($paymentProvider, $salesPaymentTransfer1->getPaymentProvider());
        $this->assertSame($paymentMethod, $salesPaymentTransfer1->getPaymentMethod());
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param int $amount
     * @param string $paymentProvider
     * @param string $paymentMethod
     *
     * @return void
     */
    protected function haveSalesPayment(
        SaveOrderTransfer $saveOrderTransfer,
        int $amount,
        string $paymentProvider,
        string $paymentMethod
    ): void {
        $salesPaymentTransfer = (new SalesPaymentTransfer())
            ->setFkSalesOrder($saveOrderTransfer->getIdSalesOrderOrFail())
            ->setAmount($amount)
            ->setPaymentProvider($paymentProvider)
            ->setPaymentMethod($paymentMethod);
        $this->tester->haveSalesPaymentEntities([$salesPaymentTransfer]);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\SalesPaymentPreDeletePluginInterface
     */
    protected function getSalesPaymentPreDeletePluginMock(): SalesPaymentPreDeletePluginInterface
    {
        $salesPaymentPreDeletePluginMock = $this->getMockBuilder(SalesPaymentPreDeletePluginInterface::class)->getMock();
        $this->tester->setDependency(SalesPaymentDependencyProvider::PLUGINS_SALES_PAYMENT_PRE_DELETE, [$salesPaymentPreDeletePluginMock]);

        return $salesPaymentPreDeletePluginMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\PaymentMapKeyBuilderStrategyPluginInterface
     */
    protected function getPaymentMapKeyBuilderStrategyPluginMock(): PaymentMapKeyBuilderStrategyPluginInterface
    {
        $paymentMapKeyBuilderStrategyPluginMock = $this->getMockBuilder(PaymentMapKeyBuilderStrategyPluginInterface::class)->getMock();
        $this->tester->setDependency(SalesPaymentDependencyProvider::PAYMENT_MAP_KEY_BUILDER_STRATEGY_PLUGINS, [$paymentMapKeyBuilderStrategyPluginMock]);

        return $paymentMapKeyBuilderStrategyPluginMock;
    }
}

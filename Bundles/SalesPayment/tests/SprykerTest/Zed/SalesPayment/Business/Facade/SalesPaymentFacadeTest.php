<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesPayment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesPaymentTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\SalesPayment\SalesPaymentDependencyProvider;
use Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\OrderPaymentExpanderPluginInterface;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesPayment
 * @group Business
 * @group Facade
 * @group Facade
 * @group SalesPaymentFacadeTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\SalesPayment\SalesPaymentBusinessTester $tester
 */
class SalesPaymentFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_PROVIDER_ONE = 'Test one';
    /**
     * @var string
     */
    protected const TEST_PROVIDER_TWO = 'Test two';
    /**
     * @var int
     */
    protected const TEST_GRAND_TOTAL = 125;

    /**
     * @var string
     */
    protected const ITEM_NAME = 'ITEM_NAME';
    /**
     * @var string
     */
    protected const CURRENCY_ISO_CODE = 'CODE';
    /**
     * @var string
     */
    protected const CUSTOMER_REFERENCE = 'CUSTOMER_REFERENCE';

    /**
     * @var \Spryker\Zed\SalesPayment\Business\SalesPaymentFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected $salesPaymentFacade;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->salesPaymentFacade = $this->tester->getFacade();
    }

    /**
     * @return void
     */
    public function testSaveOrderPaymentsPaymentTransfersHaveIdSalesPayment(): void
    {
        // Arrange
        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
        $saveOrderTransfer = $this->tester->haveOrder([], BusinessHelper::DEFAULT_OMS_PROCESS_NAME);

        $paymentTransferOne = (new PaymentTransfer())
            ->setAmount(100)
            ->setPaymentProvider(static::TEST_PROVIDER_ONE)
            ->setPaymentMethod('Method one');

        $paymentTransferTwo = (new PaymentTransfer())
            ->setAmount(150)
            ->setPaymentProvider(static::TEST_PROVIDER_TWO)
            ->setPaymentMethod('Method one');

        $quoteTransfer = (new QuoteTransfer())
            ->addPayment($paymentTransferOne)
            ->addPayment($paymentTransferTwo);

        // Act
        $this->salesPaymentFacade->saveOrderPayments($quoteTransfer, $saveOrderTransfer);

        // Assert
        foreach ($quoteTransfer->getPayments() as $paymentTransfer) {
            $this->tester->assertIsSalesPaymentSaved($paymentTransfer);
        }
    }

    /**
     * @return void
     */
    public function testExpandOrderWithPaymentsExecutePluginStack(): void
    {
        // Arrange
        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
        $salesOrderEntityTransfer = $this->tester->haveOrder([], BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $orderTransfer = $this->createOrderTransfer($salesOrderEntityTransfer);

        $salesPaymentExpanderPluginMock = $this->getMockBuilder(OrderPaymentExpanderPluginInterface::class)
            ->getMock();
        $salesPaymentExpanderPluginMock->expects($this->exactly(2))
            ->method('expand')
            ->willReturnCallback(function (OrderTransfer $orderTransfer, PaymentTransfer $paymentTransfer): PaymentTransfer {
                return $paymentTransfer;
            });

        $this->tester->setDependency(
            SalesPaymentDependencyProvider::SALES_PAYMENT_EXPANDER_PLUGINS,
            [
                $salesPaymentExpanderPluginMock,
            ]
        );

        // Act
        $this->assertCount(0, $orderTransfer->getPayments());
        $this->salesPaymentFacade->expandOrderWithPayments($orderTransfer);
        $this->assertCount(2, $orderTransfer->getPayments());
    }

    /**
     * @return void
     */
    public function testExpandOrderWithPaymentsPriceToPayAmountIsNotAffected(): void
    {
        // Arrange
        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
        $salesOrderEntityTransfer = $this->tester->haveOrder([], BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $orderTransfer = $this->createOrderTransfer($salesOrderEntityTransfer);

        // Act
        $this->salesPaymentFacade->expandOrderWithPayments($orderTransfer);
        $this->assertSame(static::TEST_GRAND_TOTAL, $orderTransfer->getTotals()->getPriceToPay());
    }

    /**
     * @return void
     */
    public function testExpandOrderWithPaymentsPriceToPayShouldNotBeEqualToGrandTotal(): void
    {
        // Arrange
        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
        $salesOrderEntityTransfer = $this->tester->haveOrder([], BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $orderTransfer = $this->createOrderTransfer($salesOrderEntityTransfer);
        $fakeAvailableAmount = 50;

        $salesPaymentExpanderPluginMock = $this->getMockBuilder(OrderPaymentExpanderPluginInterface::class)
            ->getMock();
        $salesPaymentExpanderPluginMock->expects($this->exactly(2))
            ->method('expand')
            ->willReturnCallback(function (OrderTransfer $orderTransfer, PaymentTransfer $paymentTransfer) use ($fakeAvailableAmount): PaymentTransfer {
                return $paymentTransfer->setIsLimitedAmount(true)
                    ->setAvailableAmount($fakeAvailableAmount);
            });

        $this->tester->setDependency(
            SalesPaymentDependencyProvider::SALES_PAYMENT_EXPANDER_PLUGINS,
            [
                $salesPaymentExpanderPluginMock,
            ]
        );

        // Act
        $this->salesPaymentFacade->expandOrderWithPayments($orderTransfer);
        $this->assertSame(static::TEST_GRAND_TOTAL - 2 * $fakeAvailableAmount, $orderTransfer->getTotals()->getPriceToPay());
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $salesOrderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer(SaveOrderTransfer $salesOrderTransfer): OrderTransfer
    {
        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer->setGrandTotal(static::TEST_GRAND_TOTAL);

        $orderTransfer = (new OrderTransfer())
            ->setIdSalesOrder($salesOrderTransfer->getIdSalesOrder())
            ->setTotals($totalsTransfer);

        $salesPaymentTransferOne = (new SalesPaymentTransfer())
            ->setFkSalesOrder($salesOrderTransfer->getIdSalesOrder())
            ->setAmount(100)
            ->setPaymentProvider(static::TEST_PROVIDER_ONE)
            ->setPaymentMethod('Method one');

        $salesPaymentTransferTwo = (new SalesPaymentTransfer())
            ->setFkSalesOrder($salesOrderTransfer->getIdSalesOrder())
            ->setAmount(150)
            ->setPaymentProvider(static::TEST_PROVIDER_TWO)
            ->setPaymentMethod('Method one');

        $this->tester->haveSalesPaymentEntities([
            $salesPaymentTransferOne,
            $salesPaymentTransferTwo,
        ]);

        return $orderTransfer;
    }
}

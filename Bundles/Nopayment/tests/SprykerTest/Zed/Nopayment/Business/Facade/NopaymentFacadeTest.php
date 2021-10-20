<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Nopayment\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Shared\Nopayment\NopaymentConfig as SharedNopaymentConfig;
use Spryker\Zed\Nopayment\Business\NopaymentBusinessFactory;
use Spryker\Zed\Nopayment\NopaymentConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Nopayment
 * @group Business
 * @group Facade
 * @group Facade
 * @group NopaymentFacadeTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\Nopayment\NopaymentBusinessTester $tester
 */
class NopaymentFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const PAYMENT_METHOD_WHITELISTED = 'PAYMENT_METHOD_WHITELISTED';

    /**
     * @var string
     */
    protected const PAYMENT_METHOD_NOPAYMENT_ONE = 'PAYMENT_METHOD_NOPAYMENT_ONE';

    /**
     * @var string
     */
    protected const PAYMENT_METHOD_NOPAYMENT_TWO = 'PAYMENT_METHOD_NOPAYMENT_TWO';

    /**
     * @var string
     */
    protected const PAYMENT_METHOD_NOT_NOPAYMENT = 'PAYMENT_METHOD_NOT_ALLOWED_AT_ALL';

    /**
     * @var \Spryker\Zed\Nopayment\Business\NopaymentFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected $nopaymentFacade;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->nopaymentFacade = $this->tester->getFacade();
        $configMock = $this->createMock(NopaymentConfig::class);
        $configMock->method('getWhitelistMethods')->willReturn([
            static::PAYMENT_METHOD_WHITELISTED,
        ]);
        $configMock->method('getNopaymentMethods')->willReturn([
            static::PAYMENT_METHOD_NOPAYMENT_ONE,
            static::PAYMENT_METHOD_NOPAYMENT_TWO,
        ]);
        $factory = new NopaymentBusinessFactory();
        $factory->setConfig($configMock);
        $this->nopaymentFacade->setFactory($factory);
    }

    /**
     * @return void
     */
    public function testSetAsPaidNopaymentPaidEntityShouldBeCreated(): void
    {
        // Arrange
        $idSalesOrderEntity = $this->tester->createOrder();
        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder($idSalesOrderEntity, [[]]);
        $salesOrderItemEntities = [
            $salesOrderItemEntity,
        ];

        // Act
        $resultSalesOrderItemEntities = $this->nopaymentFacade->setAsPaid($salesOrderItemEntities);

        // Assert
        $this->assertEquals($salesOrderItemEntities, $resultSalesOrderItemEntities);
        $this->tester->assertNopaymentPaidWereCreated($salesOrderItemEntities[0]->getIdSalesOrderItem());
    }

    /**
     * @return void
     */
    public function testIsPaidExpectedTrue(): void
    {
        // Arrange
        $idSalesOrderEntity = $this->tester->createOrder();
        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder($idSalesOrderEntity, [[]]);
        $this->tester->haveNopaymentPaid($salesOrderItemEntity->getIdSalesOrderItem());

        // Act
        $isPaidResult = $this->nopaymentFacade->isPaid($salesOrderItemEntity);

        // Assert
        $this->assertTrue($isPaidResult);
    }

    /**
     * @return void
     */
    public function testIsPaidExpectedFalse(): void
    {
        // Arrange
        $idSalesOrderEntity = $this->tester->createOrder();
        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder($idSalesOrderEntity, [[]]);

        // Act
        $isPaidResult = $this->nopaymentFacade->isPaid($salesOrderItemEntity);

        // Assert
        $this->assertFalse($isPaidResult);
    }

    /**
     * @dataProvider getDateForPriceToPayTest
     *
     * @param int $valuePriceToPay
     * @param int $expectedPaymentMethodsCount
     *
     * @return void
     */
    public function testFilterPaymentMethodsByPriceToPayCountOfMethodsShouldBeEqual(int $valuePriceToPay, int $expectedPaymentMethodsCount): void
    {
        // Arrange
        $paymentMethodsTransfer = new PaymentMethodsTransfer();
        $paymentMethodsTransfer->setMethods($this->getPredefinedPaymentMethods());
        $totalTransfer = new TotalsTransfer();
        $totalTransfer->setPriceToPay($valuePriceToPay);
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setTotals($totalTransfer);

        // Act
        $paymentMethodsTransfer = $this->nopaymentFacade->filterPaymentMethods($paymentMethodsTransfer, $quoteTransfer);

        // Assert
        $this->assertEquals($expectedPaymentMethodsCount, $paymentMethodsTransfer->getMethods()->count());
    }

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsResponseShouldHaveError(): void
    {
        // Arrange
        $totalTransfer = new TotalsTransfer();
        $totalTransfer->setPriceToPay(2345);
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setTotals($totalTransfer);
        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentProvider(SharedNopaymentConfig::PAYMENT_PROVIDER_NAME);
        $quoteTransfer->addPayment($paymentTransfer);
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $result = $this->nopaymentFacade->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($result);
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
        $this->assertEquals(403, $checkoutResponseTransfer->getErrors()[0]->getErrorCode());
    }

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsResponseShouldNotHaveError(): void
    {
        // Arrange
        $totalTransfer = new TotalsTransfer();
        $totalTransfer->setPriceToPay(0);
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setTotals($totalTransfer);
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $result = $this->nopaymentFacade->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($result);
        $this->assertCount(0, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return array<int[]>
     */
    public function getDateForPriceToPayTest(): array
    {
        return [
            [0, 3],
            [2435, 2],
        ];
    }

    /**
     * @return \ArrayObject
     */
    protected function getPredefinedPaymentMethods(): ArrayObject
    {
        $preDefinedPaymentMethodCollection = new ArrayObject();
        $paymentMethodNames = [
            static::PAYMENT_METHOD_WHITELISTED,
            static::PAYMENT_METHOD_NOPAYMENT_ONE,
            static::PAYMENT_METHOD_NOPAYMENT_TWO,
            static::PAYMENT_METHOD_NOT_NOPAYMENT,
        ];

        foreach ($paymentMethodNames as $methodsName) {
            $paymentMethodTransfer = new PaymentMethodTransfer();
            $paymentMethodTransfer->setMethodName($methodsName);
            $preDefinedPaymentMethodCollection->append($paymentMethodTransfer);
        }

        return $preDefinedPaymentMethodCollection;
    }
}

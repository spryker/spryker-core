<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesPaymentMerchant\Communication\Plugin\Oms\Command;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AcpHttpResponseTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Shared\Kernel\Container\GlobalContainer;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\KernelApp\Business\KernelAppFacadeInterface;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\SalesPaymentMerchant\Business\Reader\OrderExpenseReaderInterface;
use Spryker\Zed\SalesPaymentMerchant\Communication\Plugin\Oms\Command\MerchantPayoutCommandByOrderPlugin;
use Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToKernelAppFacadeBridge;
use Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantDependencyProvider;
use Spryker\Zed\SalesPaymentMerchantExtension\Communication\Dependency\Plugin\MerchantPayoutCalculatorPluginInterface;
use SprykerTest\Zed\SalesPaymentMerchant\SalesPaymentMerchantCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesPaymentMerchant
 * @group Communication
 * @group Plugin
 * @group Oms
 * @group Command
 * @group MerchantPayoutCommandPluginTest
 * Add your own group annotations below this line
 */
class MerchantPayoutCommandPluginTest extends Unit
{
 /**
  * @uses \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE
  *
  * @var string
  */
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @var \SprykerTest\Zed\SalesPaymentMerchant\SalesPaymentMerchantCommunicationTester $tester
     */
    protected SalesPaymentMerchantCommunicationTester $tester;

    /**
     * @var string
     */
    protected string $merchantReference;

    /**
     * @var string
     */
    protected string $orderReference;

    /**
     * @var \Generated\Shared\Transfer\PaymentProviderTransfer
     */
    protected PaymentProviderTransfer $paymentProviderTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->merchantReference = Uuid::uuid4()->toString();
        $this->orderReference = Uuid::uuid4()->toString();

        $globalContainer = new GlobalContainer();
        $globalContainer->setContainer(new Container([
            'locale' => 'de_DE',
        ]));

        $this->tester->mockHydrateOrderPluginsInSalesModule();

        $this->paymentProviderTransfer = $this->tester->havePaymentProvider([
            PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => 'foo',
            PaymentProviderTransfer::NAME => 'foo',
        ]);

        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::NAME => 'Foo',
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => 'foo-bar',
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $this->paymentProviderTransfer->getIdPaymentProvider(),
        ]);

        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => $this->merchantReference,
        ]);
    }

    /**
     * Test ensures that when a payment method is not configured to transfer payments, the command is skipped without a failure.
     *
     * @return void
     */
    public function testGivenAnOrderWithOneOrderItemFromAMerchantWhenTheCommandIsExecutedAndThePaymentMethodDoesNotHaveTransferOfPaymentsEnabledTheCommandIsSkipped(): void
    {
        // Arrange
        $salesOrderItemWithMerchant = $this->tester->createSalesOrderItemEntity($this->merchantReference);
        $salesOrderEntity = $this->tester->mockSalesOrderEntity([$salesOrderItemWithMerchant], $this->merchantReference, $this->orderReference);
        $orderItems = $salesOrderEntity->getItems()->getArrayCopy();

        // Act
        $merchantPayoutCommandPlugin = new MerchantPayoutCommandByOrderPlugin();
        $result = $merchantPayoutCommandPlugin->run($orderItems, $salesOrderEntity, new ReadOnlyArrayObject([]));

        // Assert
        $this->tester->assertIsArray($result);
    }

    /**
     * @return void
     */
    public function testGivenAnOrderWithOneOrderItemFromAMerchantWhenTheCommandIsExecutedAndTheExternalPSPReturnsASuccessfulResponseThenAllOrderItemsOfThisMerchantArePersisted(): void
    {
        // Arrange
        $this->tester->havePaymentProviderWithPaymentMethodSupportingPayouts();
        $salesOrderItemWithMerchant = $this->tester->createSalesOrderItemEntity($this->merchantReference);
        $salesOrderEntity = $this->tester->mockSalesOrderEntity([$salesOrderItemWithMerchant], $this->merchantReference, $this->orderReference);
        $orderItems = $salesOrderEntity->getItems()->getArrayCopy();

        $this->tester->mockExpectedResponseFromApp(
            $this->merchantReference,
            $this->orderReference,
            [['itemReference' => $salesOrderItemWithMerchant->getOrderItemReference()]],
            '900',
        );

        // Act
        $merchantPayoutCommandPlugin = new MerchantPayoutCommandByOrderPlugin();
        $merchantPayoutCommandPlugin->run($orderItems, $salesOrderEntity, new ReadOnlyArrayObject([]));

        // Assert
        $this->tester->assertSalesPaymentMerchantPayoutEntity($this->merchantReference, $this->orderReference, [$salesOrderItemWithMerchant->getOrderItemReference()]);
    }

    /**
     * @return void
     */
    public function testAmountCalculatorPluginIsCalledWhenPluggedIn(): void
    {
        // Arrange
        $this->tester->havePaymentProviderWithPaymentMethodSupportingPayouts();
        $salesOrderItemWithMerchant = $this->tester->createSalesOrderItemEntity($this->merchantReference);
        $salesOrderEntity = $this->tester->mockSalesOrderEntity([$salesOrderItemWithMerchant], $this->merchantReference, $this->orderReference);
        $orderItems = $salesOrderEntity->getItems()->getArrayCopy();

        $this->tester->mockExpectedResponseFromApp(
            $this->merchantReference,
            $this->orderReference,
            [['itemReference' => $salesOrderItemWithMerchant->getOrderItemReference()]],
            '900',
        );

        // Expect
        $amountCalculatorPluginMock = $this->createMock(MerchantPayoutCalculatorPluginInterface::class);
        $amountCalculatorPluginMock->expects($this->once())
            ->method('calculatePayoutAmount');

        $this->tester->setDependency(
            SalesPaymentMerchantDependencyProvider::PLUGIN_MERCHANT_PAYOUT_AMOUNT_CALCULATOR,
            $amountCalculatorPluginMock,
        );

        // Act
        $merchantPayoutCommandPlugin = new MerchantPayoutCommandByOrderPlugin();
        $merchantPayoutCommandPlugin->run($orderItems, $salesOrderEntity, new ReadOnlyArrayObject([]));
    }

    /**
     * @return void
     */
    public function testOrderExpenseAreNotSentWhenOrderExpenseIncludedInPaymentProcessIsDisabled(): void
    {
        // Arrange
        $this->tester->havePaymentProviderWithPaymentMethodSupportingPayouts();
        $this->tester->mockConfigMethod('isOrderExpenseIncludedInPaymentProcess', false);

        $salesOrderItemWithMerchant = $this->tester->createSalesOrderItemEntity($this->merchantReference);
        $salesOrderEntity = $this->tester->mockSalesOrderEntity([$salesOrderItemWithMerchant], $this->merchantReference, $this->orderReference);
        $orderItems = $salesOrderEntity->getItems()->getArrayCopy();

        $this->tester->mockExpectedResponseFromApp(
            $this->merchantReference,
            $this->orderReference,
            [
                ['itemReference' => $salesOrderItemWithMerchant->getOrderItemReference()],
            ],
            '900',
        );

        // Expect
        $orderExpenseReaderMock = $this->createMock(OrderExpenseReaderInterface::class);
        $orderExpenseReaderMock->expects($this->never())->method('getOrderExpensesForTransfer');
        $this->tester->mockFactoryMethod('createOrderExpenseReader', $orderExpenseReaderMock);

        // Act
        $merchantPayoutCommandPlugin = new MerchantPayoutCommandByOrderPlugin();
        $merchantPayoutCommandPlugin->setFacade($this->tester->getFacade());
        $merchantPayoutCommandPlugin->run($orderItems, $salesOrderEntity, new ReadOnlyArrayObject([]));
    }

    /**
     * @return void
     */
    public function testOrderExpensesAreSentWhenOrderExpenseIncludedInPaymentProcessIsEnabled(): void
    {
        // Arrange
        $this->tester->havePaymentProviderWithPaymentMethodSupportingPayouts();
        $this->tester->mockConfigMethod('isOrderExpenseIncludedInPaymentProcess', true);
        $salesOrderItemWithMerchant = $this->tester->createSalesOrderItemEntity($this->merchantReference);
        $salesOrderEntity = $this->tester->mockSalesOrderEntity([$salesOrderItemWithMerchant], $this->merchantReference, $this->orderReference);

        $shipmentExpenseTransfer = $this->tester->haveSalesExpense([
            ExpenseTransfer::TYPE => static::SHIPMENT_EXPENSE_TYPE,
            ExpenseTransfer::MERCHANT_REFERENCE => $this->merchantReference,
            ExpenseTransfer::FK_SALES_ORDER => $salesOrderEntity->getIdSalesOrder(),
        ]);

        $this->tester->mockExpectedResponseFromApp(
            $this->merchantReference,
            $this->orderReference,
            [
                ['itemReference' => $salesOrderItemWithMerchant->getOrderItemReference()],
                ['itemReference' => $shipmentExpenseTransfer->getUuid()],
            ],
            '900',
        );

        $orderItems = $salesOrderEntity->getItems()->getArrayCopy();

        // Act
        $merchantPayoutCommandPlugin = new MerchantPayoutCommandByOrderPlugin();
        $merchantPayoutCommandPlugin->setFacade($this->tester->getFacade());
        $merchantPayoutCommandPlugin->run($orderItems, $salesOrderEntity, new ReadOnlyArrayObject([]));

        // Assert
        $this->tester->assertSalesPaymentMerchantPayoutEntity($this->merchantReference, $this->orderReference, [$shipmentExpenseTransfer->getUuid()]);
    }

    /**
     * @return void
     */
    public function testOrderExpensesAreNotSentWhenAlreadySentForThisOrder(): void
    {
        // Arrange
        $this->tester->havePaymentProviderWithPaymentMethodSupportingPayouts();
        $this->tester->mockConfigMethod('isOrderExpenseIncludedInPaymentProcess', true);

        $salesOrderItemWithMerchant = $this->tester->createSalesOrderItemEntity($this->merchantReference);
        $salesOrderEntity = $this->tester->mockSalesOrderEntity([$salesOrderItemWithMerchant], $this->merchantReference, $this->orderReference);

        $shipmentExpenseTransfer = $this->tester->haveSalesExpense([
            ExpenseTransfer::TYPE => static::SHIPMENT_EXPENSE_TYPE,
            ExpenseTransfer::MERCHANT_REFERENCE => $this->merchantReference,
            ExpenseTransfer::FK_SALES_ORDER => $salesOrderEntity->getIdSalesOrder(),
        ]);

        $this->tester->haveSalesPaymentMerchantPayoutPersisted([
            'transfer_id' => Uuid::uuid4()->toString(),
            'merchant_reference' => $this->merchantReference,
            'order_reference' => $this->orderReference,
            'item_references' => $shipmentExpenseTransfer->getUuid(),
            'is_successful' => true,
            'amount' => 900,
        ]);

        // Expect
        $this->mockKernelAppFacadeMakeRequestOnce();

        // Act
        $merchantPayoutCommandPlugin = new MerchantPayoutCommandByOrderPlugin();
        $merchantPayoutCommandPlugin->setFacade($this->tester->getFacade());
        $merchantPayoutCommandPlugin->run($salesOrderEntity->getItems()->getArrayCopy(), $salesOrderEntity, new ReadOnlyArrayObject([]));
    }

    /**
     * @return void
     */
    public function testOrderExpensesAreNotSentWhenTheOrderItemOfAnotherMerchantIsSentForMarketplaceOrder(): void
    {
        // Arrange
        $this->tester->havePaymentProviderWithPaymentMethodSupportingPayouts();
        $this->tester->mockConfigMethod('isOrderExpenseIncludedInPaymentProcess', true);
        $merchantReferenceB = Uuid::uuid4()->toString();
        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => $merchantReferenceB,
        ]);

        $salesOrderItemWithMerchant = $this->tester->createSalesOrderItemEntity($merchantReferenceB);
        $salesOrderEntity = $this->tester->mockSalesOrderEntity([$salesOrderItemWithMerchant], $merchantReferenceB, $this->orderReference);

         $this->tester->haveSalesExpense([
            ExpenseTransfer::TYPE => static::SHIPMENT_EXPENSE_TYPE,
            ExpenseTransfer::MERCHANT_REFERENCE => $this->merchantReference,
            ExpenseTransfer::FK_SALES_ORDER => $salesOrderEntity->getIdSalesOrder(),
         ]);

        // Expect
        $this->mockKernelAppFacadeMakeRequestOnce();

        // Act
        $merchantPayoutCommandPlugin = new MerchantPayoutCommandByOrderPlugin();
        $merchantPayoutCommandPlugin->setFacade($this->tester->getFacade());
        $merchantPayoutCommandPlugin->run($salesOrderEntity->getItems()->getArrayCopy(), $salesOrderEntity, new ReadOnlyArrayObject([]));
    }

    /**
     * @return void
     */
    public function testOrderExpensesAreNotSentWhenTheyAreNotRelatedToTheMerchantOrder(): void
    {
        // Arrange
        $this->tester->havePaymentProviderWithPaymentMethodSupportingPayouts();
        $this->tester->mockConfigMethod('isOrderExpenseIncludedInPaymentProcess', true);
        $merchantReferenceB = Uuid::uuid4()->toString();
        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => $merchantReferenceB,
        ]);

        $salesOrderItemWithMerchant = $this->tester->createSalesOrderItemEntity($merchantReferenceB);
        $salesOrderEntity = $this->tester->mockSalesOrderEntity([$salesOrderItemWithMerchant], $merchantReferenceB, $this->orderReference);

        $this->tester->haveSalesExpense([
            ExpenseTransfer::TYPE => 'some-type',
            ExpenseTransfer::FK_SALES_ORDER => $salesOrderEntity->getIdSalesOrder(),
        ]);

        // Expect
        $this->mockKernelAppFacadeMakeRequestOnce();

        // Act
        $merchantPayoutCommandPlugin = new MerchantPayoutCommandByOrderPlugin();
        $merchantPayoutCommandPlugin->setFacade($this->tester->getFacade());
        $merchantPayoutCommandPlugin->run($salesOrderEntity->getItems()->getArrayCopy(), $salesOrderEntity, new ReadOnlyArrayObject([]));
    }

    /**
     * @return void
     */
    public function testOrderExpensesWithShipmentTypeAreNotSentWhenTheyAreExcludedForTheGivenStore(): void
    {
        // Arrange
        $this->tester->havePaymentProviderWithPaymentMethodSupportingPayouts();
        $this->tester->mockConfigMethod('isOrderExpenseIncludedInPaymentProcess', true);
        $this->tester->mockConfigMethod('getExcludedExpenseTypesForStore', ['DE' => static::SHIPMENT_EXPENSE_TYPE]);

        $salesOrderItemWithMerchant = $this->tester->createSalesOrderItemEntity($this->merchantReference);
        $salesOrderEntity = $this->tester->mockSalesOrderEntity([$salesOrderItemWithMerchant], $this->merchantReference, $this->orderReference);

        $this->tester->haveSalesExpense([
            ExpenseTransfer::TYPE => static::SHIPMENT_EXPENSE_TYPE,
            ExpenseTransfer::MERCHANT_REFERENCE => $this->merchantReference,
            ExpenseTransfer::FK_SALES_ORDER => $salesOrderEntity->getIdSalesOrder(),
        ]);

        // Expect
        $this->mockKernelAppFacadeMakeRequestOnce();

        // Act
        $merchantPayoutCommandPlugin = new MerchantPayoutCommandByOrderPlugin();
        $merchantPayoutCommandPlugin->setFacade($this->tester->getFacade());
        $merchantPayoutCommandPlugin->run($salesOrderEntity->getItems()->getArrayCopy(), $salesOrderEntity, new ReadOnlyArrayObject([]));
    }

    /**
     * @return void
     */
    protected function mockKernelAppFacadeMakeRequestOnce(): void
    {
        $transferRequestSenderMock = $this->createMock(KernelAppFacadeInterface::class);

        $transferRequestSenderMock->expects($this->once())->method('makeRequest')->willReturn(
            (new AcpHttpResponseTransfer())->setContent(json_encode(['transfers' => []])),
        );

        $this->tester->setDependency(SalesPaymentMerchantDependencyProvider::FACADE_KERNEL_APP, new SalesPaymentMerchantToKernelAppFacadeBridge($transferRequestSenderMock));
    }
}

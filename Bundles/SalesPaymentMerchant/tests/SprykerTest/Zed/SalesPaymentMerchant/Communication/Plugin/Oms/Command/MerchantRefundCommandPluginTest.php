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
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Ramsey\Uuid\Uuid;
use Spryker\Shared\Kernel\Container\GlobalContainer;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\KernelApp\Business\KernelAppFacadeInterface;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\SalesPaymentMerchant\Communication\Plugin\Oms\Command\MerchantPayoutReverseCommandByOrderPlugin;
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
 * @group MerchantRefundCommandPluginTest
 * Add your own group annotations below this line
 */
class MerchantRefundCommandPluginTest extends Unit
{
    /**
     * @uses \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE
     *
     * @var string
     */
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @var string
     */
    protected const OMS_STATE_PAYMENT_NEW = 'new';

    /**
     * @var \SprykerTest\Zed\SalesPaymentMerchant\SalesPaymentMerchantCommunicationTester $tester
     */
    protected SalesPaymentMerchantCommunicationTester $tester;

    /**
     * @return void
     */
    public function testGivenAnOrderWithOneOrderItemFromAMerchantWhenTheCommandIsExecutedAndThePaymentMethodDoesNotHaveTransferOfPaymentsEnabledTheCommandIsSkipped(): void
    {
        // Arrange
        $transferId = Uuid::uuid4()->toString();
        $merchantReference = Uuid::uuid4()->toString();
        $orderReference = Uuid::uuid4()->toString();

        $globalContainer = new GlobalContainer();
        $globalContainer->setContainer(new Container([
            'locale' => 'de_DE',
        ]));

        $paymentProviderTransfer = $this->tester->havePaymentProvider([
            PaymentProviderTransfer::NAME => 'Foo',
            PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => 'foo',
        ]);

        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::NAME => 'bar',
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => 'foo-bar',
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
        ]);

        $this->tester->mockHydrateOrderPluginsInSalesModule();

        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => $merchantReference,
        ]);

        $salesOrderItemWithMerchant = $this->tester->createSalesOrderItemEntity($merchantReference);

        $orderItems = [
            $salesOrderItemWithMerchant,
        ];

        $salesOrderEntity = $this->tester->mockSalesOrderEntity($orderItems, $merchantReference, $orderReference);

        // Act
        $response = $this->runMerchantPayoutReverseCommandByOrderPlugin($salesOrderEntity);

        // Assert
        $this->tester->assertIsArray($response);
    }

    /**
     * @return void
     */
    public function testGivenAnOrderWithOneOrderItemFromAMerchantWhenTheCommandIsExecutedAndTheExternalPSPReturnsASuccessfulResponseThenAllOrderItemsOfThisMerchantArePersisted(): void
    {
        // Arrange
        $transferId = Uuid::uuid4()->toString();
        $merchantReference = Uuid::uuid4()->toString();
        $orderReference = Uuid::uuid4()->toString();

        $globalContainer = new GlobalContainer();
        $globalContainer->setContainer(new Container([
            'locale' => 'de_DE',
        ]));

        $this->tester->havePaymentProviderWithPaymentMethodSupportingPayouts();
        $this->tester->mockHydrateOrderPluginsInSalesModule();
        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => $merchantReference,
        ]);

        $salesOrderItemWithMerchant = $this->tester->createSalesOrderItemEntity($merchantReference);
        $salesOrderEntity = $this->tester->mockSalesOrderEntity(
            [$salesOrderItemWithMerchant],
            $merchantReference,
            $orderReference,
        );

        $this->tester->haveSalesPaymentMerchantPayoutPersisted([
            'transfer_id' => $transferId,
            'merchant_reference' => $merchantReference,
            'order_reference' => $orderReference,
            'item_references' => $salesOrderItemWithMerchant->getOrderItemReference(),
            'is_successful' => true,
            'amount' => 900,
        ]);

        $this->tester->mockExpectedResponseFromApp(
            $merchantReference,
            $orderReference,
            [['itemReference' => $salesOrderItemWithMerchant->getOrderItemReference()]],
            '-900',
        );

        $this->tester->haveSalesPaymentMerchantPayoutPersisted([
            'transfer_id' => $transferId,
            'merchant_reference' => $merchantReference,
            'order_reference' => $orderReference,
            'item_references' => $salesOrderItemWithMerchant->getOrderItemReference(),
            'is_successful' => true,
            'amount' => 900,
        ]);

        // Act
        $this->runMerchantPayoutReverseCommandByOrderPlugin($salesOrderEntity);

        // Assert
        $this->tester->assertSalesPaymentMerchantRefundEntity($merchantReference, $orderReference, [$salesOrderItemWithMerchant->getOrderItemReference()]);
    }

    /**
     * @return void
     */
    public function testGivenAnOrderWithTwoOrderItemsFromDifferentMerchantsWhenTheCommandIsExecutedAndTheExternalPSPReturnsASuccessfulResponseThenAllOrderItemsOfThisMerchantArePersisted(): void
    {
        // Arrange
        $transferId = Uuid::uuid4()->toString();
        $merchantReferenceOne = Uuid::uuid4()->toString();
        $merchantReferenceTwo = Uuid::uuid4()->toString();
        $orderReference = Uuid::uuid4()->toString();

        $globalContainer = new GlobalContainer();
        $globalContainer->setContainer(new Container([
            'locale' => 'de_DE',
        ]));

        $this->tester->havePaymentProviderWithPaymentMethodSupportingPayouts();
        $this->tester->mockHydrateOrderPluginsInSalesModule();

        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => $merchantReferenceOne,
        ]);

        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => $merchantReferenceTwo,
        ]);

        $salesOrderItemWithMerchantOne = $this->tester->createSalesOrderItemEntity($merchantReferenceOne);
        $salesOrderItemWithMerchantTwo = $this->tester->createSalesOrderItemEntity($merchantReferenceTwo);

        $salesOrderEntity = $this->tester->mockSalesOrderEntity([
            $salesOrderItemWithMerchantOne,
            $salesOrderItemWithMerchantTwo,
        ], $merchantReferenceOne, $orderReference);

        $this->tester->haveSalesPaymentMerchantPayoutPersisted([
            'transfer_id' => null, // A failed response does not have a transfer id
            'order_reference' => $orderReference,
            'item_references' => '',
            'is_successful' => false,
            'amount' => 0,
        ]);

        $this->tester->haveSalesPaymentMerchantPayoutPersisted([
            'transfer_id' => $transferId,
            'merchant_reference' => $merchantReferenceOne,
            'order_reference' => $orderReference,
            'item_references' => $salesOrderItemWithMerchantOne->getOrderItemReference(),
            'is_successful' => true,
            'amount' => 900,
        ]);

        $this->tester->mockExpectedResponseFromApp(
            $merchantReferenceOne,
            $orderReference,
            [['itemReference' => $salesOrderItemWithMerchantOne->getOrderItemReference()]],
            '-900',
        );

        // Act
        $this->runMerchantPayoutReverseCommandByOrderPlugin($salesOrderEntity);

        // Assert
        $this->tester->assertSalesPaymentMerchantRefundEntity($merchantReferenceOne, $orderReference, [$salesOrderItemWithMerchantOne->getOrderItemReference()]);
    }

    /**
     * @return void
     */
    public function testMerchantPayoutReverseAmountCalculatorPluginIsCalled(): void
    {
        // Arrange
        $merchantReference = Uuid::uuid4()->toString();
        $orderReference = Uuid::uuid4()->toString();
        $transferId = Uuid::uuid4()->toString();

        $this->tester->havePaymentProviderWithPaymentMethodSupportingPayouts();
        $this->tester->haveMerchant([MerchantTransfer::MERCHANT_REFERENCE => $merchantReference]);

        $salesOrderItemWithMerchant = $this->tester->createSalesOrderItemEntity($merchantReference);
        $salesOrderEntity = $this->tester->mockSalesOrderEntity(
            [$salesOrderItemWithMerchant],
            $merchantReference,
            $orderReference,
        );

        $salesPaymentMerchantPayoutEntity = $this->tester->haveSalesPaymentMerchantPayoutPersisted(
            [
                'transfer_id' => $transferId,
                'merchant_reference' => $merchantReference,
                'order_reference' => $orderReference,
                'item_references' => $salesOrderItemWithMerchant->getOrderItemReference(),
                'is_successful' => true,
                'amount' => 900,
            ],
        );
        $salesOrderEntity->addSpySalesPaymentMerchantPayout($salesPaymentMerchantPayoutEntity);

        $this->tester->mockExpectedResponseFromApp(
            $merchantReference,
            $orderReference,
            [['itemReference' => $salesOrderItemWithMerchant->getOrderItemReference()]],
            '-900',
        );

        // Expect
        $amountCalculatorPluginMock = $this->createMock(MerchantPayoutCalculatorPluginInterface::class);
        $amountCalculatorPluginMock->expects($this->once())
            ->method('calculatePayoutAmount')
            ->willReturn(-100);

        $this->tester->setDependency(
            SalesPaymentMerchantDependencyProvider::PLUGIN_MERCHANT_PAYOUT_REVERSE_AMOUNT_CALCULATOR,
            $amountCalculatorPluginMock,
        );

        // Act
        $this->runMerchantPayoutReverseCommandByOrderPlugin($salesOrderEntity);
    }

    /**
     * @return void
     */
    public function testOrderExpensesAreSentToReverseWhenOrderExpenseIncludedInPaymentProcess(): void
    {
        // Arrange
        $this->tester->havePaymentProviderWithPaymentMethodSupportingPayouts();
        $this->tester->mockConfigMethod('isOrderExpenseIncludedInPaymentProcess', true);
        $this->tester->mockConfigMethod('getItemRefusedStates', [static::OMS_STATE_PAYMENT_NEW]);

        $orderReference = Uuid::uuid4()->toString();
        $merchantReferenceA = Uuid::uuid4()->toString();
        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => $merchantReferenceA,
        ]);

        $salesOrderItemWithMerchant = $this->tester->createSalesOrderItemEntity($merchantReferenceA);
        $salesOrderEntity = $this->tester->mockSalesOrderEntity([$salesOrderItemWithMerchant], $merchantReferenceA, $orderReference);

        $this->tester->haveSalesPaymentMerchantPayoutPersisted(
            [
                'transfer_id' => Uuid::uuid4()->toString(),
                'merchant_reference' => $merchantReferenceA,
                'order_reference' => $orderReference,
                'item_references' => $salesOrderItemWithMerchant->getOrderItemReference(),
                'is_successful' => true,
                'amount' => 900,
            ],
        );

        $expenseTransfer = $this->tester->haveSalesExpense([
            ExpenseTransfer::TYPE => static::SHIPMENT_EXPENSE_TYPE,
            ExpenseTransfer::MERCHANT_REFERENCE => $merchantReferenceA,
            ExpenseTransfer::FK_SALES_ORDER => $salesOrderEntity->getIdSalesOrder(),
        ]);

        $this->tester->haveSalesPaymentMerchantPayoutPersisted(
            [
                'transfer_id' => Uuid::uuid4()->toString(),
                'merchant_reference' => $merchantReferenceA,
                'order_reference' => $orderReference,
                'item_references' => $expenseTransfer->getUuid(),
                'is_successful' => true,
                'amount' => 900,
            ],
        );

        $this->tester->mockExpectedResponseFromApp(
            $merchantReferenceA,
            $orderReference,
            [
                ['itemReference' => $salesOrderItemWithMerchant->getOrderItemReference()],
                ['itemReference' => $expenseTransfer->getUuid()],
            ],
            '-900',
        );

        // Act
        $this->runMerchantPayoutReverseCommandByOrderPlugin($salesOrderEntity);

        // Assert
        $this->tester->assertSalesPaymentMerchantRefundEntity($merchantReferenceA, $orderReference, [$salesOrderItemWithMerchant->getOrderItemReference(), $expenseTransfer->getUuid()]);
    }

    /**
     * @return void
     */
    public function testOrderExpensesAreNotSentToReverseWhenOrderItemOfAnotherMerchantIsSentForMarketplaceOrder(): void
    {
        // Arrange
        $this->tester->havePaymentProviderWithPaymentMethodSupportingPayouts();
        $this->tester->mockConfigMethod('isOrderExpenseIncludedInPaymentProcess', true);
        $this->tester->mockConfigMethod('getItemRefusedStates', [static::OMS_STATE_PAYMENT_NEW]);

        $orderReference = Uuid::uuid4()->toString();
        $merchantReferenceA = Uuid::uuid4()->toString();
        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => $merchantReferenceA,
        ]);
        $merchantReferenceB = Uuid::uuid4()->toString();
        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => $merchantReferenceB,
        ]);

        $salesOrderItemWithMerchant = $this->tester->createSalesOrderItemEntity($merchantReferenceA);
        $salesOrderEntity = $this->tester->mockSalesOrderEntity([$salesOrderItemWithMerchant], $merchantReferenceA, $orderReference);

        $this->tester->haveSalesPaymentMerchantPayoutPersisted(
            [
                'transfer_id' => Uuid::uuid4()->toString(),
                'merchant_reference' => $merchantReferenceA,
                'order_reference' => $orderReference,
                'item_references' => $salesOrderItemWithMerchant->getOrderItemReference(),
                'is_successful' => true,
                'amount' => 900,
            ],
        );

        $this->tester->haveSalesExpense([
            ExpenseTransfer::TYPE => static::SHIPMENT_EXPENSE_TYPE,
            ExpenseTransfer::MERCHANT_REFERENCE => $merchantReferenceB,
            ExpenseTransfer::FK_SALES_ORDER => $salesOrderEntity->getIdSalesOrder(),
        ]);

        // Expect
        $this->mockKernelAppFacadeMakeRequestOnce();

        // Act
        $this->runMerchantPayoutReverseCommandByOrderPlugin($salesOrderEntity);
    }

    /**
     * @return void
     */
    public function testOrderExpensesAreNotSentToReverseWhenTheyAreNotRelatedToTheMerchantOrder(): void
    {
        // Arrange
        $this->tester->havePaymentProviderWithPaymentMethodSupportingPayouts();
        $this->tester->mockConfigMethod('isOrderExpenseIncludedInPaymentProcess', true);
        $this->tester->mockConfigMethod('getItemRefusedStates', [static::OMS_STATE_PAYMENT_NEW]);

        $orderReference = Uuid::uuid4()->toString();
        $merchantReferenceA = Uuid::uuid4()->toString();
        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => $merchantReferenceA,
        ]);
        $merchantReferenceB = Uuid::uuid4()->toString();
        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => $merchantReferenceB,
        ]);

        $salesOrderItemWithMerchant = $this->tester->createSalesOrderItemEntity($merchantReferenceA);
        $salesOrderEntity = $this->tester->mockSalesOrderEntity([$salesOrderItemWithMerchant], $merchantReferenceA, $orderReference);

        $this->tester->haveSalesPaymentMerchantPayoutPersisted(
            [
                'transfer_id' => Uuid::uuid4()->toString(),
                'merchant_reference' => $merchantReferenceA,
                'order_reference' => $orderReference,
                'item_references' => $salesOrderItemWithMerchant->getOrderItemReference(),
                'is_successful' => true,
                'amount' => 900,
            ],
        );

        $this->tester->haveSalesExpense([
            ExpenseTransfer::TYPE => static::SHIPMENT_EXPENSE_TYPE,
            ExpenseTransfer::FK_SALES_ORDER => $salesOrderEntity->getIdSalesOrder(),
        ]);

        // Expect
        $this->mockKernelAppFacadeMakeRequestOnce();

        // Act
        $this->runMerchantPayoutReverseCommandByOrderPlugin($salesOrderEntity);
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

        $merchantReference = Uuid::uuid4()->toString();
        $orderReference = Uuid::uuid4()->toString();
        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => $merchantReference,
        ]);
        $salesOrderItemWithMerchant = $this->tester->createSalesOrderItemEntity($merchantReference);
        $salesOrderEntity = $this->tester->mockSalesOrderEntity([$salesOrderItemWithMerchant], $merchantReference, $orderReference);

        $this->tester->haveSalesPaymentMerchantPayoutPersisted(
            [
                'transfer_id' => Uuid::uuid4()->toString(),
                'merchant_reference' => $merchantReference,
                'order_reference' => $orderReference,
                'item_references' => $salesOrderItemWithMerchant->getOrderItemReference(),
                'is_successful' => true,
                'amount' => 900,
            ],
        );

        $this->tester->haveSalesExpense([
            ExpenseTransfer::TYPE => static::SHIPMENT_EXPENSE_TYPE,
            ExpenseTransfer::MERCHANT_REFERENCE => $merchantReference,
            ExpenseTransfer::FK_SALES_ORDER => $salesOrderEntity->getIdSalesOrder(),
        ]);

        // Expect
        $this->mockKernelAppFacadeMakeRequestOnce();

        // Act
        $this->runMerchantPayoutReverseCommandByOrderPlugin($salesOrderEntity);
    }

    /**
     * @return void
     */
    public function testOrderExpensesAreNotSentWhenOrderExpenseIncludedInPaymentProcessDisabled(): void
    {
        // Arrange
        $this->tester->havePaymentProviderWithPaymentMethodSupportingPayouts();
        $this->tester->mockConfigMethod('isOrderExpenseIncludedInPaymentProcess', false);

        $merchantReference = Uuid::uuid4()->toString();
        $orderReference = Uuid::uuid4()->toString();
        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => $merchantReference,
        ]);
        $salesOrderItemWithMerchant = $this->tester->createSalesOrderItemEntity($merchantReference);
        $salesOrderEntity = $this->tester->mockSalesOrderEntity([$salesOrderItemWithMerchant], $merchantReference, $orderReference);

        $this->tester->haveSalesPaymentMerchantPayoutPersisted(
            [
                'transfer_id' => Uuid::uuid4()->toString(),
                'merchant_reference' => $merchantReference,
                'order_reference' => $orderReference,
                'item_references' => $salesOrderItemWithMerchant->getOrderItemReference(),
                'is_successful' => true,
                'amount' => 900,
            ],
        );

        $this->tester->haveSalesExpense([
            ExpenseTransfer::TYPE => static::SHIPMENT_EXPENSE_TYPE,
            ExpenseTransfer::MERCHANT_REFERENCE => $merchantReference,
            ExpenseTransfer::FK_SALES_ORDER => $salesOrderEntity->getIdSalesOrder(),
        ]);

        // Expect
        $this->mockKernelAppFacadeMakeRequestOnce();

        // Act
        $this->runMerchantPayoutReverseCommandByOrderPlugin($salesOrderEntity);
    }

    /**
     * @return void
     */
    public function testOrderExpensesAreNotSentWhenOrderHasUnRefusedItems(): void
    {
        // Arrange
        $this->tester->havePaymentProviderWithPaymentMethodSupportingPayouts();
        $this->tester->mockConfigMethod('isOrderExpenseIncludedInPaymentProcess', true);

        $merchantReference = Uuid::uuid4()->toString();
        $orderReference = Uuid::uuid4()->toString();
        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => $merchantReference,
        ]);
        $salesOrderItemWithMerchant = $this->tester->createSalesOrderItemEntity($merchantReference);
        $salesOrderEntity = $this->tester->mockSalesOrderEntity(
            [$salesOrderItemWithMerchant],
            $merchantReference,
            $orderReference,
        );

        $this->tester->haveSalesPaymentMerchantPayoutPersisted(
            [
                'transfer_id' => Uuid::uuid4()->toString(),
                'merchant_reference' => $merchantReference,
                'order_reference' => $orderReference,
                'item_references' => $salesOrderItemWithMerchant->getOrderItemReference(),
                'is_successful' => true,
                'amount' => 900,
            ],
        );

        $this->tester->haveSalesExpense([
            ExpenseTransfer::TYPE => static::SHIPMENT_EXPENSE_TYPE,
            ExpenseTransfer::MERCHANT_REFERENCE => $merchantReference,
            ExpenseTransfer::FK_SALES_ORDER => $salesOrderEntity->getIdSalesOrder(),
        ]);

        // Expect
        $this->mockKernelAppFacadeMakeRequestOnce();

        // Act
        $this->runMerchantPayoutReverseCommandByOrderPlugin($salesOrderEntity);
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

        $this->tester->setDependency(
            SalesPaymentMerchantDependencyProvider::FACADE_KERNEL_APP,
            new SalesPaymentMerchantToKernelAppFacadeBridge($transferRequestSenderMock),
        );
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return array<mixed>
     */
    protected function runMerchantPayoutReverseCommandByOrderPlugin(SpySalesOrder $salesOrderEntity): array
    {
        $merchantPayoutReverseCommandByOrderPlugin = new MerchantPayoutReverseCommandByOrderPlugin();
        $merchantPayoutReverseCommandByOrderPlugin->setFacade($this->tester->getFacade());

        return $merchantPayoutReverseCommandByOrderPlugin->run(
            $salesOrderEntity->getItems()->getArrayCopy(),
            $salesOrderEntity,
            new ReadOnlyArrayObject([]),
        );
    }
}

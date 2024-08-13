<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesPaymentMerchant\Communication\Plugin\Oms\Command;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EndpointTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\PaymentMethodAppConfigurationTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Shared\Kernel\Container\GlobalContainer;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\SalesPaymentMerchant\Communication\Plugin\Oms\Command\MerchantPayoutReverseCommandByOrderPlugin;
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
        $merchantPayoutCommandPlugin = new MerchantPayoutReverseCommandByOrderPlugin();
        $response = $merchantPayoutCommandPlugin->run($orderItems, $salesOrderEntity, new ReadOnlyArrayObject([]));

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

        $paymentProviderTransfer = $this->tester->havePaymentProvider([
            PaymentProviderTransfer::NAME => 'Foo',
            PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => 'foo',
        ]);

        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::NAME => 'bar',
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => 'foo-bar',
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
            PaymentMethodTransfer::PAYMENT_METHOD_APP_CONFIGURATION => [
                PaymentMethodAppConfigurationTransfer::BASE_URL => 'http://localhost:8080',
                PaymentMethodAppConfigurationTransfer::ENDPOINTS => [
                    [
                        EndpointTransfer::NAME => 'transfer',
                        EndpointTransfer::PATH => '/reverse-payouts',
                    ],
                ],
            ],
        ]);

        $this->tester->mockHydrateOrderPluginsInSalesModule();

        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => $merchantReference,
        ]);

        $salesOrderItemWithMerchant = $this->tester->createSalesOrderItemEntity($merchantReference);
        $salesOrderEntity = $this->tester->mockSalesOrderEntity([$salesOrderItemWithMerchant], $merchantReference, $orderReference);
        $orderItems = $salesOrderEntity->getItems()->getArrayCopy();

        $salesPaymentMerchantPayoutEntity = $this->tester->haveSalesPaymentMerchantPayoutPersisted([
            'transfer_id' => $transferId,
            'merchant_reference' => $merchantReference,
            'order_reference' => $orderReference,
            'item_references' => $salesOrderItemWithMerchant->getOrderItemReference(),
            'is_successful' => true,
            'amount' => 900,
        ]);

        $salesOrderEntity->addSpySalesPaymentMerchantPayout($salesPaymentMerchantPayoutEntity);

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
        $merchantPayoutCommandPlugin = new MerchantPayoutReverseCommandByOrderPlugin();
        $merchantPayoutCommandPlugin->run($orderItems, $salesOrderEntity, new ReadOnlyArrayObject([]));

        // Assert
        $this->tester->assertSalesPaymentMerchantRefundEntity($merchantReference, $orderReference, [$salesOrderItemWithMerchant]);
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

        $paymentProviderTransfer = $this->tester->havePaymentProvider([
            PaymentProviderTransfer::NAME => 'Foo',
            PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => 'foo',
        ]);

        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::NAME => 'bar',
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => 'foo-bar',
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
            PaymentMethodTransfer::PAYMENT_METHOD_APP_CONFIGURATION => [
                PaymentMethodAppConfigurationTransfer::BASE_URL => 'http://localhost:8080',
                PaymentMethodAppConfigurationTransfer::ENDPOINTS => [
                    [
                        EndpointTransfer::NAME => 'transfer',
                        EndpointTransfer::PATH => '/reverse-payouts',
                    ],
                ],
            ],
        ]);

        $this->tester->mockHydrateOrderPluginsInSalesModule();

        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => $merchantReferenceOne,
        ]);

        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => $merchantReferenceTwo,
        ]);

        $salesOrderItemWithMerchantOne = $this->tester->createSalesOrderItemEntity($merchantReferenceOne);
        $salesOrderItemWithMerchantTwo = $this->tester->createSalesOrderItemEntity($merchantReferenceTwo);

        $salesOrderEntity = $this->tester->mockSalesOrderEntity([$salesOrderItemWithMerchantOne, $salesOrderItemWithMerchantTwo], $merchantReferenceOne, $orderReference);
        $orderItems = $salesOrderEntity->getItems()->getArrayCopy();

        $salesPaymentMerchantPayoutEntityErrored = $this->tester->haveSalesPaymentMerchantPayoutPersisted([
            'transfer_id' => null, // A failed response does not have a transfer id
            'order_reference' => $orderReference,
            'item_references' => '',
            'is_successful' => false,
            'amount' => 0,
        ]);

        $salesPaymentMerchantPayoutEntity = $this->tester->haveSalesPaymentMerchantPayoutPersisted([
            'transfer_id' => $transferId,
            'merchant_reference' => $merchantReferenceOne,
            'order_reference' => $orderReference,
            'item_references' => $salesOrderItemWithMerchantOne->getOrderItemReference(),
            'is_successful' => true,
            'amount' => 900,
        ]);

        $salesOrderEntity->addSpySalesPaymentMerchantPayout($salesPaymentMerchantPayoutEntity);
        $salesOrderEntity->addSpySalesPaymentMerchantPayout($salesPaymentMerchantPayoutEntityErrored);

        $this->tester->mockExpectedResponseFromApp(
            $merchantReferenceOne,
            $orderReference,
            [['itemReference' => $salesOrderItemWithMerchantOne->getOrderItemReference()]],
            '-900',
        );

        // Act
        $merchantPayoutCommandPlugin = new MerchantPayoutReverseCommandByOrderPlugin();
        $merchantPayoutCommandPlugin->run($orderItems, $salesOrderEntity, new ReadOnlyArrayObject([]));

        // Assert
        $this->tester->assertSalesPaymentMerchantRefundEntity($merchantReferenceOne, $orderReference, [$salesOrderItemWithMerchantOne]);
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

        $paymentProviderTransfer = $this->tester->havePaymentProvider([
            PaymentProviderTransfer::NAME => 'Foo',
            PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => 'foo',
        ]);

        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::NAME => 'bar',
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => 'foo-bar',
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
            PaymentMethodTransfer::PAYMENT_METHOD_APP_CONFIGURATION => [
                PaymentMethodAppConfigurationTransfer::BASE_URL => 'http://localhost:8080',
                PaymentMethodAppConfigurationTransfer::ENDPOINTS => [
                    [
                        EndpointTransfer::NAME => 'transfer',
                        EndpointTransfer::PATH => '/payouts',
                    ],
                ],
            ],
        ]);

        $this->tester->haveMerchant([MerchantTransfer::MERCHANT_REFERENCE => $merchantReference]);

        $salesOrderItemWithMerchant = $this->tester->createSalesOrderItemEntity($merchantReference);
        $salesOrderEntity = $this->tester->mockSalesOrderEntity([$salesOrderItemWithMerchant], $merchantReference, $orderReference);
        $orderItems = $salesOrderEntity->getItems()->getArrayCopy();

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
        $merchantPayoutCommandPlugin = new MerchantPayoutReverseCommandByOrderPlugin();
        $merchantPayoutCommandPlugin->run($orderItems, $salesOrderEntity, new ReadOnlyArrayObject([]));
    }
}

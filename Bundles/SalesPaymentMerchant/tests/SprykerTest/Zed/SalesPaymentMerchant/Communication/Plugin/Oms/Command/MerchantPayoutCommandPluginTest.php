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
use Spryker\Zed\SalesPaymentMerchant\Communication\Plugin\Oms\Command\MerchantPayoutCommandByOrderPlugin;
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
     * @var \SprykerTest\Zed\SalesPaymentMerchant\SalesPaymentMerchantCommunicationTester $tester
     */
    protected SalesPaymentMerchantCommunicationTester $tester;

    /**
     * Test ensures that when a payment method is not configured to transfer payments, the command is skipped without a failure.
     *
     * @return void
     */
    public function testGivenAnOrderWithOneOrderItemFromAMerchantWhenTheCommandIsExecutedAndThePaymentMethodDoesNotHaveTransferOfPaymentsEnabledTheCommandIsSkipped(): void
    {
        // Arrange
        $merchantReference = Uuid::uuid4()->toString();
        $orderReference = Uuid::uuid4()->toString();

        $globalContainer = new GlobalContainer();
        $globalContainer->setContainer(new Container([
            'locale' => 'de_DE',
        ]));

        $paymentProviderTransfer = $this->tester->havePaymentProvider([
            PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => 'foo',
            PaymentProviderTransfer::NAME => 'foo',
        ]);

        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::NAME => 'Foo',
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => 'foo-bar',
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
        ]);

        $this->tester->mockHydrateOrderPluginsInSalesModule();

        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => $merchantReference,
        ]);

        $salesOrderItemWithMerchant = $this->tester->createSalesOrderItemEntity($merchantReference);
        $salesOrderEntity = $this->tester->mockSalesOrderEntity([$salesOrderItemWithMerchant], $merchantReference, $orderReference);
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
            PaymentMethodTransfer::NAME => 'Foo',
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

        $this->tester->mockHydrateOrderPluginsInSalesModule();

        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => $merchantReference,
        ]);

        $salesOrderItemWithMerchant = $this->tester->createSalesOrderItemEntity($merchantReference);
        $salesOrderEntity = $this->tester->mockSalesOrderEntity([$salesOrderItemWithMerchant], $merchantReference, $orderReference);
        $orderItems = $salesOrderEntity->getItems()->getArrayCopy();

        $this->tester->mockExpectedResponseFromApp(
            $merchantReference,
            $orderReference,
            [['itemReference' => $salesOrderItemWithMerchant->getOrderItemReference()]],
            '900',
        );

        // Act
        $merchantPayoutCommandPlugin = new MerchantPayoutCommandByOrderPlugin();
        $merchantPayoutCommandPlugin->run($orderItems, $salesOrderEntity, new ReadOnlyArrayObject([]));

        // Assert
        $this->tester->assertSalesPaymentMerchantPayoutEntity($merchantReference, $orderReference, [$salesOrderItemWithMerchant]);
    }

    /**
     * @return void
     */
    public function testAmountCalculatorPluginIsCalledWhenPluggedIn(): void
    {
        // Arrange
        $merchantReference = Uuid::uuid4()->toString();
        $orderReference = Uuid::uuid4()->toString();

        $paymentProviderTransfer = $this->tester->havePaymentProvider([
            PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => 'foo',
            PaymentProviderTransfer::NAME => 'foo',
        ]);

        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::NAME => 'Foo',
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

        $salesOrderItemWithMerchant = $this->tester->createSalesOrderItemEntity($merchantReference);
        $salesOrderEntity = $this->tester->mockSalesOrderEntity([$salesOrderItemWithMerchant], $merchantReference, $orderReference);
        $orderItems = $salesOrderEntity->getItems()->getArrayCopy();

        $this->tester->haveMerchant([MerchantTransfer::MERCHANT_REFERENCE => $merchantReference]);
        $this->tester->mockExpectedResponseFromApp(
            $merchantReference,
            $orderReference,
            [['itemReference' => $salesOrderItemWithMerchant->getOrderItemReference()]],
            '900',
        );

        //Expect
        $amountCalculatorPluginMock = $this->createMock(MerchantPayoutCalculatorPluginInterface::class);
        $amountCalculatorPluginMock->expects($this->once())
            ->method('calculatePayoutAmount')
            ->willReturn(100);

        $this->tester->setDependency(
            SalesPaymentMerchantDependencyProvider::PLUGIN_MERCHANT_PAYOUT_AMOUNT_CALCULATOR,
            $amountCalculatorPluginMock,
        );

        // Act
        $merchantPayoutCommandPlugin = new MerchantPayoutCommandByOrderPlugin();
        $merchantPayoutCommandPlugin->run($orderItems, $salesOrderEntity, new ReadOnlyArrayObject([]));
    }
}

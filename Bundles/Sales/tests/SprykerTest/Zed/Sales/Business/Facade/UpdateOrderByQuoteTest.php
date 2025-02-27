<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\OrderBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteProcessFlowTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\CheckoutExtension\CheckoutExtensionContextsInterface;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Shared\SalesOrderAmendmentExtension\SalesOrderAmendmentExtensionContextsInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToLocaleInterface;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface;
use SprykerTest\Zed\Sales\SalesBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group UpdateOrderByQuoteTest
 * Add your own group annotations below this line
 */
class UpdateOrderByQuoteTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     *
     * @var string
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @var string
     */
    protected const TEST_CURRENCY_ISO_CODE = 'XTS';

    /**
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
     */
    protected SalesBusinessTester $tester;

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
    public function testShouldUpdateOrderCustomerData(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);
        $orderTransfer = $this->tester->getOrderByIdSalesOrder($orderTransfer->getIdSalesOrder());
        $saveOrderTransfer = (new SaveOrderTransfer())->fromArray($orderTransfer->toArray(), true);

        $quoteTransfer = (new QuoteBuilder([QuoteTransfer::PRICE_MODE => static::PRICE_MODE_GROSS]))
            ->withCustomer($customerTransfer->toArray())
            ->withBillingAddress()
            ->withStore()
            ->withCurrency()
            ->build();
        $quoteTransfer->getCustomer()->setFirstName('Updated first name');
        $quoteTransfer->getCustomer()->setLastName('Updated last name');
        $quoteTransfer->setOriginalOrder($orderTransfer);

        // Act
        $this->tester->getFacade()->updateOrderByQuote($quoteTransfer, $saveOrderTransfer);

        // Assert
        $updatedOrderTransfer = $this->tester->getOrderByIdSalesOrder($orderTransfer->getIdSalesOrder());
        $this->assertSame('Updated first name', $updatedOrderTransfer->getFirstName());
        $this->assertSame('Updated last name', $updatedOrderTransfer->getLastName());
    }

    /**
     * @return void
     */
    public function testShouldUpdateOrderCurrency(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);
        $orderTransfer = $this->tester->getOrderByIdSalesOrder($orderTransfer->getIdSalesOrder());
        $saveOrderTransfer = (new SaveOrderTransfer())->fromArray($orderTransfer->toArray(), true);

        $currencyTransfer = $this->tester->haveCurrencyTransfer([CurrencyTransfer::CODE => static::TEST_CURRENCY_ISO_CODE]);
        $quoteTransfer = (new QuoteBuilder([QuoteTransfer::PRICE_MODE => static::PRICE_MODE_GROSS]))
            ->withCustomer($customerTransfer->toArray())
            ->withBillingAddress()
            ->withStore()
            ->build();
        $quoteTransfer->setCurrency($currencyTransfer);
        $quoteTransfer->setOriginalOrder($orderTransfer);

        // Act
        $this->tester->getFacade()->updateOrderByQuote($quoteTransfer, $saveOrderTransfer);

        // Assert
        $updatedOrderTransfer = $this->tester->getOrderByIdSalesOrder($orderTransfer->getIdSalesOrder());
        $this->assertSame(static::TEST_CURRENCY_ISO_CODE, $updatedOrderTransfer->getCurrencyIsoCode());
    }

    /**
     * @return void
     */
    public function testShouldUpdateBillingAddress(): void
    {
        $customerTransfer = $this->tester->haveCustomer();
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);
        $orderTransfer = $this->tester->getOrderByIdSalesOrder($orderTransfer->getIdSalesOrder());
        $saveOrderTransfer = (new SaveOrderTransfer())->fromArray($orderTransfer->toArray(), true);

        $quoteTransfer = (new QuoteBuilder([QuoteTransfer::PRICE_MODE => static::PRICE_MODE_GROSS]))
            ->withCustomer($customerTransfer->toArray())
            ->withBillingAddress([AddressTransfer::ADDRESS1 => 'Billing address 1'])
            ->withStore()
            ->withCurrency()
            ->build();
        $quoteTransfer->setOriginalOrder($orderTransfer);

        // Act
        $this->tester->getFacade()->updateOrderByQuote($quoteTransfer, $saveOrderTransfer);

        // Assert
        $salesOrderAddressEntity = $this->tester->findSalesOrderAddressEntityById($orderTransfer->getBillingAddressOrFail()->getIdSalesOrderAddressOrFail());
        $this->assertNotNull($salesOrderAddressEntity);
        $this->assertSame('Billing address 1', $salesOrderAddressEntity->getAddress1());
    }

    /**
     * @return void
     */
    public function testShouldUpdateShippingAddress(): void
    {
        $customerTransfer = $this->tester->haveCustomer();
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);
        $orderTransfer = $this->tester->getOrderByIdSalesOrder($orderTransfer->getIdSalesOrder());
        $saveOrderTransfer = (new SaveOrderTransfer())->fromArray($orderTransfer->toArray(), true);

        $quoteTransfer = (new QuoteBuilder([QuoteTransfer::PRICE_MODE => static::PRICE_MODE_GROSS]))
            ->withCustomer($customerTransfer->toArray())
            ->withBillingAddress()
            ->withShippingAddress([AddressTransfer::ADDRESS1 => 'Shipping address 1'])
            ->withStore()
            ->withCurrency()
            ->build();
        $quoteTransfer->setOriginalOrder($orderTransfer);

        // Act
        $this->tester->getFacade()->updateOrderByQuote($quoteTransfer, $saveOrderTransfer);

        // Assert
        $salesOrderAddressEntity = $this->tester->findSalesOrderAddressEntityById($orderTransfer->getShippingAddressOrFail()->getIdSalesOrderAddressOrFail());
        $this->assertNotNull($salesOrderAddressEntity);
        $this->assertSame('Shipping address 1', $salesOrderAddressEntity->getAddress1());
    }

    /**
     * @return void
     */
    public function testShouldCreateShippingAddressWhenOrderDidNotHaveItPreviously(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $quoteTransfer = (new QuoteBuilder([QuoteTransfer::PRICE_MODE => static::PRICE_MODE_GROSS]))
            ->withCustomer($customerTransfer->toArray())
            ->withItem((new ItemBuilder())->withShipment((new ShipmentBuilder())->withShippingAddress()))
            ->withAnotherItem((new ItemBuilder())->withShipment((new ShipmentBuilder())->withShippingAddress()))
            ->withTotals()
            ->withBillingAddress()
            ->withStore()
            ->withCurrency()
            ->build();
        $saveOrderTransfer = $this->tester->haveOrderFromQuote($quoteTransfer, static::DEFAULT_OMS_PROCESS_NAME);
        $orderTransfer = $this->tester->getOrderByIdSalesOrder($saveOrderTransfer->getIdSalesOrderOrFail());

        $addressTransfer = (new AddressBuilder())->build();
        $quoteTransfer
            ->setOriginalOrder($orderTransfer)
            ->setShippingAddress($addressTransfer);

        // Act
        $saveOrderTransfer = $this->tester->getFacade()->updateOrderByQuote($quoteTransfer, $saveOrderTransfer);

        // Assert
        $updatedOrderTransfer = $this->tester->getOrderByIdSalesOrder($saveOrderTransfer->getIdSalesOrderOrFail());
        $this->assertNotNull($updatedOrderTransfer->getShippingAddress());
        $this->assertSame($addressTransfer->getAddress1(), $updatedOrderTransfer->getShippingAddressOrFail()->getAddress1());
    }

    /**
     * @return void
     */
    public function testShouldResetShippingAddressWhenQuoteDoesNotHaveShippingAddress(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);
        $orderTransfer = $this->tester->getOrderByIdSalesOrder($orderTransfer->getIdSalesOrder());
        $saveOrderTransfer = (new SaveOrderTransfer())->fromArray($orderTransfer->toArray(), true);

        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::PRICE_MODE => static::PRICE_MODE_GROSS,
            QuoteTransfer::SHIPPING_ADDRESS => null,
        ]))
            ->withCustomer($customerTransfer->toArray())
            ->withBillingAddress([AddressTransfer::ADDRESS1 => 'Billing address 1'])
            ->withStore()
            ->withCurrency()
            ->build();
        $quoteTransfer->setOriginalOrder($orderTransfer);

        // Act
        $saveOrderTransfer = $this->tester->getFacade()->updateOrderByQuote($quoteTransfer, $saveOrderTransfer);

        // Assert
        $updatedOrderTransfer = $this->tester->getOrderByIdSalesOrder($saveOrderTransfer->getIdSalesOrderOrFail());
        $this->assertNull($updatedOrderTransfer->getShippingAddress());
    }

    /**
     * @return void
     */
    public function testShouldSetCurrentLocaleId(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);
        $orderTransfer = $this->tester->getOrderByIdSalesOrder($orderTransfer->getIdSalesOrder());
        $saveOrderTransfer = (new SaveOrderTransfer())->fromArray($orderTransfer->toArray(), true);

        $quoteTransfer = (new QuoteBuilder([QuoteTransfer::PRICE_MODE => static::PRICE_MODE_GROSS]))
            ->withCustomer($customerTransfer->toArray())
            ->withBillingAddress()
            ->withStore()
            ->withCurrency()
            ->build();
        $quoteTransfer->setOriginalOrder($orderTransfer);
        $this->mockLocaleFacadeDependency((new LocaleTransfer())->setIdLocale(1));

        // Act
        $this->tester->getFacade()->updateOrderByQuote($quoteTransfer, $saveOrderTransfer);

        // Assert
        $salesOrderEntity = $this->tester->findSalesOrderEntityById($orderTransfer->getIdSalesOrderOrFail());
        $this->assertNotNull($salesOrderEntity);
        $this->assertSame(1, $salesOrderEntity->getFkLocale());
    }

    /**
     * @dataProvider updateOrderByQuoteSelectsContextCorrectlyDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteProcessFlowTransfer|null $quoteProcessFlowTransfer
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface $checkoutContextPluginMock
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface $orderAmendmentContextPluginMock
     *
     * @return void
     */
    public function testUpdateOrderByQuoteSelectsContextCorrectly($quoteProcessFlowTransfer, $checkoutContextPluginMock, $orderAmendmentContextPluginMock): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);
        $orderTransfer = $this->tester->getOrderByIdSalesOrder($orderTransfer->getIdSalesOrder());
        $quoteTransfer = (new QuoteBuilder([QuoteTransfer::PRICE_MODE => static::PRICE_MODE_GROSS]))
            ->withCustomer($customerTransfer->toArray())
            ->withBillingAddress()
            ->withStore()
            ->withCurrency()
            ->build();
        $quoteTransfer->setOriginalOrder($orderTransfer)->setQuoteProcessFlow($quoteProcessFlowTransfer);

        // Assert
        $this->tester->setDependency(SalesDependencyProvider::PLUGINS_ORDER_POST_SAVE, [$checkoutContextPluginMock]);
        $this->tester->setDependency(SalesDependencyProvider::ORDER_ITEM_EXPANDER_PRE_SAVE_PLUGINS, [$orderAmendmentContextPluginMock]);

        // Act
        $this->tester->getFacade()->updateOrderByQuote($quoteTransfer, new SaveOrderTransfer());
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface
     */
    protected function getNeverCalledOrderPostSavePluginMock(): OrderPostSavePluginInterface
    {
        $orderPostSavePluginMock = $this
            ->getMockBuilder(OrderPostSavePluginInterface::class)
            ->getMock();
        $orderPostSavePluginMock->expects($this->never())->method('execute');

        return $orderPostSavePluginMock;
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface
     */
    protected function getOnceCalledOrderPostSavePluginMock(): OrderPostSavePluginInterface
    {
        $orderPostSavePluginMock = $this
            ->getMockBuilder(OrderPostSavePluginInterface::class)
            ->getMock();
        $orderPostSavePluginMock->expects($this->once())->method('execute');

        return $orderPostSavePluginMock;
    }

    /**
     * @return array<array>
     */
    protected function updateOrderByQuoteSelectsContextCorrectlyDataProvider(): array
    {
        return [
            'Calls default context when default context is set' => [
                (new QuoteProcessFlowTransfer())->setName(CheckoutExtensionContextsInterface::CONTEXT_CHECKOUT),
                $this->getOnceCalledOrderPostSavePluginMock(),
                $this->getNeverCalledOrderPostSavePluginMock(),
            ],
            'Calls default context when quote process flow is not set' => [
                null,
                $this->getOnceCalledOrderPostSavePluginMock(),
                $this->getNeverCalledOrderPostSavePluginMock(),
            ],
            'Calls default context when context is not defined' => [
                (new QuoteProcessFlowTransfer())->setName('wrong-context'),
                $this->getOnceCalledOrderPostSavePluginMock(),
                $this->getNeverCalledOrderPostSavePluginMock(),
            ],
            'Calls order amendment context when order amendment context is set' => [
                (new QuoteProcessFlowTransfer())->setName(SalesOrderAmendmentExtensionContextsInterface::CONTEXT_ORDER_AMENDMENT),
                $this->getNeverCalledOrderPostSavePluginMock(),
                $this->getOnceCalledOrderPostSavePluginMock(),
            ],
        ];
    }

    /**
     * @dataProvider shouldThrowNullValueExceptionWhenRequiredQuoteDataIsNotProvidedDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $expectedMessage
     *
     * @return void
     */
    public function testShouldThrowNullValueExceptionWhenRequiredQuoteDataIsNotProvided(QuoteTransfer $quoteTransfer, string $expectedMessage): void
    {
        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage($expectedMessage);

        // Act
        $this->tester->getFacade()->updateOrderByQuote($quoteTransfer, new SaveOrderTransfer());
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\QuoteTransfer|string>>
     */
    protected function shouldThrowNullValueExceptionWhenRequiredQuoteDataIsNotProvidedDataProvider(): array
    {
        return [
            'Quote.originalOrder is not set' => [
                (new QuoteBuilder())->build(),
                'Property "originalOrder" of transfer `Generated\Shared\Transfer\QuoteTransfer` is null.',
            ],
            'Quote.customer is not set' => [
                (new QuoteBuilder([QuoteTransfer::CUSTOMER => null]))
                    ->withOriginalOrder()
                    ->build(),
                'Property "customer" of transfer `Generated\Shared\Transfer\QuoteTransfer` is null.',
            ],
            'Quote.customer.customerReference is not set' => [
                (new QuoteBuilder())
                    ->withOriginalOrder()
                    ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => null])
                    ->build(),
                'Property "customerReference" of transfer `Generated\Shared\Transfer\CustomerTransfer` is null.',
            ],
            'Quote.currency is not set' => [
                (new QuoteBuilder([QuoteTransfer::CURRENCY => null]))
                    ->withOriginalOrder()
                    ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => 'customer-reference'])
                    ->build(),
                'Property "currency" of transfer `Generated\Shared\Transfer\QuoteTransfer` is null.',
            ],
            'Quote.currency.code is not set' => [
                (new QuoteBuilder())
                    ->withOriginalOrder()
                    ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => 'customer-reference'])
                    ->withCurrency([CurrencyTransfer::CODE => null])
                    ->build(),
                'Property "code" of transfer `Generated\Shared\Transfer\CurrencyTransfer` is null.',
            ],
            'Quote.priceMode is not set' => [
                (new QuoteBuilder([QuoteTransfer::PRICE_MODE => null]))
                    ->withOriginalOrder()
                    ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => 'customer-reference'])
                    ->withCurrency()
                    ->build(),
                'Property "priceMode" of transfer `Generated\Shared\Transfer\QuoteTransfer` is null.',
            ],
            'Quote.store is not set' => [
                (new QuoteBuilder([
                    QuoteTransfer::PRICE_MODE => static::PRICE_MODE_GROSS,
                    QuoteTransfer::STORE => null,
                ]))
                    ->withOriginalOrder()
                    ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => 'customer-reference'])
                    ->withCurrency()
                    ->build(),
                'Property "store" of transfer `Generated\Shared\Transfer\QuoteTransfer` is null.',
            ],
            'Quote.store.name is not set' => [
                (new QuoteBuilder([QuoteTransfer::PRICE_MODE => static::PRICE_MODE_GROSS]))
                    ->withOriginalOrder()
                    ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => 'customer-reference'])
                    ->withCurrency()
                    ->withStore([StoreTransfer::NAME => null])
                    ->build(),
                'Property "name" of transfer `Generated\Shared\Transfer\StoreTransfer` is null.',
            ],
            'Quote.originalOrder.billingAddress is not set' => [
                (new QuoteBuilder([QuoteTransfer::PRICE_MODE => static::PRICE_MODE_GROSS]))
                    ->withOriginalOrder((new OrderBuilder([OrderTransfer::BILLING_ADDRESS => null])))
                    ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => 'customer-reference'])
                    ->withCurrency()
                    ->withStore()
                    ->withBillingAddress()
                    ->build(),
                'Property "billingAddress" of transfer `Generated\Shared\Transfer\OrderTransfer` is null.',
            ],
            'Quote.billingAddress is not set' => [
                (new QuoteBuilder([
                    QuoteTransfer::PRICE_MODE => static::PRICE_MODE_GROSS,
                    QuoteTransfer::BILLING_ADDRESS => null,
                ]))
                    ->withOriginalOrder((new OrderBuilder([OrderTransfer::BILLING_ADDRESS => (new AddressBuilder())->build()])))
                    ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => 'customer-reference'])
                    ->withCurrency()
                    ->withStore()
                    ->build(),
                'Property "billingAddress" of transfer `Generated\Shared\Transfer\QuoteTransfer` is null.',
            ],
            'Quote.originalOrder.billingAddress.idSalesOrderAddress is not set' => [
                (new QuoteBuilder([QuoteTransfer::PRICE_MODE => static::PRICE_MODE_GROSS]))
                    ->withOriginalOrder((new OrderBuilder([
                        OrderTransfer::BILLING_ADDRESS => (new AddressBuilder([AddressTransfer::ID_SALES_ORDER_ADDRESS => null]))->build(),
                    ])))
                    ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => 'customer-reference'])
                    ->withCurrency()
                    ->withStore()
                    ->withBillingAddress()
                    ->build(),
                'Property "idSalesOrderAddress" of transfer `Generated\Shared\Transfer\AddressTransfer` is null.',
            ],
            'Quote.billingAddress.iso2Code is not set' => [
                (new QuoteBuilder([QuoteTransfer::PRICE_MODE => static::PRICE_MODE_GROSS]))
                    ->withOriginalOrder((new OrderBuilder([
                        OrderTransfer::BILLING_ADDRESS => (new AddressBuilder([AddressTransfer::ID_SALES_ORDER_ADDRESS => 1]))->build(),
                    ])))
                    ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => 'customer-reference'])
                    ->withCurrency()
                    ->withStore()
                    ->withBillingAddress([AddressTransfer::ISO2_CODE => null])
                    ->build(),
                'Property "iso2Code" of transfer `Generated\Shared\Transfer\AddressTransfer` is null.',
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function mockLocaleFacadeDependency(LocaleTransfer $localeTransfer): void
    {
        $localeFacadeMock = $this->createMock(SalesToLocaleInterface::class);
        $localeFacadeMock->method('getCurrentLocale')->willReturn($localeTransfer);

        $this->tester->setDependency(
            SalesDependencyProvider::FACADE_LOCALE,
            $localeFacadeMock,
        );
    }
}

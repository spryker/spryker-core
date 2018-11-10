<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CheckoutRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\AddressesBuilder;
use Generated\Shared\DataBuilder\CheckoutResponseBuilder;
use Generated\Shared\DataBuilder\CustomerResponseBuilder;
use Generated\Shared\DataBuilder\PaymentBuilder;
use Generated\Shared\DataBuilder\PaymentMethodsBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\QuoteResponseBuilder;
use Generated\Shared\DataBuilder\ShipmentMethodsBuilder;
use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\Cart\Business\CartFacade;
use Spryker\Zed\Checkout\Business\CheckoutFacade;
use Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartFacadeBridge;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCheckoutFacadeBridge;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCustomerFacadeBridge;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToPaymentFacadeBridge;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToShipmentFacadeBridge;
use Spryker\Zed\Customer\Business\CustomerFacade;
use Spryker\Zed\Payment\Business\PaymentFacade;
use Spryker\Zed\Shipment\Business\ShipmentFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CheckoutRestApi
 * @group Business
 * @group Facade
 * @group CheckoutRestApiFacadeTest
 * Add your own group annotations below this line
 */
class CheckoutRestApiFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CheckoutRestApi\CheckoutRestApiBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $product;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customer;

    /**
     * @return void
     */
    public function testGetCheckoutDataWillReturnNotEmptyCheckoutDataTransfer()
    {
        /**
         * @var \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiFacade $checkoutRestApiFacade
         */
        $checkoutRestApiFacade = $this->tester->getFacade();
        $mockCheckoutRestApiFactory = $this->getMockCheckoutRestApiFactory();
        $checkoutRestApiFacade->setFactory($mockCheckoutRestApiFactory);
        $quoteTransfer = $this->createQuoteTransfer();

        $checkoutDataTransfer = $checkoutRestApiFacade->getCheckoutData($quoteTransfer);

        $this->assertNotEmpty($checkoutDataTransfer);
        $this->assertInstanceOf(CheckoutDataTransfer::class, $checkoutDataTransfer);
        $this->assertInstanceOf(AddressesTransfer::class, $checkoutDataTransfer->getAddresses());
        $this->assertInstanceOf(ShipmentMethodsTransfer::class, $checkoutDataTransfer->getShipmentMethods());
        $this->assertInstanceOf(PaymentMethodsTransfer::class, $checkoutDataTransfer->getPaymentMethods());
        $this->assertCount(1, $checkoutDataTransfer->getAddresses()->getAddresses());
        $this->assertCount(1, $checkoutDataTransfer->getShipmentMethods()->getMethods());
        $this->assertCount(2, $checkoutDataTransfer->getPaymentMethods()->getMethods());
    }

    /**
     * @return void
     */
    public function testGetCheckoutDataWillReturnNotEmptyCheckoutDataTransferForGuest()
    {
        /**
         * @var \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiFacade $checkoutRestApiFacade
         */
        $checkoutRestApiFacade = $this->tester->getFacade();
        $mockCheckoutRestApiFactory = $this->getMockCheckoutRestApiFactoryForGuest();
        $checkoutRestApiFacade->setFactory($mockCheckoutRestApiFactory);
        $quoteTransfer = $this->createQuoteTransfer();

        $checkoutDataTransfer = $checkoutRestApiFacade->getCheckoutData($quoteTransfer);

        $this->assertNotEmpty($checkoutDataTransfer);
        $this->assertInstanceOf(CheckoutDataTransfer::class, $checkoutDataTransfer);
        $this->assertInstanceOf(AddressesTransfer::class, $checkoutDataTransfer->getAddresses());
        $this->assertInstanceOf(ShipmentMethodsTransfer::class, $checkoutDataTransfer->getShipmentMethods());
        $this->assertInstanceOf(PaymentMethodsTransfer::class, $checkoutDataTransfer->getPaymentMethods());
        $this->assertCount(0, $checkoutDataTransfer->getAddresses()->getAddresses());
        $this->assertCount(1, $checkoutDataTransfer->getShipmentMethods()->getMethods());
        $this->assertCount(2, $checkoutDataTransfer->getPaymentMethods()->getMethods());
    }

    /**
     * @return void
     */
    public function testPlaceOrderWillPlaceOrderForCustomer()
    {
        /**
         * @var \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiFacade $checkoutRestApiFacade
         */
        $checkoutRestApiFacade = $this->tester->getFacade();
        $mockCheckoutRestApiFactory = $this->getMockCheckoutRestApiFactory();
        $checkoutRestApiFacade->setFactory($mockCheckoutRestApiFactory);

        $quoteTransfer = $this->createQuoteTransfer();

        $checkoutResponseTransfer = $checkoutRestApiFacade->placeOrder($quoteTransfer);

        $this->assertInstanceOf(CheckoutResponseTransfer::class, $checkoutResponseTransfer);
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testPlaceOrderWillPlaceOrderForGuest()
    {
        /**
         * @var \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiFacade $checkoutRestApiFacade
         */
        $checkoutRestApiFacade = $this->tester->getFacade();
        $mockCheckoutRestApiFactory = $this->getMockCheckoutRestApiFactoryForGuest();
        $checkoutRestApiFacade->setFactory($mockCheckoutRestApiFactory);

        $quoteTransfer = $this->createQuoteTransferForGuest();

        $checkoutResponseTransfer = $checkoutRestApiFacade->placeOrder($quoteTransfer);

        $this->assertInstanceOf(CheckoutResponseTransfer::class, $checkoutResponseTransfer);
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testPlaceOrderWillFailOnItemOutOfStock()
    {
        /**
         * @var \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiFacade $checkoutRestApiFacade
         */
        $checkoutRestApiFacade = $this->tester->getFacade();
        $mockCheckoutRestApiFactory = $this->getMockCheckoutRestApiFactoryWithFailingValidation();
        $checkoutRestApiFacade->setFactory($mockCheckoutRestApiFactory);

        $quoteTransfer = $this->createQuoteTransferWithItemOutOfStock();

        $checkoutResponseTransfer = $checkoutRestApiFacade->placeOrder($quoteTransfer);

        $this->assertInstanceOf(CheckoutResponseTransfer::class, $checkoutResponseTransfer);
        $this->assertNotTrue($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockCheckoutRestApiFactory(): MockObject
    {
        $mockCheckoutRestApiFactory = $this->createPartialMock(
            CheckoutRestApiBusinessFactory::class,
            [
                'getShipmentFacade',
                'getPaymentFacade',
                'getCustomerFacade',
                'getCartFacade',
                'getCheckoutFacade',
            ]
        );

        $mockCheckoutRestApiFactory = $this->addMockShipmentFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockPaymentFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCustomerFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCartFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCheckoutFacade($mockCheckoutRestApiFactory);

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockCheckoutRestApiFactoryWithFailingValidation(): MockObject
    {
        $mockCheckoutRestApiFactory = $this->createPartialMock(
            CheckoutRestApiBusinessFactory::class,
            [
                'getShipmentFacade',
                'getPaymentFacade',
                'getCustomerFacade',
                'getCartFacade',
                'getCheckoutFacade',
            ]
        );

        $mockCheckoutRestApiFactory = $this->addMockShipmentFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockPaymentFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCustomerFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCartFacadeWithFailingValidation($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCheckoutFacade($mockCheckoutRestApiFactory);

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockCheckoutRestApiFactoryForGuest(): MockObject
    {
        $mockCheckoutRestApiFactory = $this->createPartialMock(
            CheckoutRestApiBusinessFactory::class,
            [
                'getShipmentFacade',
                'getPaymentFacade',
                'getCustomerFacade',
                'getCartFacade',
                'getCheckoutFacade',
            ]
        );

        $mockCheckoutRestApiFactory = $this->addMockShipmentFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockPaymentFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCustomerFacadeForGuest($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCartFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCheckoutFacade($mockCheckoutRestApiFactory);

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(): QuoteTransfer
    {
        $product = $this->tester->haveProduct();
        $this->tester->haveProductInStock([StockProductTransfer::SKU => $product->getSku()]);

        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::SKU => $product->getSku(), ItemTransfer::UNIT_PRICE => 1])
            ->withStore([StoreTransfer::NAME => 'DE'])
            ->withCustomer(['isGuest' => false])
            ->withTotals(['priceToPay' => 9999])
            ->withCurrency()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withShipment()
            ->build();

        return $quoteTransfer->setPayment($this->getPaymentTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransferForGuest(): QuoteTransfer
    {
        $product = $this->tester->haveProduct();
        $this->tester->haveProductInStock([StockProductTransfer::SKU => $product->getSku()]);

        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::SKU => $product->getSku(), ItemTransfer::UNIT_PRICE => 1])
            ->withStore([StoreTransfer::NAME => 'DE'])
            ->withCustomer(['isGuest' => true])
            ->withTotals(['priceToPay' => 9999])
            ->withCurrency()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withShipment()
            ->build();

        return $quoteTransfer->setPayment($this->getPaymentTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransferWithItemOutOfStock(): QuoteTransfer
    {
        $product = $this->tester->haveProduct();

        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::SKU => $product->getSku(), ItemTransfer::UNIT_PRICE => 1])
            ->withStore([StoreTransfer::NAME => 'DE'])
            ->withCustomer(['isGuest' => true])
            ->withTotals(['priceToPay' => 9999])
            ->withCurrency()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withShipment()
            ->build();

        return $quoteTransfer->setPayment($this->getPaymentTransfer());
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockShipmentFacade(MockObject $mockCheckoutRestApiFactory): MockObject
    {
        $mockShipmentFacade = $this->createPartialMock(
            ShipmentFacade::class,
            ['getAvailableMethods']
        );
        $mockShipmentFacade
            ->method('getAvailableMethods')
            ->willReturn($this->createShipmentMethodsTransfer());

        $mockCheckoutRestApiFactory
            ->method('getShipmentFacade')
            ->willReturn(
                new CheckoutRestApiToShipmentFacadeBridge(
                    $mockShipmentFacade
                )
            );

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function createShipmentMethodsTransfer(): AbstractTransfer
    {
        $shipmentMethodData = [
            'carrierName' => 'Spryker Dummy Shipment',
            'idShipmentMethod' => '1',
            'name' => 'Standard',
            'storeCurrencyPrice' => '490',
        ];

        return (new ShipmentMethodsBuilder())->withMethod($shipmentMethodData)->build();
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockCartFacade(MockObject $mockCheckoutRestApiFactory): MockObject
    {
        $mockCartFacade = $this->createPartialMock(
            CartFacade::class,
            ['validateQuote']
        );
        $mockCartFacade
            ->method('validateQuote')
            ->willReturn($this->createQuoteResponseTransfer());

        $mockCheckoutRestApiFactory
            ->method('getCartFacade')
            ->willReturn(
                new CheckoutRestApiToCartFacadeBridge(
                    $mockCartFacade
                )
            );

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function createQuoteResponseTransfer(): AbstractTransfer
    {
        return (new QuoteResponseBuilder(['isSuccessful' => true]))
            ->withQuoteTransfer($this->createQuoteTransfer()->toArray())
            ->build();
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockCartFacadeWithFailingValidation(MockObject $mockCheckoutRestApiFactory): MockObject
    {
        $mockCartFacade = $this->createPartialMock(
            CartFacade::class,
            ['validateQuote']
        );
        $mockCartFacade
            ->method('validateQuote')
            ->willReturn($this->createQuoteResponseTransferWithFailingValidation());

        $mockCheckoutRestApiFactory
            ->method('getCartFacade')
            ->willReturn(
                new CheckoutRestApiToCartFacadeBridge(
                    $mockCartFacade
                )
            );

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function createQuoteResponseTransferWithFailingValidation(): AbstractTransfer
    {
        return (new QuoteResponseBuilder(['isSuccessful' => false]))
            ->withQuoteTransfer($this->createQuoteTransfer()->toArray())
            ->build();
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockCheckoutFacade(MockObject $mockCheckoutRestApiFactory): MockObject
    {
        $mockCheckoutFacade = $this->createPartialMock(
            CheckoutFacade::class,
            ['placeOrder']
        );
        $mockCheckoutFacade
            ->method('placeOrder')
            ->willReturn($this->createCheckoutResponseTransfer());

        $mockCheckoutRestApiFactory
            ->method('getCheckoutFacade')
            ->willReturn(
                new CheckoutRestApiToCheckoutFacadeBridge(
                    $mockCheckoutFacade
                )
            );

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function createCheckoutResponseTransfer(): AbstractTransfer
    {
        return (new CheckoutResponseBuilder(['isSuccess' => true]))->build();
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockPaymentFacade(MockObject $mockCheckoutRestApiFactory): MockObject
    {
        $mockPaymentFacade = $this->createPartialMock(
            PaymentFacade::class,
            ['getAvailableMethods']
        );
        $mockPaymentFacade
            ->method('getAvailableMethods')
            ->willReturn($this->createPaymentMethodsTransfer());

        $mockCheckoutRestApiFactory
            ->method('getPaymentFacade')
            ->willReturn(
                new CheckoutRestApiToPaymentFacadeBridge(
                    $mockPaymentFacade
                )
            );

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    protected function createPaymentMethodsTransfer(): AbstractTransfer
    {
        $paymentMethodData1 = [
            'methodName' => 'dummyPaymentInvoice',
        ];
        $paymentMethodData2 = [
            'methodName' => 'dummyPaymentCreditCard',
        ];

        return (new PaymentMethodsBuilder())
            ->withMethod($paymentMethodData1)
            ->withAnotherMethod($paymentMethodData2)
            ->build();
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockCustomerFacade(MockObject $mockCheckoutRestApiFactory): MockObject
    {
        $mockCustomerFacade = $this->createPartialMock(
            CustomerFacade::class,
            [
                'getAddresses',
                'findCustomerByReference',
            ]
        );
        $mockCustomerFacade
            ->method('getAddresses')
            ->willReturn($this->createAddressesTransfer());
        $mockCustomerFacade
            ->method('findCustomerByReference')
            ->willReturn($this->createCustomerResponseTransfer());

        $mockCheckoutRestApiFactory
            ->method('getCustomerFacade')
            ->willReturn(
                new CheckoutRestApiToCustomerFacadeBridge(
                    $mockCustomerFacade
                )
            );

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\AddressesTransfer
     */
    protected function createAddressesTransfer(): AbstractTransfer
    {
        $addressData = [
            'salutation' => 'Mr',
            'firstName' => 'Spencor',
            'lastName' => 'Hopkin',
            'address1' => 'Julie-Wolfthorn-Straße',
            'address2' => '1',
            'address3' => null,
            'zipCode' => '10115',
            'city' => 'Berlin',
            'iso2Code' => 'DE',
            'company' => 'spryker',
            'phone' => '+49 (30) 2084 98350',
            'isDefaultShipping' => null,
            'isDefaultBilling' => null,
        ];

        return (new AddressesBuilder())->withAddress($addressData)->build();
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\CustomerResponseTransfer
     */
    protected function createCustomerResponseTransfer(): AbstractTransfer
    {
        return (new CustomerResponseBuilder())
            ->withCustomerTransfer($this->tester->haveCustomer()->toArray())
            ->build();
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockCustomerFacadeForGuest(MockObject $mockCheckoutRestApiFactory): MockObject
    {
        $mockCustomerFacade = $this->createPartialMock(
            CustomerFacade::class,
            ['getAddresses']
        );
        $mockCustomerFacade
            ->method('getAddresses')
            ->willReturn((new AddressesBuilder())->build());

        $mockCheckoutRestApiFactory
            ->method('getCustomerFacade')
            ->willReturn(
                new CheckoutRestApiToCustomerFacadeBridge(
                    $mockCustomerFacade
                )
            );

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\PaymentTransfer
     */
    protected function getPaymentTransfer(): AbstractTransfer
    {
        $paymentTransferData = [
            "dummyPaymentInvoice" => [
                "dateOfBirth" => "08.04.1986",
            ],
            "paymentMethod" => "invoice",
            "paymentProvider" => "dummyPayment",
            "paymentSelection" => "dummyPaymentInvoice",
            "amount" => "899910",
        ];

        return (new PaymentBuilder($paymentTransferData))->build();
    }
}

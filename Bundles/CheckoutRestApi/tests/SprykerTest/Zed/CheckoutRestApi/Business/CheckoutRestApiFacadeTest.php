<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CheckoutRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\AddressesBuilder;
use Generated\Shared\DataBuilder\PaymentMethodsBuilder;
use Generated\Shared\DataBuilder\ShipmentMethodsBuilder;
use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory;
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
            ]
        );

        $this->addMockShipmentFacade($mockCheckoutRestApiFactory);
        $this->addMockPaymentFacade($mockCheckoutRestApiFactory);
        $this->addMockCustomerFacade($mockCheckoutRestApiFactory);

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
            ]
        );

        $this->addMockShipmentFacade($mockCheckoutRestApiFactory);
        $this->addMockPaymentFacade($mockCheckoutRestApiFactory);
        $this->addMockGuestCustomerFacade($mockCheckoutRestApiFactory);

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(): QuoteTransfer
    {
        $this->product = $this->tester->haveProduct();
        $this->customer = $this->tester->haveCustomer();
        $this->customer->setIsGuest(false);

        return $this->tester->havePersistentQuote([
            QuoteTransfer::ITEMS => [ItemTransfer::SKU => $this->product->getSku(), ItemTransfer::UNIT_PRICE => 1],
            QuoteTransfer::CUSTOMER => $this->customer,
            QuoteTransfer::STORE => [StoreTransfer::NAME => 'DE'],
        ]);
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return void
     */
    protected function addMockShipmentFacade(MockObject $mockCheckoutRestApiFactory): void
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
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
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
     * @return void
     */
    protected function addMockPaymentFacade(MockObject $mockCheckoutRestApiFactory): void
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
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function createPaymentMethodsTransfer(): AbstractTransfer
    {
        $paymentMethodData1 = [
            'methodName' => 'dummyPaymentInvoice',
        ];
        $paymentMethodData2 = [
            'methodName' => 'dummyPaymentCreditCard',
        ];

        return (new PaymentMethodsBuilder())->withMethod($paymentMethodData1)->withAnotherMethod($paymentMethodData2)->build();
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return void
     */
    protected function addMockCustomerFacade(MockObject $mockCheckoutRestApiFactory): void
    {
        $mockCustomerFacade = $this->createPartialMock(
            CustomerFacade::class,
            ['getAddresses']
        );
        $mockCustomerFacade
            ->method('getAddresses')
            ->willReturn($this->createAddressesTransfer());

        $mockCheckoutRestApiFactory
            ->method('getCustomerFacade')
            ->willReturn(
                new CheckoutRestApiToCustomerFacadeBridge(
                    $mockCustomerFacade
                )
            );
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return void
     */
    protected function addMockGuestCustomerFacade(MockObject $mockCheckoutRestApiFactory): void
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
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
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
}

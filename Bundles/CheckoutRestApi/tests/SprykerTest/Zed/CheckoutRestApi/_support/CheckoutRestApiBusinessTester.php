<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CheckoutRestApi;

use Codeception\Actor;
use Generated\Shared\DataBuilder\CheckoutResponseBuilder;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\DataBuilder\CustomerResponseBuilder;
use Generated\Shared\DataBuilder\PaymentBuilder;
use Generated\Shared\DataBuilder\PaymentMethodsBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\QuoteResponseBuilder;
use Generated\Shared\DataBuilder\RestCheckoutRequestAttributesBuilder;
use Generated\Shared\DataBuilder\ShipmentMethodBuilder;
use Generated\Shared\DataBuilder\ShipmentMethodsBuilder;
use Generated\Shared\DataBuilder\ShipmentMethodsCollectionBuilder;
use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderCollectionTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class CheckoutRestApiBusinessTester extends Actor
{
    use _generated\CheckoutRestApiBusinessTesterActions;

    protected const CART_UUID = 'test-cart-uuid';

    protected const CUSTOMER = [
        'customerReference' => 'DE-test-customer-reference',
        'idCustomer' => 666,
        'defaultShippingAddress' => '8',
        'defaultBillingAddress' => '9',
    ];

    protected const GUEST_CUSTOMER = [
        'customerReference' => 'anonymous:test-guest-customer-reference',
    ];

    protected const ADDRESS_1 = [
        'id' => 'dd1ddd99-1315-5eae-aaaf-9e74f78a33d52',
        'uuid' => 'dd1ddd99-1315-5eae-aaaf-9e74f78a33d52',
        'salutation' => 'Mr',
        'firstName' => 'spencor',
        'lastName' => 'hopkin',
        'address1' => 'West road',
        'address2' => '212',
        'address3' => "",
        'zipCode' => '61000',
        'city' => 'Berlin',
        'iso2Code' => 'DE',
        'company' => 'Spryker',
        'phone' => '+380666666666',
    ];

    protected const ADDRESS_2 = [
        'id' => 'b3840c0d-07e3-58b3-87e7-dabec8170324',
        'uuid' => 'b3840c0d-07e3-58b3-87e7-dabec8170324',
        'salutation' => 'Mr',
        'firstName' => 'Spencor',
        'lastName' => 'Hopkin',
        'address1' => 'Julie-Wolfthorn-Straße',
        'address2' => '1',
        'address3' => 'new address',
        'zipCode' => '10115',
        'city' => 'Berlin',
        'iso2Code' => 'DE',
        'company' => 'spryker',
        'phone' => '+49 (30) 2084 98350',
    ];

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function prepareFullRestCheckoutRequestAttributesTransfer(): RestCheckoutRequestAttributesTransfer
    {
        /** @var \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer */
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesBuilder(['idCart' => static::CART_UUID]))
            ->withBillingAddress(static::ADDRESS_1)
            ->withShippingAddress(static::ADDRESS_2)
            ->withCustomer(static::CUSTOMER)
            ->build();

        return $restCheckoutRequestAttributesTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function prepareFullRestCheckoutRequestAttributesTransferForGuest(): RestCheckoutRequestAttributesTransfer
    {
        /** @var \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer */
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesBuilder(['idCart' => static::CART_UUID]))
            ->withBillingAddress(static::ADDRESS_1)
            ->withShippingAddress(static::ADDRESS_2)
            ->withCustomer(static::GUEST_CUSTOMER)
            ->build();

        return $restCheckoutRequestAttributesTransfer;
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\PaymentTransfer
     */
    public function getPaymentTransfer(): AbstractTransfer
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

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function createCustomerResponseTransfer(): AbstractTransfer
    {
        /** @var \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer */
        $customerResponseTransfer = (new CustomerResponseBuilder(['hasCustomer' => true, 'isSuccess' => true]))
            ->withCustomerTransfer(
                (new CustomerBuilder(static::CUSTOMER))
            )
            ->build();

        $addressesTransfer = (new AddressesTransfer())
            ->addAddress((new AddressTransfer())->fromArray(static::ADDRESS_1, true));

        $customerResponseTransfer->getCustomerTransfer()->setAddresses($addressesTransfer);

        return $customerResponseTransfer;
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function createPaymentProviderCollectionTransfer(): AbstractTransfer
    {
        $paymentProviderCollectionTransfer = new PaymentProviderCollectionTransfer();

        $paymentMethodData1 = [
            'methodName' => 'invoice',
            'paymentSelections' => 'dummyPaymentInvoice',
        ];
        $paymentMethodData2 = [
            'methodName' => 'credit card',
            'paymentSelections' => 'dummyPaymentCreditCard',
        ];
        $paymentProviderTransfer = (new PaymentProviderTransfer())
            ->setName('DummyPayment');

        $paymentProviderTransfer->addPaymentMethod(
            (new PaymentMethodTransfer())
                ->setMethodName($paymentMethodData1['methodName'])
        );

        $paymentProviderTransfer->addPaymentMethod(
            (new PaymentMethodTransfer())
                ->setMethodName($paymentMethodData2['methodName'])
        );

        $paymentProviderCollectionTransfer->addPaymentProvider($paymentProviderTransfer);

        return $paymentProviderCollectionTransfer;
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function createCheckoutResponseTransfer(): AbstractTransfer
    {
        return (new CheckoutResponseBuilder(['isSuccess' => true]))->withSaveOrder()->build();
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuoteResponseTransferWithFailingValidation(): AbstractTransfer
    {
        return (new QuoteResponseBuilder(['isSuccessful' => false]))
            ->withQuoteTransfer($this->createQuoteTransfer()->toArray())
            ->build();
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuoteResponseTransfer(): AbstractTransfer
    {
        return (new QuoteResponseBuilder(['isSuccessful' => true]))
            ->withQuoteTransfer($this->createQuoteTransfer()->toArray())
            ->build();
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuoteResponseForGuestTransfer(): AbstractTransfer
    {
        return (new QuoteResponseBuilder(['isSuccessful' => true]))
            ->withQuoteTransfer($this->createQuoteTransferForGuest()->toArray())
            ->build();
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function createShipmentMethodsTransfer(): AbstractTransfer
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
     * @return \Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer
     */
    public function createShipmentMethodsCollectionTransfer(): ShipmentMethodsCollectionTransfer
    {
        $shipmentMethodData = [
            'carrierName' => 'Spryker Dummy Shipment',
            'idShipmentMethod' => '1',
            'name' => 'Standard',
            'storeCurrencyPrice' => '490',
        ];

        return (new ShipmentMethodsCollectionBuilder())
            ->withShipmentMethods(
                (new ShipmentMethodsBuilder())
                    ->withMethod(
                        (new ShipmentMethodBuilder($shipmentMethodData))
                    )
            )
            ->build();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(): QuoteTransfer
    {
        $product = $this->haveProduct();
        $this->haveProductInStock([StockProductTransfer::SKU => $product->getSku()]);

        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = (new QuoteBuilder(['uuid' => static::CART_UUID]))
            ->withItem([ItemTransfer::SKU => $product->getSku(), ItemTransfer::UNIT_PRICE => 1])
            ->withStore([StoreTransfer::NAME => 'DE'])
            ->withCustomer(static::CUSTOMER)
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
    public function createQuoteTransferForGuest(): QuoteTransfer
    {
        $product = $this->haveProduct();
        $this->haveProductInStock([StockProductTransfer::SKU => $product->getSku()]);

        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = (new QuoteBuilder(['uuid' => static::CART_UUID]))
            ->withItem([ItemTransfer::SKU => $product->getSku(), ItemTransfer::UNIT_PRICE => 1])
            ->withStore([StoreTransfer::NAME => 'DE'])
            ->withCustomer(static::GUEST_CUSTOMER)
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
    public function createQuote(): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = (new QuoteBuilder(['uuid' => static::CART_UUID]))
            ->withCustomer(['isGuest' => false] + static::CUSTOMER)
            ->withBillingAddress(static::ADDRESS_1)
            ->withShippingAddress(static::ADDRESS_2)
            ->withShipment()
            ->withPayment()
            ->build();

        return $quoteTransfer->setCustomerReference(static::CUSTOMER['customerReference']);
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function createAvailableMethodsCollectionTransfer(): PaymentMethodsTransfer
    {
        /** @var \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer */
        $paymentMethodsTransfer = (new PaymentMethodsBuilder())
            ->withMethod(['methodName' => 'invoice'])
            ->withAnotherMethod(['methodName' => 'credit card'])
            ->build();

        return $paymentMethodsTransfer;
    }
}

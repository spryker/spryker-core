<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomersRestApi;

use Codeception\Actor;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\RestCheckoutRequestAttributesBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestAddressTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;

/**
 * Inherited Methods
 *
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
class CustomersRestApiBusinessTester extends Actor
{
    use _generated\CustomersRestApiBusinessTesterActions;

    public const CUSTOMER = [
        'customerReference' => 'DE-test-customer-reference',
        'idCustomer' => 666,
    ];

    protected const GUEST_CUSTOMER = [
        'customerReference' => 'anonymous:test-guest-customer-reference',
    ];

    public const ADDRESS_1 = [
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

    public const ADDRESS_2 = [
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
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesBuilder())
            ->withBillingAddress(static::ADDRESS_1)
            ->withShippingAddress(static::ADDRESS_2)
            ->withCustomer(static::CUSTOMER)
            ->build();

        return $restCheckoutRequestAttributesTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function prepareFullGuestRestCheckoutRequestAttributesTransfer(): RestCheckoutRequestAttributesTransfer
    {
        /** @var \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer */
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesBuilder())
            ->withBillingAddress(static::ADDRESS_1)
            ->withShippingAddress(static::ADDRESS_2)
            ->withCustomer(static::GUEST_CUSTOMER)
            ->build();

        return $restCheckoutRequestAttributesTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function prepareOnlyBillingRestCheckoutRequestAttributesTransfer(): RestCheckoutRequestAttributesTransfer
    {
        /** @var \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer */
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesBuilder())
            ->withBillingAddress(static::ADDRESS_1)
            ->withCustomer(static::CUSTOMER)
            ->build();

        return $restCheckoutRequestAttributesTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function prepareOnlyBillingGuestRestCheckoutRequestAttributesTransfer(): RestCheckoutRequestAttributesTransfer
    {
        /** @var \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer */
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesBuilder())
            ->withBillingAddress(static::ADDRESS_1)
            ->withCustomer(static::GUEST_CUSTOMER)
            ->build();

        return $restCheckoutRequestAttributesTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function prepareOnlyShippingRestCheckoutRequestAttributesTransfer(): RestCheckoutRequestAttributesTransfer
    {
        /** @var \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer */
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesBuilder())
            ->withShippingAddress(static::ADDRESS_2)
            ->withCustomer(static::CUSTOMER)
            ->build();

        return $restCheckoutRequestAttributesTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function prepareOnlyShippingGuestRestCheckoutRequestAttributesTransfer(): RestCheckoutRequestAttributesTransfer
    {
        /** @var \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer */
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesBuilder())
            ->withShippingAddress(static::ADDRESS_2)
            ->withCustomer(static::GUEST_CUSTOMER)
            ->build();

        return $restCheckoutRequestAttributesTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function prepareCustomerRestCheckoutRequestAttributesTransfer(): RestCheckoutRequestAttributesTransfer
    {
        /** @var \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer */
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesBuilder())
            ->withCustomer(static::CUSTOMER)
            ->build();

        return $restCheckoutRequestAttributesTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function prepareGuestCustomerRestCheckoutRequestAttributesTransfer(): RestCheckoutRequestAttributesTransfer
    {
        /** @var \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer */
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesBuilder())
            ->withCustomer(static::GUEST_CUSTOMER)
            ->build();

        return $restCheckoutRequestAttributesTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function prepareNoCustomerRestCheckoutRequestAttributesTransfer(): RestCheckoutRequestAttributesTransfer
    {
        /** @var \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer */
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesBuilder())->build();

        return $restCheckoutRequestAttributesTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function prepareQuoteTransfer(): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = (new QuoteBuilder())->build();

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $actualQuote
     *
     * @return void
     */
    public function assertBillingAddressMapping(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer, QuoteTransfer $actualQuote): void
    {
        $expectedBillingAddress = $restCheckoutRequestAttributesTransfer->getBillingAddress();
        $actualBillingAddressTransfer = $actualQuote->getBillingAddress();

        $this->assertAddress($expectedBillingAddress, $actualBillingAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $actualQuote
     *
     * @return void
     */
    public function assertShippingAddressMapping(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer, QuoteTransfer $actualQuote): void
    {
        $expectedShippingAddress = $restCheckoutRequestAttributesTransfer->getShippingAddress();
        $actualShippingAddressTransfer = $actualQuote->getShippingAddress();

        $this->assertAddress($expectedShippingAddress, $actualShippingAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $actualQuote
     *
     * @return void
     */
    public function assertShippingAddressMappingWithItemLevelShippingAddresses(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer, QuoteTransfer $actualQuote): void
    {
        $expectedShippingAddress = $restCheckoutRequestAttributesTransfer->getShippingAddress();

        foreach ($actualQuote->getItems() as $itemTransfer) {
            $actualShippingAddressTransfer = $itemTransfer->getShipment()->getShippingAddress();
            $this->assertAddress($expectedShippingAddress, $actualShippingAddressTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\RestAddressTransfer $expectedShippingAddress
     * @param \Generated\Shared\Transfer\AddressTransfer $actualShippingAddressTransfer
     *
     * @return void
     */
    protected function assertAddress(
        RestAddressTransfer $expectedShippingAddress,
        AddressTransfer $actualShippingAddressTransfer
    ): void {
        $this->assertEquals($expectedShippingAddress->getSalutation(), $actualShippingAddressTransfer->getSalutation());
        $this->assertEquals($expectedShippingAddress->getFirstName(), $actualShippingAddressTransfer->getFirstName());
        $this->assertEquals($expectedShippingAddress->getLastName(), $actualShippingAddressTransfer->getLastName());
        $this->assertEquals($expectedShippingAddress->getAddress1(), $actualShippingAddressTransfer->getAddress1());
        $this->assertEquals($expectedShippingAddress->getAddress2(), $actualShippingAddressTransfer->getAddress2());
        $this->assertEquals($expectedShippingAddress->getCity(), $actualShippingAddressTransfer->getCity());
        $this->assertEquals($expectedShippingAddress->getIso2Code(), $actualShippingAddressTransfer->getIso2Code());
        $this->assertEquals($expectedShippingAddress->getZipCode(), $actualShippingAddressTransfer->getZipCode());
        $this->assertEquals($expectedShippingAddress->getCompany(), $actualShippingAddressTransfer->getCompany());
    }
}

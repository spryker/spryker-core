<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business\Checkout;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Checkout
 * @group CustomerOrderSaverTest
 * Add your own group annotations below this line
 */
class CustomerOrderSaverTest extends Test
{
    /**
     * @var \SprykerTest\Zed\Customer\CustomerBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testSaveCustomerOrderWhenQuoteShippingAddressIsDefineAndAddressSavingNotSkippedAndBillingAddressNotSame(): void
    {
        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::BILLING_SAME_AS_SHIPPING => false,
            QuoteTransfer::IS_ADDRESS_SAVING_SKIPPED => false,
        ]))
            ->withCustomer()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withItem()
            ->withTotals()
            ->build();

        $saveOrderTransfer = new SaveOrderTransfer();

        $this->tester->getFacade()->saveOrderCustomer($quoteTransfer, $saveOrderTransfer);

        $customerAddressQuery = SpyCustomerAddressQuery::create()->findByFkCustomer($quoteTransfer->getCustomer()->getIdCustomer());

        $this->assertEquals(2, count($customerAddressQuery), $quoteTransfer->getCustomer()->getIdCustomer());
    }

    /**
     * @return void
     */
    public function testSaveCustomerOrderWhenQuoteShippingAddressIsDefineAndAddressSavingNotSkippedAndBillingIsSame(): void
    {
        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::BILLING_SAME_AS_SHIPPING => true,
            QuoteTransfer::IS_ADDRESS_SAVING_SKIPPED => false,
        ]))
            ->withCustomer()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withItem()
            ->withTotals()
            ->build();

        $saveOrderTransfer = new SaveOrderTransfer();

        $this->tester->getFacade()->saveOrderCustomer($quoteTransfer, $saveOrderTransfer);

        $customerAddressQuery = SpyCustomerAddressQuery::create()->findByFkCustomer($quoteTransfer->getCustomer()->getIdCustomer());

        $this->assertEquals(1, count($customerAddressQuery), $quoteTransfer->getCustomer()->getIdCustomer());
    }

    /**
     * @return void
     */
    public function testSaveCustomerOrderWhenItemShippingAddressIsDefinedAndAddressSavingNotSkipped(): void
    {
        $itemTransfer = (new ItemBuilder())
            ->withShipment(
                (new ShipmentBuilder())
                    ->withShippingAddress()
            )
            ->build();

        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::IS_ADDRESS_SAVING_SKIPPED => false,
        ]))
            ->withCustomer()
            ->withBillingAddress()
            ->withItem($itemTransfer->toArray())
            ->withTotals()
            ->build();

        $saveOrderTransfer = new SaveOrderTransfer();

        $this->tester->getFacade()->saveOrderCustomer($quoteTransfer, $saveOrderTransfer);

        $customerAddressQuery = SpyCustomerAddressQuery::create()->findByFkCustomer($quoteTransfer->getCustomer()->getIdCustomer());

        $this->assertEquals(2, count($customerAddressQuery), $quoteTransfer->getCustomer()->getIdCustomer());
    }

    /**
     * @return void
     */
    public function testSaveCustomerOrderWhenQuoteHasMultipleShippingAddressesAndAddressSavingNotSkipped(): void
    {
        $itemTransfer1 = (new ItemBuilder())
            ->withShipment(
                (new ShipmentBuilder())
                    ->withShippingAddress()
            )
            ->build();

        $itemTransfer2 = (new ItemBuilder())
            ->withAnotherShipment(
                (new ShipmentBuilder())
                    ->withAnotherShippingAddress()
            )
            ->build();

        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::IS_ADDRESS_SAVING_SKIPPED => false,
        ]))
            ->withCustomer()
            ->withBillingAddress()
            ->withItem($itemTransfer1->toArray())
            ->withAnotherItem($itemTransfer2->toArray())
            ->withTotals()
            ->build();

        $saveOrderTransfer = new SaveOrderTransfer();

        $this->tester->getFacade()->saveOrderCustomer($quoteTransfer, $saveOrderTransfer);

        $customerAddressQuery = SpyCustomerAddressQuery::create()->findByFkCustomer($quoteTransfer->getCustomer()->getIdCustomer());

        $this->assertEquals(3, count($customerAddressQuery), $quoteTransfer->getCustomer()->getIdCustomer());
    }

    /**
     * @return void
     */
    public function testSaveCustomerOrderWhenQuoteHasMultipleSameShippingAddressesAndAddressSavingNotSkipped(): void
    {
        $addressTransfer = (new AddressBuilder())->build();
        $itemTransfer1 = (new ItemBuilder())
            ->withShipment(
                (new ShipmentBuilder())
                    ->withShippingAddress($addressTransfer->toArray())
            )
            ->build();

        $itemTransfer2 = (new ItemBuilder())
            ->withAnotherShipment(
                (new ShipmentBuilder())
                    ->withAnotherShippingAddress()
            )
            ->build();

        $itemTransfer3 = (new ItemBuilder())
            ->withAnotherShipment(
                (new ShipmentBuilder())
                    ->withShippingAddress($addressTransfer->toArray())
            )
            ->build();

        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::IS_ADDRESS_SAVING_SKIPPED => false,
        ]))
            ->withCustomer()
            ->withBillingAddress($addressTransfer->toArray())
            ->withItem($itemTransfer1->toArray())
            ->withAnotherItem($itemTransfer2->toArray())
            ->withAnotherItem($itemTransfer3->toArray())
            ->withTotals()
            ->build();

        $saveOrderTransfer = new SaveOrderTransfer();

        $this->tester->getFacade()->saveOrderCustomer($quoteTransfer, $saveOrderTransfer);

        $customerAddressQuery = SpyCustomerAddressQuery::create()->findByFkCustomer($quoteTransfer->getCustomer()->getIdCustomer());

        $this->assertEquals(2, count($customerAddressQuery), $quoteTransfer->getCustomer()->getIdCustomer());
    }

    /**
     * @return void
     */
    public function testSaveCustomerOrderWhenQuoteHasMultipleShippingAddressesAndAddressSavingIsSkipped(): void
    {
        $itemTransfer = (new ItemBuilder())
            ->withShipment(
                (new ShipmentBuilder())
                    ->withShippingAddress()
            )
            ->build();

        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::IS_ADDRESS_SAVING_SKIPPED => true,
        ]))
            ->withCustomer()
            ->withBillingAddress()
            ->withItem($itemTransfer->toArray())
            ->withTotals()
            ->build();

        $saveOrderTransfer = new SaveOrderTransfer();

        $this->tester->getFacade()->saveOrderCustomer($quoteTransfer, $saveOrderTransfer);

        $customerAddressQuery = SpyCustomerAddressQuery::create()->findByFkCustomer($quoteTransfer->getCustomer()->getIdCustomer());

        $this->assertNull($customerAddressQuery);
    }

    /**
     * @return void
     */
    public function testSaveCustomerOrderWhenQuoteHasMultipleSameShippingAddressesAndAddressSavingIsSkipped(): void
    {
        $addressTransfer = (new AddressBuilder())->build();
        $itemTransfer1 = (new ItemBuilder())
            ->withShipment(
                (new ShipmentBuilder())
                    ->withShippingAddress()
            )
            ->build();

        $itemTransfer2 = (new ItemBuilder())
            ->withAnotherShipment(
                (new ShipmentBuilder())
                    ->withAnotherShippingAddress($addressTransfer->toArray())
            )
            ->build();

        $itemTransfer3 = (new ItemBuilder())
            ->withAnotherShipment(
                (new ShipmentBuilder())
                    ->withAnotherShippingAddress($addressTransfer->toArray())
            )
            ->build();

        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::IS_ADDRESS_SAVING_SKIPPED => false,
        ]))
            ->withCustomer()
            ->withBillingAddress()
            ->withItem($itemTransfer1->toArray())
            ->withAnotherItem($itemTransfer2->toArray())
            ->withAnotherItem($itemTransfer3->toArray())
            ->withTotals()
            ->build();

        $saveOrderTransfer = new SaveOrderTransfer();

        $this->tester->getFacade()->saveOrderCustomer($quoteTransfer, $saveOrderTransfer);

        $customerAddressQuery = SpyCustomerAddressQuery::create()->findByFkCustomer($quoteTransfer->getCustomer()->getIdCustomer());

        $this->assertNull($customerAddressQuery);
    }
}

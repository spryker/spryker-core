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
 * @group CustomerOrderSaverWithItemLevelShippingAddressTest
 * Add your own group annotations below this line
 */
class CustomerOrderSaverWithItemLevelShippingAddressTest extends Test
{
    /**
     * @var \SprykerTest\Zed\Customer\CustomerBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider saveCustomerOrderWithItemLevelShippingAddressAndAddressSavingIsNotSkipped
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param int|null $expectedResult
     *
     * @return void
     */
    public function testSaveCustomerOrderWithItemLevelShippingAddressAndAddressSavingIsNotSkipped(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer,
        ?int $expectedResult
    ): void {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $quoteTransfer->setIsAddressSavingSkipped(false);
        $quoteTransfer->setCustomer($customerTransfer)
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setIdCustomer($customerTransfer->getIdCustomer());

        // Act
        $this->tester->getFacade()->saveOrderCustomer($quoteTransfer, $saveOrderTransfer);

        // Assert
        $idCustomer = $quoteTransfer->getCustomer()->getIdCustomer();
        $customerAddressEntity = SpyCustomerAddressQuery::create()->findByFkCustomer($idCustomer);

        $this->assertCount(
            $expectedResult,
            $customerAddressEntity,
            sprintf(
                'Should be saved %d adresses. Saved %d.',
                $expectedResult,
                $customerAddressEntity->count()
            )
        );
    }

    /**
     * @dataProvider saveCustomerOrderWithQuoteLevelShippingAddressAndAddressSavingIsSkipped
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function testSaveCustomerOrderWithQuoteLevelShippingAddressAndAddressSavingIsSkipped(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): void {
        // Arrange
        $quoteTransfer->setIsAddressSavingSkipped(true);
        foreach ($quoteTransfer->getItems() as $item) {
            $item->getShipment()->getShippingAddress()->setIsAddressSavingSkipped(true);
        }

        // Act
        $this->tester->getFacade()->saveOrderCustomer($quoteTransfer, $saveOrderTransfer);

        // Assert
        $idCustomer = $quoteTransfer->getCustomer()->getIdCustomer();
        $customerAddressEntity = SpyCustomerAddressQuery::create()->findByFkCustomer($idCustomer);

        $this->assertCount(
            1,
            $customerAddressEntity,
            sprintf(
                'Any shipping or billing addresses should not be saved. Saved %d',
                $customerAddressEntity->count()
            )
        );
    }

    /**
     * @return array
     */
    public function saveCustomerOrderWithItemLevelShippingAddressAndAddressSavingIsNotSkipped(): array
    {
        return [
            'quote has one item with shipping address, billind is defined; expected 2 addresses saved' => $this->getQuoteWithOneItemAndBillingAddresAndItemLevelShippingAddress(),
            'quote has two items with shipping addresses, billind is defined; expected 3 addresses saved' => $this->getQuoteWithTwoItemBillingAddresAndItemLevelShippingAddress(),
            'quote has three items with two different shipping addresses, billind is the same as one shipping; expected 2 addresses saved' => $this->getQuoteWithThreeItemsAndTwoDifferentAddresses(),
        ];
    }

    /**
     * @return array
     */
    public function saveCustomerOrderWithQuoteLevelShippingAddressAndAddressSavingIsSkipped(): array
    {
        return [
            'quote has one item with shipping address, billind is defined; expected no shipping or billing addresses saved' => $this->getQuoteWithOneItemAndBillingAddresAndItemLevelShippingAddress(),
            'quote has three items with two different shipping addresses; expected no shipping or billing addresses saved' => $this->getQuoteWithThreeItemsAndTwoDifferentAddresses(),
        ];
    }

    /**
     * @return array
     */
    public function getQuoteWithOneItemAndBillingAddresAndItemLevelShippingAddress(): array
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withCustomer()
            ->withBillingAddress()
            ->withItem(
                (new ItemBuilder())
                    ->withShipment(
                        (new ShipmentBuilder())
                            ->withAnotherShippingAddress()
                    )
            )
            ->withTotals()
            ->build();

        return [$quoteTransfer, new SaveOrderTransfer(), 2];
    }

    /**
     * @return array
     */
    public function getQuoteWithTwoItemBillingAddresAndItemLevelShippingAddress(): array
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withCustomer()
            ->withBillingAddress()
            ->withItem(
                (new ItemBuilder())
                ->withShipment(
                    (new ShipmentBuilder())
                        ->withAnotherShippingAddress()
                )
            )
            ->withAnotherItem(
                (new ItemBuilder())
                    ->withAnotherShipment(
                        (new ShipmentBuilder())
                            ->withAnotherShippingAddress()
                    )
            )
            ->withTotals()
            ->build();

        return [$quoteTransfer, new SaveOrderTransfer(), 3];
    }

    /**
     * @return array
     */
    public function getQuoteWithThreeItemsAndTwoDifferentAddresses(): array
    {
        $addressTransfer1 = (new AddressBuilder())->build();
        $addressTransfer2 = (new AddressBuilder())->build();

        $quoteTransfer = (new QuoteBuilder())
            ->withCustomer()
            ->withBillingAddress($addressTransfer1->toArray())
            ->withItem(
                (new ItemBuilder())
                    ->withShipment(
                        (new ShipmentBuilder())
                            ->withShippingAddress($addressTransfer1->toArray())
                    )
            )
            ->withAnotherItem(
                (new ItemBuilder())
                    ->withAnotherShipment(
                        (new ShipmentBuilder())
                            ->withAnotherShippingAddress($addressTransfer2->toArray())
                    )
            )
            ->withAnotherItem(
                (new ItemBuilder())
                    ->withAnotherShipment(
                        (new ShipmentBuilder())
                            ->withAnotherShippingAddress($addressTransfer2->toArray())
                    )
            )
            ->withTotals()
            ->build();

        return [$quoteTransfer, new SaveOrderTransfer(), 2];
    }
}

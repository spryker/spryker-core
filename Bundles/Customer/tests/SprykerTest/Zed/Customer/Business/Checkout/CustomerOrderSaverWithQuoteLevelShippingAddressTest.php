<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business\Checkout;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\QuoteBuilder;
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
 * @group CustomerOrderSaverWithQuoteLevelShippingAddressTest
 * Add your own group annotations below this line
 */
class CustomerOrderSaverWithQuoteLevelShippingAddressTest extends Test
{
    /**
     * @var \SprykerTest\Zed\Customer\CustomerBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider saveCustomerOrderWithQuoteLevelShippingAddressAndAddressSavingIsNotSkipped
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param int|null $expectedResult
     *
     * @return void
     */
    public function testSaveCustomerOrderWithQuoteLevelShippingAddressAndAddressSavingIsNotSkipped(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer,
        ?int $expectedResult
    ): void {
        // Arrange
        $quoteTransfer->setIsAddressSavingSkipped(false);

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
     * @param int|null $expectedResult
     *
     * @return void
     */
    public function testSaveCustomerOrderWithQuoteLevelShippingAddressAndAddressSavingIsSkipped(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer,
        ?int $expectedResult
    ): void {
        // Arrange
        $quoteTransfer->setIsAddressSavingSkipped(true);

        // Act
        $this->tester->getFacade()->saveOrderCustomer($quoteTransfer, $saveOrderTransfer);

        // Assert
        $idCustomer = $quoteTransfer->getCustomer()->getIdCustomer();
        $customerAddressEntity = SpyCustomerAddressQuery::create()->findByFkCustomer($idCustomer);

        $this->assertEmpty(
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
    public function saveCustomerOrderWithQuoteLevelShippingAddressAndAddressSavingIsNotSkipped(): array
    {
        return [
            'quote shipping address is defined, billind is not same shipping; expected shipping and billing addresses saved' => $this->getQuoteWithBillingAddressIsNotSameAsShippingAddress(),
            'quote shipping address is defined, billing is same shipping; expected shipping and billing addresses saved' => $this->getQuoteWithBillingAddressIsSameAsShippingAddress(),
        ];
    }

    /**
     * @return array
     */
    public function saveCustomerOrderWithQuoteLevelShippingAddressAndAddressSavingIsSkipped(): array
    {
        return [
            'quote shipping address is defined, billind is not same shipping; expected no shipping or billing addresses saved' => $this->getQuoteWithBillingAddressIsNotSameAsShippingAddress(),
            'quote shipping address is defined, billing is same shipping; expected no shipping or billing addresses saved' => $this->getQuoteWithBillingAddressIsSameAsShippingAddress(),
        ];
    }

    /**
     * @return array
     */
    public function getQuoteWithBillingAddressIsSameAsShippingAddress(): array
    {
        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::BILLING_SAME_AS_SHIPPING => true,
        ]))
            ->withCustomer()
            ->withShippingAddress()
            ->withAnotherBillingAddress()
            ->withItem()
            ->withTotals()
            ->build();

        return [$quoteTransfer, new SaveOrderTransfer(), 1];
    }

    /**
     * @return array
     */
    public function getQuoteWithBillingAddressIsNotSameAsShippingAddress(): array
    {
        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::BILLING_SAME_AS_SHIPPING => false,
        ]))
            ->withCustomer()
            ->withShippingAddress()
            ->withAnotherBillingAddress()
            ->withItem()
            ->withTotals()
            ->build();

        return [$quoteTransfer, new SaveOrderTransfer(), 2];
    }
}

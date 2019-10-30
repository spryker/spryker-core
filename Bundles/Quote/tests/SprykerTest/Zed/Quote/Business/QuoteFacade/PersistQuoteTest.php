<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Quote\Business\QuoteFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\DiscountBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\TotalsBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Quote\Business\QuoteFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Quote
 * @group Business
 * @group QuoteFacade
 * @group PersistQuoteTest
 * Add your own group annotations below this line
 */
class PersistQuoteTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Quote\QuoteBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Quote\Business\QuoteFacade
     */
    protected $quoteFacade;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->quoteFacade = new QuoteFacade();
    }

    /**
     * @dataProvider persistQuoteDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $expectedQuoteTransfer
     *
     * @return void
     */
    public function testPersistQuote(QuoteTransfer $quoteTransfer, QuoteTransfer $expectedQuoteTransfer)
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $currencyTransfer = $this->tester->getLocator()
            ->currency()
            ->facade()
            ->getCurrent();
        $storeTransfer = $this->tester->getLocator()
            ->store()
            ->facade()
            ->getStoreByName('DE');

        $quoteTransfer
            ->setCustomer($customerTransfer)
            ->setCurrency($currencyTransfer)
            ->setStore($storeTransfer);

        $expectedStoreTransfer = new StoreTransfer();
        $expectedStoreTransfer
            ->setIdStore($storeTransfer->getIdStore())
            ->setName($storeTransfer->getName());

        $expectedQuoteTransfer
            ->setCurrency($currencyTransfer)
            ->setStore($expectedStoreTransfer)
            ->setCustomerReference($customerTransfer->getCustomerReference());

        // Act
        $this->quoteFacade->createQuote($quoteTransfer);

        // Assert
        $actualQuoteTransfer = $this->quoteFacade->findQuoteByCustomer($customerTransfer)->getQuoteTransfer();

        $this->assertNotNull($actualQuoteTransfer->getIdQuote());
        $this->assertEquals($actualQuoteTransfer->getCurrency(), $expectedQuoteTransfer->getCurrency());
        $this->assertEquals($actualQuoteTransfer->getStore(), $expectedQuoteTransfer->getStore());
        $this->assertEquals($actualQuoteTransfer->getCustomerReference(), $expectedQuoteTransfer->getCustomerReference());
    }

    /**
     * @return array
     */
    public function persistQuoteDataProvider()
    {
        return [
            'persist empty quote' => $this->providePersistEmptyQuoteData(),
            'persist filtered quote' => $this->providePersistFilteredQuoteData(),
        ];
    }

    /**
     * @return array
     */
    protected function providePersistEmptyQuoteData()
    {
        $quoteTransfer = (new QuoteBuilder())->build();
        $expectedQuoteTransfer = clone $quoteTransfer;

        return [$quoteTransfer, $expectedQuoteTransfer];
    }

    /**
     * @return array
     */
    protected function providePersistFilteredQuoteData()
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = (new QuoteBuilder())->build();
        $expectedQuoteTransfer = clone $quoteTransfer;

        $itemTransfer1 = (new ItemBuilder())->build();
        $itemTransfer2 = (new ItemBuilder())->build();

        $totalsTransfer = (new TotalsBuilder())
            ->seed([
                TotalsTransfer::GRAND_TOTAL => 100,
            ])
            ->build();

        $quoteTransfer
            ->addItem($itemTransfer1)
            ->addItem($itemTransfer2)
            ->setTotals($totalsTransfer)
            ->setPriceMode('foo')
            ->setShippingAddress((new AddressBuilder())->build())
            ->setBillingSameAsShipping(true)
            ->addCartRuleDiscount((new DiscountBuilder())->build());

        $expectedQuoteTransfer
            ->addItem($itemTransfer1)
            ->addItem($itemTransfer2)
            ->setTotals($totalsTransfer)
            ->setPriceMode('foo');

        return [$quoteTransfer, $expectedQuoteTransfer];
    }
}

<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Quote\Business\QuoteFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\DiscountBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Quote\Business\QuoteFacade;

/**
 * Auto-generated group annotations
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

        $expectedQuoteTransfer
            ->setCurrency($currencyTransfer)
            ->setStore($storeTransfer);

        // Act
        $this->quoteFacade->persistQuote($quoteTransfer);

        // Assert
        $actualQuoteTransfer = $this->quoteFacade->findQuoteByCustomer($customerTransfer);

        $this->assertSame($expectedQuoteTransfer->modifiedToArray(), $actualQuoteTransfer->modifiedToArray());
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
        $quoteTransfer = new QuoteTransfer();
        $expectedQuoteTransfer = new QuoteTransfer();

        return [$quoteTransfer, $expectedQuoteTransfer];
    }

    /**
     * @return array
     */
    protected function providePersistFilteredQuoteData()
    {
        $itemTransfer1 = (new ItemBuilder())->build();
        $itemTransfer2 = (new ItemBuilder())->build();

        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer->setGrandTotal(100);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer
            ->addItem($itemTransfer1)
            ->addItem($itemTransfer2)
            ->setTotals($totalsTransfer)
            ->setPriceMode('foo')
            ->setShippingAddress((new AddressBuilder)->build())
            ->setBillingSameAsShipping(true)
            ->addCartRuleDiscount((new DiscountBuilder())->build());

        $expectedQuoteTransfer = new QuoteTransfer();
        $expectedQuoteTransfer
            ->addItem($itemTransfer1)
            ->addItem($itemTransfer2)
            ->setTotals($totalsTransfer)
            ->setPriceMode('foo');

        return [$quoteTransfer, $expectedQuoteTransfer];
    }
}

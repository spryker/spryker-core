<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MultiCart\Communication\Plugin\Quote;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\MultiCart\Communication\Plugin\Quote\DefaultQuoteCollectionFilterPlugin;
use SprykerTest\Zed\MultiCart\MultiCartCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MultiCart
 * @group Communication
 * @group Plugin
 * @group Quote
 * @group DefaultQuoteCollectionFilterPluginTest
 * Add your own group annotations below this line
 */
class DefaultQuoteCollectionFilterPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_QUOTE_NAME = 'Cart DE--1';

    /**
     * @var \SprykerTest\Zed\MultiCart\MultiCartCommunicationTester
     */
    protected MultiCartCommunicationTester $tester;

    /**
     * @return void
     */
    public function testShouldKeepOnlyDefaultQuote(): void
    {
        // Arrange
        $quoteCollectionTransfer = (new QuoteCollectionTransfer())
            ->addQuote((new QuoteTransfer())->setIsDefault(false)->setName(''))
            ->addQuote((new QuoteTransfer())->setIsDefault(true)->setName(static::DEFAULT_QUOTE_NAME))
            ->addQuote((new QuoteTransfer())->setIsDefault(false)->setName(''));

        $quoteCriteriaFilterTransfer = (new QuoteCriteriaFilterTransfer())
            ->setIsDefault(true);

        // Act
        $quoteCollectionTransfer = (new DefaultQuoteCollectionFilterPlugin())
            ->filter($quoteCollectionTransfer, $quoteCriteriaFilterTransfer);

        // Assert
        $this->assertCount(1, $quoteCollectionTransfer->getQuotes());
        $this->assertSame(static::DEFAULT_QUOTE_NAME, $quoteCollectionTransfer->getQuotes()->getIterator()->current()->getName());
    }

    /**
     * @return void
     */
    public function testShouldNotApplyIsDefaultFilter(): void
    {
        // Arrange
        $quoteCollectionTransfer = (new QuoteCollectionTransfer())
            ->addQuote((new QuoteTransfer())->setIsDefault(false)->setName(''))
            ->addQuote((new QuoteTransfer())->setIsDefault(true)->setName(static::DEFAULT_QUOTE_NAME))
            ->addQuote((new QuoteTransfer())->setIsDefault(false)->setName(''));

        $quoteCriteriaFilterTransfer = (new QuoteCriteriaFilterTransfer())
            ->setIsDefault(false);

        // Act
        $quoteCollectionTransfer = (new DefaultQuoteCollectionFilterPlugin())
            ->filter($quoteCollectionTransfer, $quoteCriteriaFilterTransfer);

        // Assert
        $this->assertCount(3, $quoteCollectionTransfer->getQuotes());
    }

    /**
     * @return void
     */
    public function testShouldReturnEmptyCollectionIfQuoteCollectionDoesNotContainDefaultQuote(): void
    {
        // Arrange
        $quoteCollectionTransfer = (new QuoteCollectionTransfer())
            ->addQuote((new QuoteTransfer())->setIsDefault(false)->setName(''))
            ->addQuote((new QuoteTransfer())->setIsDefault(false)->setName(''))
            ->addQuote((new QuoteTransfer())->setIsDefault(false)->setName(''));

        $quoteCriteriaFilterTransfer = (new QuoteCriteriaFilterTransfer())
            ->setIsDefault(true);

        // Act
        $quoteCollectionTransfer = (new DefaultQuoteCollectionFilterPlugin())
            ->filter($quoteCollectionTransfer, $quoteCriteriaFilterTransfer);

        // Assert
        $this->assertCount(0, $quoteCollectionTransfer->getQuotes());
    }
}

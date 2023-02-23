<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\MultiCart;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\MultiCart\Dependency\Client\MultiCartToSessionClientInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group MultiCart
 * @group MultiCartClientTest
 * Add your own group annotations below this line
 */
class MultiCartClientTest extends Unit
{
    /**
     * @uses \Spryker\Client\MultiCart\MultiCartDependencyProvider::CLIENT_SESSION
     *
     * @var string
     */
    protected const CLIENT_SESSION = 'CLIENT_SESSION';

    /**
     * @var string
     */
    protected const QUOTE_NAME = 'quote-name';

    /**
     * @var string
     */
    protected const ITEM_GROUP_KEY = 'item-group-key';

    /**
     * @var \SprykerTest\Client\MultiCart\MultiCartClientTester
     */
    protected MultiCartClientTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(static::CLIENT_SESSION, $this->getSessionClientMock());
    }

    /**
     * @return void
     */
    public function testSetQuoteCollectionShouldNotFilterQuoteDataWhenAllowedFieldsConfigIsEmpty(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('getQuoteFieldsAllowedForCustomerQuoteCollectionInSession', []);

        $quoteTransfer = (new QuoteBuilder([QuoteTransfer::NAME => static::QUOTE_NAME]))
            ->withItem([ItemTransfer::GROUP_KEY => static::ITEM_GROUP_KEY])
            ->build();
        $quoteData = $quoteTransfer->toArray();
        $quoteCollectionTransfer = (new QuoteCollectionTransfer())->addQuote($quoteTransfer);

        // Act
        $this->tester->getClient()->setQuoteCollection($quoteCollectionTransfer);

        // Assert
        $this->assertSame($quoteData, $quoteCollectionTransfer->getQuotes()->getIterator()->current()->toArray());
    }

    /**
     * @return void
     */
    public function testSetQuoteCollectionShouldFilterOutAllQuoteDataExceptAllowedFieldsWhenConfiguredFieldIsString(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('getQuoteFieldsAllowedForCustomerQuoteCollectionInSession', [
            QuoteTransfer::NAME,
        ]);
        $quoteTransfer = (new QuoteBuilder([QuoteTransfer::NAME => static::QUOTE_NAME]))
            ->withItem([ItemTransfer::GROUP_KEY => static::ITEM_GROUP_KEY])
            ->build();
        $quoteCollectionTransfer = (new QuoteCollectionTransfer())->addQuote($quoteTransfer);

        // Act
        $this->tester->getClient()->setQuoteCollection($quoteCollectionTransfer);

        // Assert
        $quoteData = $quoteCollectionTransfer->getQuotes()->getIterator()->current()->toArray(true, true);
        $this->assertCount(1, array_filter($quoteData));
        $this->assertArrayHasKey(QuoteTransfer::NAME, $quoteData);
        $this->assertSame(static::QUOTE_NAME, $quoteData[QuoteTransfer::NAME]);
    }

    /**
     * @return void
     */
    public function testSetQuoteCollectionShouldFilterOutAllQuoteDataExceptAllowedFieldsWhenConfiguredFieldIsArray(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('getQuoteFieldsAllowedForCustomerQuoteCollectionInSession', [
            QuoteTransfer::ITEMS => [
                ItemTransfer::GROUP_KEY,
            ],
        ]);
        $quoteTransfer = (new QuoteBuilder([QuoteTransfer::NAME => static::QUOTE_NAME]))
            ->withItem([ItemTransfer::GROUP_KEY => static::ITEM_GROUP_KEY])
            ->build();
        $quoteCollectionTransfer = (new QuoteCollectionTransfer())->addQuote($quoteTransfer);

        // Act
        $this->tester->getClient()->setQuoteCollection($quoteCollectionTransfer);

        // Assert
        $quoteData = $quoteCollectionTransfer->getQuotes()->getIterator()->current()->toArray(true, true);
        $this->assertCount(1, array_filter($quoteData));
        $this->assertArrayHasKey(QuoteTransfer::ITEMS, $quoteData);
        $this->assertIsArray($quoteData[QuoteTransfer::ITEMS]);
        $this->assertCount(1, $quoteData[QuoteTransfer::ITEMS]);

        $itemData = array_filter($quoteData[QuoteTransfer::ITEMS][0]);
        $this->assertCount(1, $quoteData[QuoteTransfer::ITEMS]);
        $this->assertArrayHasKey(ItemTransfer::GROUP_KEY, $itemData);
        $this->assertSame(static::ITEM_GROUP_KEY, $itemData[ItemTransfer::GROUP_KEY]);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\MultiCart\Dependency\Client\MultiCartToSessionClientInterface
     */
    protected function getSessionClientMock(): MultiCartToSessionClientInterface
    {
        return $this->getMockBuilder(MultiCartToSessionClientInterface::class)->getMock();
    }
}

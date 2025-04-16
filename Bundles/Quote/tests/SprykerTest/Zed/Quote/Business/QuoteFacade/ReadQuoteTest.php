<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Quote\Business\QuoteFacade;

use Codeception\Stub;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Quote\QuoteDependencyProvider;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteCollectionFilterPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Quote
 * @group Business
 * @group QuoteFacade
 * @group ReadQuoteTest
 * Add your own group annotations below this line
 */
class ReadQuoteTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Quote\QuoteBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testReadQuoteFromDatabaseByCustomer(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);
        $storeTransfer = $quoteTransfer->getStore();

        // Act
        $quoteResponseTransfer = $this->tester->getFacade()->findQuoteByCustomerAndStore($customerTransfer, $storeTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful(), 'Quote response transfer should have ben successful.');
        $this->assertSame($quoteTransfer->getIdQuote(), $quoteResponseTransfer->getQuoteTransfer()->getIdQuote(), 'Quote response should have expected quote ID from database.');
    }

    /**
     * @return void
     */
    public function testShouldGetQuoteCollectionByCriteriaFilter(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);
        $storeTransfer = $quoteTransfer->getStore();

        $quoteCriteriaFilterTransfer = (new QuoteCriteriaFilterTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setIdStore($storeTransfer->getIdStore());

        // Act
        $quoteResponseTransfer = $this->tester->getFacade()->getQuoteCollection($quoteCriteriaFilterTransfer);

        // Assert
        $this->assertSame(
            $quoteTransfer->getIdQuote(),
            $quoteResponseTransfer->getQuotes()->getIterator()->current()->getIdQuote(),
        );
    }

    /**
     * @return void
     */
    public function testShouldExecuteStackOfQuoteCollectionFilterPlugins(): void
    {
        // Assert
        $this->tester->setDependency(
            QuoteDependencyProvider::PLUGINS_QUOTE_COLLECTION_FILTER,
            [$this->getQuoteCollectionFilterPluginMock()],
        );

        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);
        $storeTransfer = $quoteTransfer->getStore();

        $quoteCriteriaFilterTransfer = (new QuoteCriteriaFilterTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setIdStore($storeTransfer->getIdStore());

        // Act
        $this->tester->getFacade()->getQuoteCollection($quoteCriteriaFilterTransfer);
    }

    /**
     * @return \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteCollectionFilterPluginInterface
     */
    protected function getQuoteCollectionFilterPluginMock(): QuoteCollectionFilterPluginInterface
    {
        $quoteCollectionFilterPluginMock = Stub::makeEmpty(QuoteCollectionFilterPluginInterface::class);
        $quoteCollectionFilterPluginMock->expects($this->once())
            ->method('filter')
            ->willReturnCallback(function (QuoteCollectionTransfer $quoteCollectionTransfer) {
                return $quoteCollectionTransfer;
            });

        return $quoteCollectionFilterPluginMock;
    }
}

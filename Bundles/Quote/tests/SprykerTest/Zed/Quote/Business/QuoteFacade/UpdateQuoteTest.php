<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Quote\Business\QuoteFacade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Quote
 * @group Business
 * @group QuoteFacade
 * @group UpdateQuoteTest
 * Add your own group annotations below this line
 */
class UpdateQuoteTest extends Unit
{
    protected const ERROR_MESSAGE_STORE_DATA_IS_MISSING = 'quote.validation.error.store_is_missing';
    protected const WRONG_STORE_NAME = 'WRONGSTORENAME';

    /**
     * @var \SprykerTest\Zed\Quote\QuoteBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testUpdateQuotePersistChangesToDatabase()
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);
        $storeTransfer = $quoteTransfer->getStore();

        $itemTransfer = (new ItemBuilder())->build();
        $itemCollection = new ArrayObject([
            $itemTransfer,
        ]);

        $quoteTransfer->setItems($itemCollection);

        // Act
        /** @var \Spryker\Zed\Quote\Business\QuoteFacade $quoteFacade */
        $quoteFacade = $this->tester->getFacade();
        $persistQuoteResponseTransfer = $quoteFacade->updateQuote($quoteTransfer);

        // Assert
        $this->assertTrue($persistQuoteResponseTransfer->getIsSuccessful(), 'Persist quote response transfer should have ben successful.');
        $findQuoteResponseTransfer = $quoteFacade->findQuoteByCustomerAndStore($customerTransfer, $storeTransfer);
        $this->assertTrue($findQuoteResponseTransfer->getIsSuccessful(), 'Find quote response transfer should have ben successful.');
        $this->assertEquals($itemCollection, $findQuoteResponseTransfer->getQuoteTransfer()->getItems(), 'Quote response should have expected data from database.');
    }

    /**
     * @return void
     */
    public function testUpdateQuoteWithValidationEmptyStore(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);
        $quoteTransfer->setStore(null);

        // Act
        $this->validateStoreInQuote($quoteTransfer, static::ERROR_MESSAGE_STORE_DATA_IS_MISSING);
    }

    /**
     * @return void
     */
    public function testUpdateQuoteWithValidationEmptyStoreName()
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);
        $storeTransfer = new StoreTransfer();

        $quoteTransfer
            ->setStore($storeTransfer);

        // Act
        $this->validateStoreInQuote($quoteTransfer, static::ERROR_MESSAGE_STORE_DATA_IS_MISSING);
    }

    /**
     * @return void
     */
    public function testUpdateQuoteWithValidationWrongStoreName()
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);
        $storeTransfer = (new StoreTransfer())
            ->setName(static::WRONG_STORE_NAME);

        $quoteTransfer
            ->setStore($storeTransfer);

        $this->validateStoreInQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $errorMessage
     *
     * @return void
     */
    protected function validateStoreInQuote(QuoteTransfer $quoteTransfer, string $errorMessage = ''): void
    {
        // Act
        /** @var \Spryker\Zed\Quote\Business\QuoteFacade $quoteFacade */
        $quoteFacade = $this->tester->getFacade();
        $quoteResponseTransfer = $quoteFacade->updateQuote($quoteTransfer);

        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());

        if ($errorMessage) {
            $errors = array_map(function ($errorMessageTransfer) {
                return $errorMessageTransfer->getValue();
            }, (array)$quoteResponseTransfer->getErrors());

            $this->assertContains($errorMessage, $errors);
        }
    }
}

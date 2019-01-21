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
use Spryker\Zed\Quote\Business\Validator\QuoteValidator;

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
    public function testValidationEmptyStoreInQuote()
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);
        $quoteTransfer->setStore(null);

        // Act
        /** @var \Spryker\Zed\Quote\Business\QuoteFacade $quoteFacade */
        $quoteFacade = $this->tester->getFacade();
        $quoteResponseTransfer = $quoteFacade->updateQuote($quoteTransfer);

        $errors = array_map(function ($messageTransfer) {
            return $messageTransfer->getValue();
        }, (array)$quoteResponseTransfer->getErrors());

        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertContains(QuoteValidator::MESSAGE_STORE_DATA_IS_MISSING, $errors);
    }

    /**
     * @return void
     */
    public function testValidationEmptyStoreNameInQuote()
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
        /** @var \Spryker\Zed\Quote\Business\QuoteFacade $quoteFacade */
        $quoteFacade = $this->tester->getFacade();
        $quoteResponseTransfer = $quoteFacade->updateQuote($quoteTransfer);

        $errors = array_map(function ($messageTransfer) {
            return $messageTransfer->getValue();
        }, (array)$quoteResponseTransfer->getErrors());

        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertContains(QuoteValidator::MESSAGE_STORE_DATA_IS_MISSING, $errors);
    }

    /**
     * @return void
     */
    public function testValidationWrongStoreNameInQuote()
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);
        $storeTransfer = (new StoreTransfer())
            ->setName('WRONGNAME');

        $quoteTransfer
            ->setStore($storeTransfer);

        // Act
        /** @var \Spryker\Zed\Quote\Business\QuoteFacade $quoteFacade */
        $quoteFacade = $this->tester->getFacade();
        $quoteResponseTransfer = $quoteFacade->updateQuote($quoteTransfer);

        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
    }
}

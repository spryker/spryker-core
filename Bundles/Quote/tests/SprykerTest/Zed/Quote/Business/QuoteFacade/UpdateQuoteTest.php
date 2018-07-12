<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Quote\Business\QuoteFacade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\QuoteTransfer;

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
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);

        $itemTransfer = (new ItemBuilder())->build();
        $itemCollection = new ArrayObject([
            $itemTransfer,
        ]);

        $quoteTransfer->setItems($itemCollection);

        // Act
        $persistQuoteResponseTransfer = $this->tester->getFacade()->updateQuote($quoteTransfer);

        // Assert
        $this->assertTrue($persistQuoteResponseTransfer->getIsSuccessful(), 'Persist quote response transfer should have ben successful.');
        $findQuoteResponseTransfer = $this->tester->getFacade()->findQuoteByCustomer($customerTransfer);
        $this->assertTrue($findQuoteResponseTransfer->getIsSuccessful(), 'Find quote response transfer should have ben successful.');
        $this->assertEquals($itemCollection, $findQuoteResponseTransfer->getQuoteTransfer()->getItems(), 'Quote response should have expected data from database.');
    }
}

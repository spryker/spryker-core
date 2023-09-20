<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteRequest\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerTest\Zed\QuoteRequest\QuoteRequestBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group QuoteRequest
 * @group Business
 * @group Facade
 * @group SanitizeQuoteRequestTest
 * Add your own group annotations below this line
 */
class SanitizeQuoteRequestTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\QuoteRequest\QuoteRequestBusinessTester
     */
    protected QuoteRequestBusinessTester $tester;

    /**
     * @return void
     */
    public function testSanitizeQuoteRequestSanitizeQuoteRequestInQuote(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInDraftStatus(
            $this->tester->createCompanyUser($customerTransfer),
            $this->tester->createQuoteWithCustomer($customerTransfer),
        );
        $quoteTransfer = (new QuoteTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference())
            ->setQuoteRequestVersionReference($quoteRequestTransfer->getLatestVersion()->getVersionReference());

        // Act
        $updatedQuoteTransfer = $this->tester->getFacade()->sanitizeQuoteRequest($quoteTransfer);

        // Assert
        $this->assertNull($updatedQuoteTransfer->getQuoteRequestReference());
        $this->assertNull($updatedQuoteTransfer->getQuoteRequestVersionReference());
    }
}

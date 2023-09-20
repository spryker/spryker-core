<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteRequest\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use SprykerTest\Zed\QuoteRequest\QuoteRequestBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group QuoteRequest
 * @group Business
 * @group Facade
 * @group GetQuoteRequestTest
 * Add your own group annotations below this line
 */
class GetQuoteRequestTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\QuoteRequest\QuoteRequestBusinessTester
     */
    protected QuoteRequestBusinessTester $tester;

    /**
     * @return void
     */
    public function testFindQuoteRequestRetrievesQuoteRequestByReference(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInDraftStatus(
            $this->tester->createCompanyUser($customerTransfer),
            $this->tester->createQuoteWithCustomer($customerTransfer),
        );

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference());

        // Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()->getQuoteRequest($quoteRequestFilterTransfer);
        $storedQuoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertSame($quoteRequestTransfer->getIdQuoteRequest(), $storedQuoteRequestTransfer->getIdQuoteRequest());
    }

    /**
     * @return void
     */
    public function testFindQuoteRequestRetrievesEmptyResultByFakeReference(): void
    {
        // Arrange
        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference(QuoteRequestBusinessTester::FAKE_QUOTE_REQUEST_REFERENCE);

        // Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()->getQuoteRequest($quoteRequestFilterTransfer);

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testFindQuoteRequestThrowsExceptionWithEmptyQuoteRequestReference(): void
    {
        // Arrange
        $quoteRequestFilterTransfer = new QuoteRequestFilterTransfer();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->getQuoteRequest($quoteRequestFilterTransfer);
    }
}

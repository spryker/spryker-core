<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteRequest\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\QuoteRequest\QuoteRequestConfig as SharedQuoteRequestConfig;
use SprykerTest\Zed\QuoteRequest\QuoteRequestBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group QuoteRequest
 * @group Business
 * @group Facade
 * @group ReviseQuoteRequestTest
 * Add your own group annotations below this line
 */
class ReviseQuoteRequestTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\QuoteRequest\QuoteRequestBusinessTester
     */
    protected QuoteRequestBusinessTester $tester;

    /**
     * @var \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected CompanyUserTransfer $companyUserTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected QuoteTransfer $quoteTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $customerTransfer = $this->tester->haveCustomer();

        $this->companyUserTransfer = $this->tester->createCompanyUser($customerTransfer);
        $this->quoteTransfer = $this->tester->createQuoteWithCustomer($customerTransfer);
    }

    /**
     * @return void
     */
    public function testReviseQuoteRequestCreatesNewQuoteRequestVersionFromReadyStatus(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInReadyStatus($this->companyUserTransfer, $this->quoteTransfer);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->reviseQuoteRequest($this->tester->createFilterTransfer($quoteRequestTransfer));
        $storedQuoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertSame(
            SharedQuoteRequestConfig::STATUS_DRAFT,
            $quoteRequestResponseTransfer->getQuoteRequest()->getStatus(),
        );
        $this->assertNotEquals(
            $quoteRequestTransfer->getLatestVersion()->getVersionReference(),
            $storedQuoteRequestTransfer->getLatestVersion()->getVersionReference(),
        );
    }

    /**
     * @return void
     */
    public function testReviseQuoteRequestThrowsErrorMessageQuoteRequestNotFound(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestTransfer())
            ->setCompanyUser($this->companyUserTransfer)
            ->setQuoteRequestReference(QuoteRequestBusinessTester::FAKE_QUOTE_REQUEST_REFERENCE);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->reviseQuoteRequest($this->tester->createFilterTransfer($quoteRequestTransfer));

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertSame(
            QuoteRequestBusinessTester::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testReviseQuoteRequestThrowsErrorMessageWhenQuoteRequestStatusNotRevisable(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInDraftStatus($this->companyUserTransfer, $this->quoteTransfer);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->reviseQuoteRequest($this->tester->createFilterTransfer($quoteRequestTransfer));

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertSame(
            QuoteRequestBusinessTester::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testReviseQuoteRequestClearsSourcePrices(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInInProgressStatus($this->companyUserTransfer, $this->quoteTransfer);
        $quoteTransfer = clone $this->quoteTransfer;

        $quoteTransfer->getItems()->offsetGet(0)
            ->setSourceUnitGrossPrice(1)
            ->setSourceUnitNetPrice(2);

        $quoteRequestTransfer->getLatestVersion()->setQuote($quoteTransfer);
        $quoteRequestTransfer = $this->tester
            ->getFacade()
            ->updateQuoteRequestForCompanyUser($quoteRequestTransfer)
            ->getQuoteRequest();

        $quoteRequestFilterTransfer = $this->tester->createFilterTransfer($quoteRequestTransfer);
        $this->tester->getFacade()->sendQuoteRequestToCompanyUser($quoteRequestFilterTransfer);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->reviseQuoteRequest($quoteRequestFilterTransfer);

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());

        $itemTransfer = $quoteRequestResponseTransfer->getQuoteRequest()
            ->getLatestVersion()
            ->getQuote()
            ->getItems()
            ->offsetGet(0);

        $this->assertNull($itemTransfer->getSourceUnitGrossPrice());
        $this->assertNull($itemTransfer->getSourceUnitNetPrice());
    }
}

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
 * @group CancelQuoteRequestForCompanyUserTest
 * Add your own group annotations below this line
 */
class CancelQuoteRequestForCompanyUserTest extends Unit
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
    public function testCancelQuoteRequestForCompanyUserChangesQuoteRequestStatusToCanceled(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInDraftStatus($this->companyUserTransfer, $this->quoteTransfer);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->cancelQuoteRequestForCompanyUser($this->tester->createFilterTransfer($quoteRequestTransfer));

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertSame(
            SharedQuoteRequestConfig::STATUS_CANCELED,
            $quoteRequestResponseTransfer->getQuoteRequest()->getStatus(),
        );
    }

    /**
     * @return void
     */
    public function testCancelQuoteRequestForCompanyUserThrowsErrorMessageQuoteRequestNotFound(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestTransfer())
            ->setCompanyUser($this->companyUserTransfer)
            ->setQuoteRequestReference(QuoteRequestBusinessTester::FAKE_QUOTE_REQUEST_REFERENCE);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->cancelQuoteRequestForCompanyUser($this->tester->createFilterTransfer($quoteRequestTransfer));

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
    public function testCancelQuoteRequestForCompanyUserThrowsErrorMessageWhenQuoteRequestStatusIsClosed(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInDraftStatus($this->companyUserTransfer, $this->quoteTransfer);
        $quoteTransfer = (new QuoteTransfer())
            ->setQuoteRequestVersionReference($quoteRequestTransfer->getLatestVersion()->getVersionReference());

        $this->tester->getFacade()->closeQuoteRequest($quoteTransfer);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->cancelQuoteRequestForCompanyUser($this->tester->createFilterTransfer($quoteRequestTransfer));

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertSame(
            QuoteRequestBusinessTester::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue(),
        );
    }
}

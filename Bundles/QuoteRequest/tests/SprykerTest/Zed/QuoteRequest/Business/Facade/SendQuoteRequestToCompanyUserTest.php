<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteRequest\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\CompanyUserTransfer;
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
 * @group SendQuoteRequestToCompanyUserTest
 * Add your own group annotations below this line
 */
class SendQuoteRequestToCompanyUserTest extends Unit
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
    public function testSendQuoteRequestToCompanyUserWhenQuoteRequestValidUntilNotSet(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInInProgressStatus(
            $this->companyUserTransfer,
            $this->quoteTransfer,
            null,
            true,
        );

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->sendQuoteRequestToCompanyUser($this->tester->createFilterTransfer($quoteRequestTransfer));
        $storedQuoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertTrue($storedQuoteRequestTransfer->getIsLatestVersionVisible());
        $this->assertSame(SharedQuoteRequestConfig::STATUS_READY, $storedQuoteRequestTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testSendQuoteRequestToCompanyUserWhenQuoteRequestValidUntilCorrect(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInInProgressStatus(
            $this->companyUserTransfer,
            $this->quoteTransfer,
            (new DateTime('+1 hour'))->format('Y-m-d H:i:s'),
            null,
        );

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->sendQuoteRequestToCompanyUser($this->tester->createFilterTransfer($quoteRequestTransfer));
        $storedQuoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertTrue($storedQuoteRequestTransfer->getIsLatestVersionVisible());
        $this->assertSame(SharedQuoteRequestConfig::STATUS_READY, $storedQuoteRequestTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testSendQuoteRequestToCompanyUserThrowsErrorMessageQuoteRequestNotFound(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInDraftStatus($this->companyUserTransfer, $this->quoteTransfer)
            ->setQuoteRequestReference(QuoteRequestBusinessTester::FAKE_QUOTE_REQUEST_REFERENCE);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->sendQuoteRequestToCompanyUser($this->tester->createFilterTransfer($quoteRequestTransfer));

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
    public function testSendQuoteRequestToCompanyUserThrowsErrorMessageWhenQuoteRequestStatusNotInProgress(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInWaitingStatus($this->companyUserTransfer, $this->quoteTransfer);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->sendQuoteRequestToCompanyUser($this->tester->createFilterTransfer($quoteRequestTransfer));

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
    public function testSendQuoteRequestToCompanyUserThrowsErrorMessageWhenEmptyQuoteItems(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInInProgressStatus($this->companyUserTransfer, $this->quoteTransfer);

        $this->quoteTransfer->setItems(new ArrayObject());
        $quoteRequestTransfer->getLatestVersion()->setQuote($this->quoteTransfer);

        $quoteRequestTransfer = $this->tester->getFacade()
            ->updateQuoteRequestForCompanyUser($quoteRequestTransfer)
            ->getQuoteRequest();

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->sendQuoteRequestToCompanyUser($this->tester->createFilterTransfer($quoteRequestTransfer));

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertSame(
            QuoteRequestBusinessTester::GLOSSARY_KEY_QUOTE_REQUEST_EMPTY_QUOTE_ITEMS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue(),
        );
    }
}

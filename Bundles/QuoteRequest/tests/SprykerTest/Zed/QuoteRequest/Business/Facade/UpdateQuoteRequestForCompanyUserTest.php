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
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
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
 * @group UpdateQuoteRequestForCompanyUserTest
 * Add your own group annotations below this line
 */
class UpdateQuoteRequestForCompanyUserTest extends Unit
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
    public function testUpdateQuoteRequestForCompanyUserUpdatesQuoteRequest(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInInProgressStatus($this->companyUserTransfer, $this->quoteTransfer);

        $quoteRequestTransfer->setIsLatestVersionVisible(false)
            ->setValidUntil((new DateTime('+1 hour'))->format('Y-m-d H:i:s'))
            ->getLatestVersion()
            ->setMetadata(['test' => 'test'])
            ->getQuote()
            ->setItems(new ArrayObject());

        // Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()->updateQuoteRequestForCompanyUser($quoteRequestTransfer);
        $storedQuoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertSame($quoteRequestTransfer->getValidUntil(), $storedQuoteRequestTransfer->getValidUntil());
        $this->assertSame($quoteRequestTransfer->getIsLatestVersionVisible(), $storedQuoteRequestTransfer->getIsLatestVersionVisible());
        $this->assertEquals(
            $quoteRequestTransfer->getLatestVersion()->getQuote()->getItems(),
            $storedQuoteRequestTransfer->getLatestVersion()->getQuote()->getItems(),
        );
        $this->assertEquals(
            $quoteRequestTransfer->getLatestVersion()->getMetadata(),
            $storedQuoteRequestTransfer->getLatestVersion()->getMetadata(),
        );
    }

    /**
     * @return void
     */
    public function testUpdateQuoteRequestForCompanyUserThrowsExceptionWithEmptyQuoteRequestReference(): void
    {
        // Arrange
        $quoteRequestTransfer = new QuoteRequestTransfer();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateQuoteRequestForCompanyUser($quoteRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateQuoteRequestForCompanyUserThrowsExceptionWithEmptyCompanyUser(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInInProgressStatus($this->companyUserTransfer, $this->quoteTransfer)
            ->setCompanyUser(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateQuoteRequestForCompanyUser($quoteRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateQuoteRequestForCompanyUserThrowsExceptionWithEmptyIdCompanyUser(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInInProgressStatus($this->companyUserTransfer, $this->quoteTransfer);
        $quoteRequestTransfer->getCompanyUser()->setIdCompanyUser(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateQuoteRequestForCompanyUser($quoteRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateQuoteRequestForCompanyUserThrowsErrorMessageQuoteRequestNotFound(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInInProgressStatus($this->companyUserTransfer, $this->quoteTransfer)
            ->setQuoteRequestReference(QuoteRequestBusinessTester::FAKE_QUOTE_REQUEST_REFERENCE);

        // Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()->updateQuoteRequestForCompanyUser($quoteRequestTransfer);

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
    public function testUpdateQuoteRequestForCompanyUserThrowsErrorMessageQuoteRequestStatusNotEditable(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInDraftStatus($this->companyUserTransfer, $this->quoteTransfer);

        // Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()->updateQuoteRequestForCompanyUser($quoteRequestTransfer);

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertSame(
            QuoteRequestBusinessTester::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue(),
        );
    }
}

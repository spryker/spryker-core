<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteRequest\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
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
 * @group UpdateQuoteRequestTest
 * Add your own group annotations below this line
 */
class UpdateQuoteRequestTest extends Unit
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
    public function testUpdateQuoteRequestUpdatesQuoteRequestVersionMetadataAndQuote(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInDraftStatus($this->companyUserTransfer, $this->quoteTransfer);

        $quoteRequestTransfer->getLatestVersion()
            ->setMetadata(['test' => 'test'])
            ->getQuote()
            ->setItems(new ArrayObject());

        // Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()->updateQuoteRequest($quoteRequestTransfer);
        $storedQuoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
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
    public function testUpdateQuoteRequestSanitizesSourcePrices(): void
    {
        // Arrange
        $sourcePrice = 322;
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInDraftStatus($this->companyUserTransfer, $this->quoteTransfer);
        $quoteRequestTransfer->getLatestVersion()
            ->getQuote()
            ->setShipment($this->tester->createShipmentWithSourcePrice($sourcePrice));
        $quoteRequestTransfer->getLatestVersion()
            ->getQuote()
            ->getItems()
            ->getIterator()
            ->current()
            ->setShipment($this->tester->createShipmentWithSourcePrice($sourcePrice))
            ->setSourceUnitGrossPrice(322);

        // Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()->updateQuoteRequest($quoteRequestTransfer);
        $quoteTransfer = $quoteRequestResponseTransfer->getQuoteRequest()->getLatestVersion()->getQuote();
        $itemTransfer = $quoteTransfer->getItems()->getIterator()->current();

        // Assert
        $this->assertEmpty($quoteTransfer->getShipment()->getMethod()->getSourcePrice());
        $this->assertEmpty($itemTransfer->getShipment()->getMethod()->getSourcePrice());
        $this->assertEmpty($itemTransfer->getSourceUnitGrossPrice());
    }

    /**
     * @return void
     */
    public function testUpdateQuoteRequestThrowsExceptionWithEmptyQuoteRequestReference(): void
    {
        // Arrange
        $quoteRequestTransfer = new QuoteRequestTransfer();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateQuoteRequestThrowsExceptionWithEmptyCompanyUser(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInDraftStatus($this->companyUserTransfer, $this->quoteTransfer)
            ->setCompanyUser(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateQuoteRequestThrowsExceptionWithEmptyIdCompanyUser(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInDraftStatus($this->companyUserTransfer, $this->quoteTransfer);
        $quoteRequestTransfer->getCompanyUser()->setIdCompanyUser(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateQuoteRequestThrowsErrorMessageQuoteRequestNotFound(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInDraftStatus($this->companyUserTransfer, $this->quoteTransfer)
            ->setQuoteRequestReference(QuoteRequestBusinessTester::FAKE_QUOTE_REQUEST_REFERENCE);

        // Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()->updateQuoteRequest($quoteRequestTransfer);

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
    public function testUpdateQuoteRequestThrowsErrorMessageQuoteRequestStatusNotEditable(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInWaitingStatus($this->companyUserTransfer, $this->quoteTransfer);

        // Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()->updateQuoteRequest($quoteRequestTransfer);

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertSame(
            QuoteRequestBusinessTester::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue(),
        );
    }
}

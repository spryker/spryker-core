<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteRequest\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteRequestBuilder;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
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
 * @group CreateQuoteRequestTest
 * Add your own group annotations below this line
 */
class CreateQuoteRequestTest extends Unit
{
    /**
     * @see \Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestWriter::GLOSSARY_KEY_QUOTE_REQUEST_CART_IS_EMPTY
     *
     * @var string
     */
    protected const GLOSSARY_KEY_QUOTE_REQUEST_CART_IS_EMPTY = 'quote_request.validation.error.cart_is_empty';

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
    public function testCreateQuoteRequestCreatesQuoteRequest(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder())->build()
            ->setCompanyUser($this->companyUserTransfer)
            ->setLatestVersion($this->tester->createQuoteRequestVersion($this->quoteTransfer));

        // Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()->createQuoteRequest($quoteRequestTransfer);
        $storedQuoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals($quoteRequestTransfer->getCompanyUser(), $storedQuoteRequestTransfer->getCompanyUser());
        $this->assertSame(SharedQuoteRequestConfig::STATUS_DRAFT, $storedQuoteRequestTransfer->getStatus());
        $this->assertEquals(
            $quoteRequestTransfer->getLatestVersion()->getQuote(),
            $storedQuoteRequestTransfer->getLatestVersion()->getQuote(),
        );
    }

    /**
     * @return void
     */
    public function testCreateQuoteRequestThrowsExceptionWithEmptyCompanyUser(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder())->build()
            ->setLatestVersion($this->tester->createQuoteRequestVersion($this->quoteTransfer));

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreateQuoteRequestThrowsExceptionWithEmptyIdCompanyUser(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder())->build()
            ->setCompanyUser(new CompanyUserTransfer())
            ->setLatestVersion($this->tester->createQuoteRequestVersion($this->quoteTransfer));

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreateQuoteRequestThrowsExceptionWithEmptyLatestVersion(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder())->build()
            ->setCompanyUser($this->companyUserTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreateQuoteRequestThrowsExceptionWithEmptyQuoteItems(): void
    {
        // Arrange
        $this->quoteTransfer->setItems(new ArrayObject());

        $quoteRequestTransfer = (new QuoteRequestBuilder())->build()
            ->setCompanyUser($this->companyUserTransfer)
            ->setLatestVersion($this->tester->createQuoteRequestVersion($this->quoteTransfer));

        // Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()->createQuoteRequest($quoteRequestTransfer);

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_QUOTE_REQUEST_CART_IS_EMPTY,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue(),
        );
    }
}

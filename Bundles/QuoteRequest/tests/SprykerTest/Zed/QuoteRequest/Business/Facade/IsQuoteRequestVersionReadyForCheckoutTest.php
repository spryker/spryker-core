<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteRequest\Business\Facade;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
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
 * @group IsQuoteRequestVersionReadyForCheckoutTest
 * Add your own group annotations below this line
 */
class IsQuoteRequestVersionReadyForCheckoutTest extends Unit
{
    /**
     * @see \Spryker\Zed\QuoteRequest\Business\Validator\QuoteRequestTimeValidator::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_VERSION_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_WRONG_QUOTE_REQUEST_VERSION_NOT_FOUND = 'quote_request.checkout.validation.error.version_not_found';

    /**
     * @see \Spryker\Zed\QuoteRequest\Business\Validator\QuoteRequestTimeValidator::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_STATUS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_WRONG_QUOTE_REQUEST_STATUS = 'quote_request.checkout.validation.error.wrong_status';

    /**
     * @see \Spryker\Zed\QuoteRequest\Business\Validator\QuoteRequestTimeValidator::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_VALID_UNTIL
     *
     * @var string
     */
    protected const GLOSSARY_KEY_WRONG_QUOTE_REQUEST_VALID_UNTIL = 'quote_request.checkout.validation.error.wrong_valid_until';

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
    public function testIsQuoteRequestVersionReadyForCheckoutChecksQuoteRequestInQuoteWhenValidUntilNotSet(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInReadyStatus($this->companyUserTransfer, $this->quoteTransfer);

        $this->quoteTransfer->setQuoteRequestVersionReference(
            $quoteRequestTransfer->getLatestVersion()->getVersionReference(),
        );

        // Act
        $isValid = $this->tester
            ->getFacade()
            ->isQuoteRequestVersionReadyForCheckout($this->quoteTransfer, new CheckoutResponseTransfer());

        // Assert
        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testIsQuoteRequestVersionReadyForCheckoutChecksQuoteRequestInQuoteWhenValidUntilSet(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInReadyStatus(
            $this->companyUserTransfer,
            $this->quoteTransfer,
            (new DateTime('+1 hour'))->format('Y-m-d H:i:s'),
        );

        $this->quoteTransfer->setQuoteRequestVersionReference(
            $quoteRequestTransfer->getLatestVersion()->getVersionReference(),
        );

        // Act
        $isValid = $this->tester
            ->getFacade()
            ->isQuoteRequestVersionReadyForCheckout($this->quoteTransfer, new CheckoutResponseTransfer());

        // Assert
        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testIsQuoteRequestVersionReadyForCheckoutSkipCheckWhenQuoteRequestVersionReferenceNotProvided(): void
    {
        // Arrange
        $this->quoteTransfer->setQuoteRequestVersionReference(null);

        // Act
        $isValid = $this->tester
            ->getFacade()
            ->isQuoteRequestVersionReadyForCheckout($this->quoteTransfer, new CheckoutResponseTransfer());

        // Assert
        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testIsQuoteRequestVersionReadyForCheckoutThrowsErrorMessageQuoteRequestVersionNotFound(): void
    {
        // Arrange
        $this->quoteTransfer->setQuoteRequestVersionReference(QuoteRequestBusinessTester::FAKE_QUOTE_REQUEST_VERSION_REFERENCE);
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isValid = $this->tester
            ->getFacade()
            ->isQuoteRequestVersionReadyForCheckout($this->quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($isValid);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertSame(
            static::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_VERSION_NOT_FOUND,
            $checkoutResponseTransfer->getErrors()[0]->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testIsQuoteRequestVersionReadyForCheckoutThrowsErrorMessageQuoteRequestNotReady(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInDraftStatus($this->companyUserTransfer, $this->quoteTransfer);
        $this->quoteTransfer->setQuoteRequestVersionReference(
            $quoteRequestTransfer->getLatestVersion()->getVersionReference(),
        );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isValid = $this->tester
            ->getFacade()
            ->isQuoteRequestVersionReadyForCheckout($this->quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($isValid);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertSame(
            static::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_STATUS,
            $checkoutResponseTransfer->getErrors()[0]->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testIsQuoteRequestVersionReadyForCheckoutThrowsErrorMessageQuoteRequestWrongValidUntil(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInReadyStatus(
            $this->companyUserTransfer,
            $this->quoteTransfer,
            (new DateTime('+1 second'))->format('Y-m-d H:i:s'),
        );
        $this->quoteTransfer->setQuoteRequestVersionReference(
            $quoteRequestTransfer->getLatestVersion()->getVersionReference(),
        );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        sleep(2);
        $isValid = $this->tester
            ->getFacade()
            ->isQuoteRequestVersionReadyForCheckout($this->quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($isValid);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertSame(
            static::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_VALID_UNTIL,
            $checkoutResponseTransfer->getErrors()[0]->getMessage(),
        );
    }
}

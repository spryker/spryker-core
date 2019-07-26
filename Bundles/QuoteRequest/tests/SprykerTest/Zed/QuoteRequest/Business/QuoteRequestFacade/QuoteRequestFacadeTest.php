<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteRequest\Business\QuoteRequestFacade;

use ArrayObject;
use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\QuoteRequestBuilder;
use Generated\Shared\DataBuilder\QuoteRequestFilterBuilder;
use Generated\Shared\DataBuilder\QuoteRequestVersionFilterBuilder;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Shared\QuoteRequest\QuoteRequestConfig as SharedQuoteRequestConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group QuoteRequest
 * @group Business
 * @group QuoteRequestFacade
 * @group Facade
 * @group QuoteRequestFacadeTest
 * Add your own group annotations below this line
 */
class QuoteRequestFacadeTest extends Unit
{
    protected const FAKE_QUOTE_REQUEST_REFERENCE = 'FAKE_QUOTE_REQUEST_REFERENCE';
    protected const FAKE_QUOTE_REQUEST_VERSION_REFERENCE = 'FAKE_QUOTE_REQUEST_VERSION_REFERENCE';

    /**
     * @see \Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestWriter::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS
     */
    protected const GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS = 'quote_request.validation.error.not_exists';

    /**
     * @see \Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestWriter::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS
     */
    protected const GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS = 'quote_request.validation.error.wrong_status';

    /**
     * @see \Spryker\Zed\QuoteRequest\Business\Validator\QuoteRequestTimeValidator::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_VERSION_NOT_FOUND
     */
    protected const GLOSSARY_KEY_WRONG_QUOTE_REQUEST_VERSION_NOT_FOUND = 'quote_request.checkout.validation.error.version_not_found';

    /**
     * @see \Spryker\Zed\QuoteRequest\Business\Validator\QuoteRequestTimeValidator::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_STATUS
     */
    protected const GLOSSARY_KEY_WRONG_QUOTE_REQUEST_STATUS = 'quote_request.checkout.validation.error.wrong_status';

    /**
     * @see \Spryker\Zed\QuoteRequest\Business\Validator\QuoteRequestTimeValidator::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_VALID_UNTIL
     */
    protected const GLOSSARY_KEY_WRONG_QUOTE_REQUEST_VALID_UNTIL = 'quote_request.checkout.validation.error.wrong_valid_until';

    /**
     * @see \Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestUserWriter::GLOSSARY_KEY_QUOTE_REQUEST_EMPTY_QUOTE_ITEMS
     */
    protected const GLOSSARY_KEY_QUOTE_REQUEST_EMPTY_QUOTE_ITEMS = 'quote_request.validation.error.empty_quote_items';

    /**
     * @var \SprykerTest\Zed\QuoteRequest\QuoteRequestBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected $companyUserTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $customerTransfer = $this->tester->haveCustomer();

        $this->companyUserTransfer = $this->tester->createCompanyUser($customerTransfer);
        $this->quoteTransfer = (new QuoteBuilder())
            ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference()])
            ->withItem([ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(), ItemTransfer::UNIT_PRICE => 1, ItemTransfer::QUANTITY => 1])
            ->build();
    }

    /**
     * @return void
     */
    public function testGetQuoteRequestCollectionByFilterRetrievesCustomerQuoteRequests(): void
    {
        // Arrange
        $this->haveQuoteRequestInDraftStatus();
        $this->haveQuoteRequestInDraftStatus();

        $quoteRequestFilterTransfer = (new QuoteRequestFilterBuilder())->build()
            ->setCompanyUser($this->companyUserTransfer);

        // Act
        $quoteRequestCollectionTransfer = $this->tester
            ->getFacade()
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer);

        // Assert
        $this->assertCount(2, $quoteRequestCollectionTransfer->getQuoteRequests());
    }

    /**
     * @return void
     */
    public function testGetQuoteRequestCollectionByFilterRetrievesCustomerQuoteRequestByReference(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus();

        $quoteRequestFilterTransfer = (new QuoteRequestFilterBuilder())->build()
            ->setCompanyUser($this->companyUserTransfer)
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference());

        // Act
        $quoteRequestCollectionTransfer = $this->tester
            ->getFacade()
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer);

        // Assert
        $this->assertCount(1, $quoteRequestCollectionTransfer->getQuoteRequests());
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
        $this->assertEquals(SharedQuoteRequestConfig::STATUS_DRAFT, $storedQuoteRequestTransfer->getStatus());
        $this->assertEquals(
            $quoteRequestTransfer->getLatestVersion()->getQuote(),
            $storedQuoteRequestTransfer->getLatestVersion()->getQuote()
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

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateQuoteRequestUpdatesQuoteRequestVersionMetadataAndQuote(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus();

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
            $storedQuoteRequestTransfer->getLatestVersion()->getQuote()->getItems()
        );
        $this->assertEquals(
            $quoteRequestTransfer->getLatestVersion()->getMetadata(),
            $storedQuoteRequestTransfer->getLatestVersion()->getMetadata()
        );
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
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus()
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
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus();
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
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus()
            ->setQuoteRequestReference(static::FAKE_QUOTE_REQUEST_REFERENCE);

        // Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()->updateQuoteRequest($quoteRequestTransfer);

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testUpdateQuoteRequestThrowsErrorMessageQuoteRequestStatusNotEditable(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInWaitingStatus();

        // Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()->updateQuoteRequest($quoteRequestTransfer);

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testCancelQuoteRequestChangesQuoteRequestStatusToCanceled(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus();

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->cancelQuoteRequest($this->createFilterTransfer($quoteRequestTransfer));

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            SharedQuoteRequestConfig::STATUS_CANCELED,
            $quoteRequestResponseTransfer->getQuoteRequest()->getStatus()
        );
    }

    /**
     * @return void
     */
    public function testCancelQuoteRequestThrowsErrorMessageQuoteRequestNotFound(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestTransfer())
            ->setCompanyUser($this->companyUserTransfer)
            ->setQuoteRequestReference(static::FAKE_QUOTE_REQUEST_REFERENCE);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->cancelQuoteRequest($this->createFilterTransfer($quoteRequestTransfer));

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testCancelQuoteRequestThrowsErrorMessageQuoteRequestStatusNotCancelable(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInInProgressStatus();

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->cancelQuoteRequest($this->createFilterTransfer($quoteRequestTransfer));

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testCloseQuoteRequestChangesQuoteRequestStatusToClosed(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus();
        $quoteTransfer = (new QuoteTransfer())
            ->setQuoteRequestVersionReference($quoteRequestTransfer->getLatestVersion()->getVersionReference());

        // Act
         $this->tester
            ->getFacade()
            ->closeQuoteRequest($quoteTransfer);

        // Assert
        $quoteRequestCollection = $this->tester->getFacade()->getQuoteRequestCollectionByFilter(
            (new QuoteRequestFilterTransfer())->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference())
        );

        $this->assertEquals(
            SharedQuoteRequestConfig::STATUS_CLOSED,
            $quoteRequestCollection->getQuoteRequests()[0]->getStatus()
        );
    }

    /**
     * @return void
     */
    public function testCloseQuoteRequestSkipQuoteRequestWhenQuoteRequestNotFound(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus();
        $quoteTransfer = (new QuoteTransfer())
            ->setQuoteRequestVersionReference(static::FAKE_QUOTE_REQUEST_REFERENCE);

        // Act
         $this->tester
            ->getFacade()
            ->closeQuoteRequest($quoteTransfer);

        // Assert
        $quoteRequestCollection = $this->tester->getFacade()->getQuoteRequestCollectionByFilter(
            (new QuoteRequestFilterTransfer())->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference())
        );

        $this->assertEquals(
            SharedQuoteRequestConfig::STATUS_DRAFT,
            $quoteRequestCollection->getQuoteRequests()[0]->getStatus()
        );
    }

    /**
     * @return void
     */
    public function testIsQuoteRequestVersionReadyForCheckoutChecksQuoteRequestInQuoteWhenValidUntilNotSet(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInReadyStatus();

        $this->quoteTransfer->setQuoteRequestVersionReference(
            $quoteRequestTransfer->getLatestVersion()->getVersionReference()
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
        $quoteRequestTransfer = $this->haveQuoteRequestInReadyStatus(
            (new DateTime("+1 hour"))->format('Y-m-d H:i:s')
        );

        $this->quoteTransfer->setQuoteRequestVersionReference(
            $quoteRequestTransfer->getLatestVersion()->getVersionReference()
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
        $this->quoteTransfer->setQuoteRequestVersionReference(static::FAKE_QUOTE_REQUEST_VERSION_REFERENCE);
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isValid = $this->tester
            ->getFacade()
            ->isQuoteRequestVersionReadyForCheckout($this->quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($isValid);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertEquals(
            static::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_VERSION_NOT_FOUND,
            $checkoutResponseTransfer->getErrors()[0]->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testIsQuoteRequestVersionReadyForCheckoutThrowsErrorMessageQuoteRequestNotReady(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus();
        $this->quoteTransfer->setQuoteRequestVersionReference(
            $quoteRequestTransfer->getLatestVersion()->getVersionReference()
        );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isValid = $this->tester
            ->getFacade()
            ->isQuoteRequestVersionReadyForCheckout($this->quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($isValid);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertEquals(
            static::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_STATUS,
            $checkoutResponseTransfer->getErrors()[0]->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testIsQuoteRequestVersionReadyForCheckoutThrowsErrorMessageQuoteRequestWrongValidUntil(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInReadyStatus(
            (new DateTime("+1 second"))->format('Y-m-d H:i:s')
        );
        $this->quoteTransfer->setQuoteRequestVersionReference(
            $quoteRequestTransfer->getLatestVersion()->getVersionReference()
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
        $this->assertEquals(
            static::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_VALID_UNTIL,
            $checkoutResponseTransfer->getErrors()[0]->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testGetQuoteRequestVersionCollectionByFilterRetrievesQuoteRequestVersions(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus();

        $quoteRequestVersionFilterTransfer = (new QuoteRequestVersionFilterBuilder())->build()
            ->setQuoteRequest($quoteRequestTransfer);

        // Act
        $quoteRequestCollectionTransfer = $this->tester
            ->getFacade()
            ->getQuoteRequestVersionCollectionByFilter($quoteRequestVersionFilterTransfer);

        // Assert
        $this->assertCount(1, $quoteRequestCollectionTransfer->getQuoteRequestVersions());
    }

    /**
     * @return void
     */
    public function testGetQuoteRequestVersionCollectionByFilterRetrievesQuoteRequestVersionsByFakeReference(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus();

        $quoteRequestVersionFilterTransfer = (new QuoteRequestVersionFilterBuilder())->build()
            ->setQuoteRequest($quoteRequestTransfer)
            ->setQuoteRequestVersionReference(static::FAKE_QUOTE_REQUEST_VERSION_REFERENCE);

        // Act
        $quoteRequestCollectionTransfer = $this->tester
            ->getFacade()
            ->getQuoteRequestVersionCollectionByFilter($quoteRequestVersionFilterTransfer);

        // Assert
        $this->assertCount(0, $quoteRequestCollectionTransfer->getQuoteRequestVersions());
    }

    /**
     * @return void
     */
    public function testSendQuoteRequestToCompanyUserWhenQuoteRequestValidUntilNotSet(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInInProgressStatus(null, true);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->sendQuoteRequestToCompanyUser($this->createFilterTransfer($quoteRequestTransfer));
        $storedQuoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertTrue($storedQuoteRequestTransfer->getIsLatestVersionVisible());
        $this->assertEquals(SharedQuoteRequestConfig::STATUS_READY, $storedQuoteRequestTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testSendQuoteRequestToCompanyUserWhenQuoteRequestValidUntilCorrect(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInInProgressStatus(
            (new DateTime("+1 hour"))->format('Y-m-d H:i:s'),
            null
        );

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->sendQuoteRequestToCompanyUser($this->createFilterTransfer($quoteRequestTransfer));
        $storedQuoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertTrue($storedQuoteRequestTransfer->getIsLatestVersionVisible());
        $this->assertEquals(SharedQuoteRequestConfig::STATUS_READY, $storedQuoteRequestTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testSendQuoteRequestToCompanyUserThrowsErrorMessageQuoteRequestNotFound(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus()
            ->setQuoteRequestReference(static::FAKE_QUOTE_REQUEST_REFERENCE);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->sendQuoteRequestToCompanyUser($this->createFilterTransfer($quoteRequestTransfer));

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testSendQuoteRequestToCompanyUserThrowsErrorMessageWhenQuoteRequestStatusNotInProgress(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInWaitingStatus();

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->sendQuoteRequestToCompanyUser($this->createFilterTransfer($quoteRequestTransfer));

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testSendQuoteRequestToCompanyUserThrowsErrorMessageWhenEmptyQuoteItems(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInInProgressStatus();

        $this->quoteTransfer->setItems(new ArrayObject());
        $quoteRequestTransfer->getLatestVersion()->setQuote($this->quoteTransfer);

        $quoteRequestTransfer = $this->tester->getFacade()
            ->updateQuoteRequestForCompanyUser($quoteRequestTransfer)
            ->getQuoteRequest();

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->sendQuoteRequestToCompanyUser($this->createFilterTransfer($quoteRequestTransfer));

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_QUOTE_REQUEST_EMPTY_QUOTE_ITEMS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testReviseQuoteRequestForCompanyUserCreatesNewQuoteRequestVersionFromWaitingStatus(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInWaitingStatus();

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->reviseQuoteRequestForCompanyUser($this->createFilterTransfer($quoteRequestTransfer));
        $storedQuoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            SharedQuoteRequestConfig::STATUS_IN_PROGRESS,
            $quoteRequestResponseTransfer->getQuoteRequest()->getStatus()
        );
        $this->assertNotEquals(
            $quoteRequestTransfer->getLatestVersion()->getVersionReference(),
            $storedQuoteRequestTransfer->getLatestVersion()->getVersionReference()
        );
    }

    /**
     * @return void
     */
    public function testReviseQuoteRequestForCompanyUserCreatesNewQuoteRequestVersionFromReadyStatus(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInReadyStatus();

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->reviseQuoteRequestForCompanyUser($this->createFilterTransfer($quoteRequestTransfer));
        $storedQuoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            SharedQuoteRequestConfig::STATUS_IN_PROGRESS,
            $quoteRequestResponseTransfer->getQuoteRequest()->getStatus()
        );
        $this->assertNotEquals(
            $quoteRequestTransfer->getLatestVersion()->getVersionReference(),
            $storedQuoteRequestTransfer->getLatestVersion()->getVersionReference()
        );
    }

    /**
     * @return void
     */
    public function testReviseQuoteRequestForCompanyUserThrowsErrorMessageQuoteRequestNotFound(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestTransfer())
            ->setCompanyUser($this->companyUserTransfer)
            ->setQuoteRequestReference(static::FAKE_QUOTE_REQUEST_REFERENCE);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->reviseQuoteRequestForCompanyUser($this->createFilterTransfer($quoteRequestTransfer));

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testReviseQuoteRequestForCompanyUserThrowsErrorMessageWhenQuoteRequestStatusNotRevisable(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInInProgressStatus();

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->reviseQuoteRequestForCompanyUser($this->createFilterTransfer($quoteRequestTransfer));

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testReviseQuoteRequestCreatesNewQuoteRequestVersionFromReadyStatus(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInReadyStatus();

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->reviseQuoteRequest($this->createFilterTransfer($quoteRequestTransfer));
        $storedQuoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            SharedQuoteRequestConfig::STATUS_DRAFT,
            $quoteRequestResponseTransfer->getQuoteRequest()->getStatus()
        );
        $this->assertNotEquals(
            $quoteRequestTransfer->getLatestVersion()->getVersionReference(),
            $storedQuoteRequestTransfer->getLatestVersion()->getVersionReference()
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
            ->setQuoteRequestReference(static::FAKE_QUOTE_REQUEST_REFERENCE);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->reviseQuoteRequest($this->createFilterTransfer($quoteRequestTransfer));

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testReviseQuoteRequestThrowsErrorMessageWhenQuoteRequestStatusNotRevisable(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus();

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->reviseQuoteRequest($this->createFilterTransfer($quoteRequestTransfer));

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testReviseQuoteRequestClearsSourcePrices(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInInProgressStatus();
        $quoteTransfer = clone $this->quoteTransfer;

        $quoteTransfer->getItems()->offsetGet(0)
            ->setSourceUnitGrossPrice(1)
            ->setSourceUnitNetPrice(2);

        $quoteRequestTransfer->getLatestVersion()->setQuote($quoteTransfer);
        $quoteRequestTransfer = $this->tester
            ->getFacade()
            ->updateQuoteRequestForCompanyUser($quoteRequestTransfer)
            ->getQuoteRequest();

        $quoteRequestFilterTransfer = $this->createFilterTransfer($quoteRequestTransfer);
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

    /**
     * @return void
     */
    public function testSendQuoteRequestToUserWhenQuoteRequestStatusIsDraft(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus();

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->sendQuoteRequestToUser($this->createFilterTransfer($quoteRequestTransfer));

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            SharedQuoteRequestConfig::STATUS_WAITING,
            $quoteRequestResponseTransfer->getQuoteRequest()->getStatus()
        );
    }

    /**
     * @return void
     */
    public function testSendQuoteRequestToUserThrowsErrorMessageQuoteRequestNotFound(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestTransfer())
            ->setCompanyUser($this->companyUserTransfer)
            ->setQuoteRequestReference(static::FAKE_QUOTE_REQUEST_REFERENCE);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->sendQuoteRequestToUser($this->createFilterTransfer($quoteRequestTransfer));

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testSendQuoteRequestToUserThrowsErrorMessageWhenQuoteRequestStatusNotDraft(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInWaitingStatus();

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->sendQuoteRequestToUser($this->createFilterTransfer($quoteRequestTransfer));

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testSendQuoteRequestToUserThrowsErrorMessageWhenEmptyQuoteItems(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus();

        $this->quoteTransfer->setItems(new ArrayObject());
        $quoteRequestTransfer->getLatestVersion()->setQuote($this->quoteTransfer);

        $quoteRequestTransfer = $this->tester->getFacade()
            ->updateQuoteRequest($quoteRequestTransfer)
            ->getQuoteRequest();

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->sendQuoteRequestToUser($this->createFilterTransfer($quoteRequestTransfer));

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_QUOTE_REQUEST_EMPTY_QUOTE_ITEMS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testCancelQuoteRequestForCompanyUserChangesQuoteRequestStatusToCanceled(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus();

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->cancelQuoteRequestForCompanyUser($this->createFilterTransfer($quoteRequestTransfer));

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            SharedQuoteRequestConfig::STATUS_CANCELED,
            $quoteRequestResponseTransfer->getQuoteRequest()->getStatus()
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
            ->setQuoteRequestReference(static::FAKE_QUOTE_REQUEST_REFERENCE);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->cancelQuoteRequestForCompanyUser($this->createFilterTransfer($quoteRequestTransfer));

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testCancelQuoteRequestForCompanyUserThrowsErrorMessageWhenQuoteRequestStatusIsClosed(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus();
        $quoteTransfer = (new QuoteTransfer())
            ->setQuoteRequestVersionReference($quoteRequestTransfer->getLatestVersion()->getVersionReference());

        $this->tester->getFacade()->closeQuoteRequest($quoteTransfer);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->cancelQuoteRequestForCompanyUser($this->createFilterTransfer($quoteRequestTransfer));

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testCreateUserQuoteRequestCreatesUserQuoteRequest(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder())->build()
            ->setCompanyUser($this->companyUserTransfer);

        // Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()->createQuoteRequestForCompanyUser($quoteRequestTransfer);
        $storedQuoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(SharedQuoteRequestConfig::STATUS_IN_PROGRESS, $storedQuoteRequestTransfer->getStatus());
        $this->assertNotNull($storedQuoteRequestTransfer->getLatestVersion());
        $this->assertEquals(
            $quoteRequestTransfer->getCompanyUser()->getIdCompanyUser(),
            $storedQuoteRequestTransfer->getCompanyUser()->getIdCompanyUser()
        );
    }

    /**
     * @return void
     */
    public function testCreateUserQuoteRequestThrowsExceptionWithEmptyCompanyUser(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder())->build();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createQuoteRequestForCompanyUser($quoteRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreateUserQuoteRequestThrowsExceptionWithEmptyIdCompanyUser(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder())->build()
            ->setCompanyUser(new CompanyUserTransfer());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createQuoteRequestForCompanyUser($quoteRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCloseOutdatedQuoteRequestsUpdateReadyQuoteRequestStatusToClosed(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInReadyStatus(
            (new DateTime("+1 second"))->format('Y-m-d H:i:s')
        );

        // Act
        sleep(2);
        $this->tester->getFacade()->closeOutdatedQuoteRequests();
        $storedQuoteRequestTransfer = $this->getQuoteRequestByReference($quoteRequestTransfer->getQuoteRequestReference());

        // Assert
        $this->assertEquals(SharedQuoteRequestConfig::STATUS_CLOSED, $storedQuoteRequestTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testCloseOutdatedQuoteRequestsNotUpdatesQuoteRequestStatusToClosedWhenQuoteRequestNotReady(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInInProgressStatus(
            (new DateTime("+1 second"))->format('Y-m-d H:i:s')
        );

        // Act
        sleep(2);
        $this->tester->getFacade()->closeOutdatedQuoteRequests();
        $storedQuoteRequestTransfer = $this->getQuoteRequestByReference($quoteRequestTransfer->getQuoteRequestReference());

        // Assert
        $this->assertEquals($storedQuoteRequestTransfer->getStatus(), SharedQuoteRequestConfig::STATUS_IN_PROGRESS);
    }

    /**
     * @return void
     */
    public function testCloseOutdatedQuoteRequestsNotUpdatesQuoteRequestStatusToClosedWhenValidUntilNotSet(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInReadyStatus();

        // Act
        $this->tester->getFacade()->closeOutdatedQuoteRequests();
        $storedQuoteRequestTransfer = $this->getQuoteRequestByReference($quoteRequestTransfer->getQuoteRequestReference());

        // Assert
        $this->assertEquals($storedQuoteRequestTransfer->getStatus(), SharedQuoteRequestConfig::STATUS_READY);
    }

    /**
     * @return void
     */
    public function testCloseOutdatedQuoteRequestsNotUpdatesQuoteRequestStatusToClosedWhenValidUntilGreaterCurrentTime(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInReadyStatus(
            (new DateTime("+1 hour"))->format('Y-m-d H:i:s')
        );

        // Act
        $this->tester->getFacade()->closeOutdatedQuoteRequests();
        $storedQuoteRequestTransfer = $this->getQuoteRequestByReference($quoteRequestTransfer->getQuoteRequestReference());

        // Assert
        $this->assertEquals($storedQuoteRequestTransfer->getStatus(), SharedQuoteRequestConfig::STATUS_READY);
    }

    /**
     * @return void
     */
    public function testUpdateQuoteRequestForCompanyUserUpdatesQuoteRequest(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInInProgressStatus();

        $quoteRequestTransfer->setIsLatestVersionVisible(false)
            ->setValidUntil((new DateTime("+1 hour"))->format('Y-m-d H:i:s'))
            ->getLatestVersion()
            ->setMetadata(['test' => 'test'])
            ->getQuote()
            ->setItems(new ArrayObject());

        // Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()->updateQuoteRequestForCompanyUser($quoteRequestTransfer);
        $storedQuoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals($quoteRequestTransfer->getValidUntil(), $storedQuoteRequestTransfer->getValidUntil());
        $this->assertEquals($quoteRequestTransfer->getIsLatestVersionVisible(), $storedQuoteRequestTransfer->getIsLatestVersionVisible());
        $this->assertEquals(
            $quoteRequestTransfer->getLatestVersion()->getQuote()->getItems(),
            $storedQuoteRequestTransfer->getLatestVersion()->getQuote()->getItems()
        );
        $this->assertEquals(
            $quoteRequestTransfer->getLatestVersion()->getMetadata(),
            $storedQuoteRequestTransfer->getLatestVersion()->getMetadata()
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
        $quoteRequestTransfer = $this->haveQuoteRequestInInProgressStatus()
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
        $quoteRequestTransfer = $this->haveQuoteRequestInInProgressStatus();
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
        $quoteRequestTransfer = $this->haveQuoteRequestInInProgressStatus()
            ->setQuoteRequestReference(static::FAKE_QUOTE_REQUEST_REFERENCE);

        // Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()->updateQuoteRequestForCompanyUser($quoteRequestTransfer);

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testUpdateQuoteRequestForCompanyUserThrowsErrorMessageQuoteRequestStatusNotEditable(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus();

        // Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()->updateQuoteRequestForCompanyUser($quoteRequestTransfer);

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testFindQuoteRequestRetrievesQuoteRequestByReference(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus();

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference());

        // Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()->getQuoteRequest($quoteRequestFilterTransfer);
        $storedQuoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals($quoteRequestTransfer->getIdQuoteRequest(), $storedQuoteRequestTransfer->getIdQuoteRequest());
    }

    /**
     * @return void
     */
    public function testFindQuoteRequestRetrievesEmptyResultByFakeReference(): void
    {
        // Arrange
        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference(static::FAKE_QUOTE_REQUEST_REFERENCE);

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

    /**
     * @return void
     */
    public function testSanitizeQuoteRequestSanitizeQuoteRequestInQuote(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus();
        $quoteTransfer = (new QuoteTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference())
            ->setQuoteRequestVersionReference($quoteRequestTransfer->getLatestVersion()->getVersionReference());

        // Act
        $updatedQuoteTransfer = $this->tester->getFacade()->sanitizeQuoteRequest($quoteTransfer);

        // Assert
        $this->assertNull($updatedQuoteTransfer->getQuoteRequestReference());
        $this->assertNull($updatedQuoteTransfer->getQuoteRequestVersionReference());
    }

    /**
     * @return void
     */
    public function testDeleteQuoteRequestsForCompanyUserWillDeleteAllAssignedQuoteRequests(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInInProgressStatus();
        $quoteTransfer = (new QuoteTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference())
            ->setQuoteRequestVersionReference($quoteRequestTransfer->getLatestVersion()->getVersionReference());

        // Act
        $this->tester->getFacade()->deleteQuoteRequestsByIdCompanyUser(
            $quoteRequestTransfer->getCompanyUser()->getIdCompanyUser()
        );
        $quoteRequestCollection = $this->tester->getFacade()->getQuoteRequestCollectionByFilter(
            $this->createFilterTransfer($quoteRequestTransfer)
        );

        // Assert
        $this->assertSame(0, $quoteRequestCollection->getQuoteRequests()->count());
    }

    /**
     * @param string $quoteRequestReference
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function getQuoteRequestByReference(string $quoteRequestReference): QuoteRequestTransfer
    {
        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestReference);

        $storedQuoteRequestTransfers = $this->tester
            ->getFacade()
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer)
            ->getQuoteRequests()
            ->getArrayCopy();

        return array_shift($storedQuoteRequestTransfers);
    }

    /**
     * @param string|null $validUntil
     * @param bool|null $isLatestVersionVisible
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function haveQuoteRequestInReadyStatus(
        ?string $validUntil = null,
        ?bool $isLatestVersionVisible = null
    ): QuoteRequestTransfer {
        $quoteRequestTransfer = $this->haveQuoteRequestInInProgressStatus($validUntil, $isLatestVersionVisible);

        return $this->tester->getFacade()
            ->sendQuoteRequestToCompanyUser($this->createFilterTransfer($quoteRequestTransfer))
            ->getQuoteRequest();
    }

    /**
     * @param string|null $validUntil
     * @param bool|null $isLatestVersionVisible
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function haveQuoteRequestInInProgressStatus(
        ?string $validUntil = null,
        ?bool $isLatestVersionVisible = null
    ): QuoteRequestTransfer {
        $quoteRequestTransfer = $this->haveQuoteRequestInWaitingStatus();

        $quoteRequestTransfer = $this->tester->getFacade()
            ->reviseQuoteRequestForCompanyUser($this->createFilterTransfer($quoteRequestTransfer))
            ->getQuoteRequest();

        $quoteRequestTransfer
            ->setValidUntil($validUntil)
            ->setIsLatestVersionVisible($isLatestVersionVisible);

        if ($validUntil || !$isLatestVersionVisible) {
            return $this->tester->getFacade()
                ->updateQuoteRequestForCompanyUser($quoteRequestTransfer)
                ->getQuoteRequest();
        }

        return $quoteRequestTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function haveQuoteRequestInWaitingStatus(): QuoteRequestTransfer
    {
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus();

        return $this->tester->getFacade()
            ->sendQuoteRequestToUser($this->createFilterTransfer($quoteRequestTransfer))
            ->getQuoteRequest();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function haveQuoteRequestInDraftStatus(): QuoteRequestTransfer
    {
        return $this->tester->createQuoteRequest(
            $this->tester->createQuoteRequestVersion($this->quoteTransfer),
            $this->companyUserTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestFilterTransfer
     */
    protected function createFilterTransfer(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestFilterTransfer
    {
        return (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference())
            ->setIdCompanyUser($quoteRequestTransfer->getCompanyUser()->getIdCompanyUser());
    }
}

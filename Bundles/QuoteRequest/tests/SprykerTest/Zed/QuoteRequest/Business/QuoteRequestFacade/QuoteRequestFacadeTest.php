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
use Generated\Shared\Transfer\QuoteRequestCriteriaTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Shared\QuoteRequest\QuoteRequestConfig as SharedQuoteRequestConfig;
use Spryker\Shared\QuoteRequest\QuoteRequestConfig;

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
        $this->assertEquals(QuoteRequestConfig::INITIAL_VERSION_NUMBER, $storedQuoteRequestTransfer->getLatestVersion()->getVersion());
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
            ->cancelQuoteRequest($this->createCriteriaTransfer($quoteRequestTransfer));

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
            ->cancelQuoteRequest($this->createCriteriaTransfer($quoteRequestTransfer));

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
            ->cancelQuoteRequest($this->createCriteriaTransfer($quoteRequestTransfer));

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
    public function testCheckQuoteRequestChecksQuoteRequestInQuoteWhenValidUntilNotSet(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInReadyStatus();

        $this->quoteTransfer->setQuoteRequestVersionReference(
            $quoteRequestTransfer->getLatestVersion()->getVersionReference()
        );

        // Act
        $isValid = $this->tester
            ->getFacade()
            ->checkQuoteRequest($this->quoteTransfer, new CheckoutResponseTransfer());

        // Assert
        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testCheckQuoteRequestChecksQuoteRequestInQuoteWhenValidUntilSet(): void
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
            ->checkQuoteRequest($this->quoteTransfer, new CheckoutResponseTransfer());

        // Assert
        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testCheckQuoteRequestSkipCheckWhenQuoteRequestVersionReferenceNotProvided(): void
    {
        // Arrange
        $this->quoteTransfer->setQuoteRequestVersionReference(null);

        // Act
        $isValid = $this->tester
            ->getFacade()
            ->checkQuoteRequest($this->quoteTransfer, new CheckoutResponseTransfer());

        // Assert
        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testCheckQuoteRequestThrowsErrorMessageQuoteRequestVersionNotFound(): void
    {
        // Arrange
        $this->quoteTransfer->setQuoteRequestVersionReference(static::FAKE_QUOTE_REQUEST_VERSION_REFERENCE);
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isValid = $this->tester
            ->getFacade()
            ->checkQuoteRequest($this->quoteTransfer, $checkoutResponseTransfer);

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
    public function testCheckQuoteRequestThrowsErrorMessageQuoteRequestNotReady(): void
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
            ->checkQuoteRequest($this->quoteTransfer, $checkoutResponseTransfer);

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
    public function testCheckQuoteRequestThrowsErrorMessageQuoteRequestWrongValidUntil(): void
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
            ->checkQuoteRequest($this->quoteTransfer, $checkoutResponseTransfer);

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
    public function testSendQuoteRequestToCustomerWhenQuoteRequestValidUntilNotSet(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInInProgressStatus(null, true);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->sendQuoteRequestToCustomer($this->createCriteriaTransfer($quoteRequestTransfer));
        $storedQuoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertFalse($storedQuoteRequestTransfer->getIsLatestVersionHidden());
        $this->assertEquals(SharedQuoteRequestConfig::STATUS_READY, $storedQuoteRequestTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testSendQuoteRequestToCustomerWhenQuoteRequestValidUntilCorrect(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInInProgressStatus(
            (new DateTime("+1 hour"))->format('Y-m-d H:i:s'),
            null
        );

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->sendQuoteRequestToCustomer($this->createCriteriaTransfer($quoteRequestTransfer));
        $storedQuoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertFalse($storedQuoteRequestTransfer->getIsLatestVersionHidden());
        $this->assertEquals(SharedQuoteRequestConfig::STATUS_READY, $storedQuoteRequestTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testSendQuoteRequestToCustomerThrowsErrorMessageQuoteRequestNotFound(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus()
            ->setQuoteRequestReference(static::FAKE_QUOTE_REQUEST_REFERENCE);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->sendQuoteRequestToCustomer($this->createCriteriaTransfer($quoteRequestTransfer));

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
    public function testSendQuoteRequestToCustomerThrowsErrorMessageWhenQuoteRequestStatusNotInProgress(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInWaitingStatus();

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->sendQuoteRequestToCustomer($this->createCriteriaTransfer($quoteRequestTransfer));

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
    public function testSendQuoteRequestToCustomerThrowsErrorMessageWhenEmptyQuoteItems(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInInProgressStatus();

        $this->quoteTransfer->setItems(new ArrayObject());
        $quoteRequestTransfer->getLatestVersion()->setQuote($this->quoteTransfer);

        $quoteRequestTransfer = $this->tester->getFacade()
            ->updateUserQuoteRequest($quoteRequestTransfer)
            ->getQuoteRequest();

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->sendQuoteRequestToCustomer($this->createCriteriaTransfer($quoteRequestTransfer));

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
    public function testReviseUserQuoteRequestCreatesNewQuoteRequestVersionFromWaitingStatus(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInWaitingStatus();

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->reviseUserQuoteRequest($this->createCriteriaTransfer($quoteRequestTransfer));
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
    public function testReviseUserQuoteRequestCreatesNewQuoteRequestVersionFromReadyStatus(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInReadyStatus();

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->reviseUserQuoteRequest($this->createCriteriaTransfer($quoteRequestTransfer));
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
    public function testReviseUserQuoteRequestThrowsErrorMessageQuoteRequestNotFound(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestTransfer())
            ->setCompanyUser($this->companyUserTransfer)
            ->setQuoteRequestReference(static::FAKE_QUOTE_REQUEST_REFERENCE);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->reviseUserQuoteRequest($this->createCriteriaTransfer($quoteRequestTransfer));

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
    public function testReviseUserQuoteRequestThrowsErrorMessageWhenQuoteRequestStatusNotRevisable(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInInProgressStatus();

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->reviseUserQuoteRequest($this->createCriteriaTransfer($quoteRequestTransfer));

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
            ->reviseQuoteRequest($this->createCriteriaTransfer($quoteRequestTransfer));
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
            ->reviseQuoteRequest($this->createCriteriaTransfer($quoteRequestTransfer));

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
            ->reviseQuoteRequest($this->createCriteriaTransfer($quoteRequestTransfer));

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
            ->updateUserQuoteRequest($quoteRequestTransfer)
            ->getQuoteRequest();

        $quoteRequestCriteriaTransfer = $this->createCriteriaTransfer($quoteRequestTransfer);
        $this->tester->getFacade()->sendQuoteRequestToCustomer($quoteRequestCriteriaTransfer);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->reviseQuoteRequest($quoteRequestCriteriaTransfer);

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
            ->sendQuoteRequestToUser($this->createCriteriaTransfer($quoteRequestTransfer));

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
            ->sendQuoteRequestToUser($this->createCriteriaTransfer($quoteRequestTransfer));

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
            ->sendQuoteRequestToUser($this->createCriteriaTransfer($quoteRequestTransfer));

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
            ->sendQuoteRequestToUser($this->createCriteriaTransfer($quoteRequestTransfer));

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
    public function testCancelUserQuoteRequestChangesQuoteRequestStatusToCanceled(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus();

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->cancelUserQuoteRequest($this->createCriteriaTransfer($quoteRequestTransfer));

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
    public function testCancelUserQuoteRequestThrowsErrorMessageQuoteRequestNotFound(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestTransfer())
            ->setCompanyUser($this->companyUserTransfer)
            ->setQuoteRequestReference(static::FAKE_QUOTE_REQUEST_REFERENCE);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->cancelUserQuoteRequest($this->createCriteriaTransfer($quoteRequestTransfer));

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
    public function testCancelUserQuoteRequestThrowsErrorMessageWhenQuoteRequestStatusIsClosed(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus();
        $quoteTransfer = (new QuoteTransfer())
            ->setQuoteRequestVersionReference($quoteRequestTransfer->getLatestVersion()->getVersionReference());

        $this->tester->getFacade()->closeQuoteRequest($quoteTransfer);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->cancelUserQuoteRequest($this->createCriteriaTransfer($quoteRequestTransfer));

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
        $quoteRequestResponseTransfer = $this->tester->getFacade()->createUserQuoteRequest($quoteRequestTransfer);
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
        $this->tester->getFacade()->createUserQuoteRequest($quoteRequestTransfer);
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
        $this->tester->getFacade()->createUserQuoteRequest($quoteRequestTransfer);
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
    public function testUpdateUserQuoteRequestUpdatesQuoteRequest(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInInProgressStatus();

        $quoteRequestTransfer->setIsLatestVersionHidden(true)
            ->setValidUntil((new DateTime("+1 hour"))->format('Y-m-d H:i:s'))
            ->getLatestVersion()
            ->setMetadata(['test' => 'test'])
            ->getQuote()
            ->setItems(new ArrayObject());

        // Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()->updateUserQuoteRequest($quoteRequestTransfer);
        $storedQuoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals($quoteRequestTransfer->getValidUntil(), $storedQuoteRequestTransfer->getValidUntil());
        $this->assertEquals($quoteRequestTransfer->getIsLatestVersionHidden(), $storedQuoteRequestTransfer->getIsLatestVersionHidden());
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
    public function testUpdateUserQuoteRequestThrowsExceptionWithEmptyQuoteRequestReference(): void
    {
        // Arrange
        $quoteRequestTransfer = new QuoteRequestTransfer();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateUserQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateUserQuoteRequestThrowsExceptionWithEmptyCompanyUser(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInInProgressStatus()
            ->setCompanyUser(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateUserQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateUserQuoteRequestThrowsExceptionWithEmptyIdCompanyUser(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInInProgressStatus();
        $quoteRequestTransfer->getCompanyUser()->setIdCompanyUser(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateUserQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateUserQuoteRequestThrowsErrorMessageQuoteRequestNotFound(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInInProgressStatus()
            ->setQuoteRequestReference(static::FAKE_QUOTE_REQUEST_REFERENCE);

        // Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()->updateUserQuoteRequest($quoteRequestTransfer);

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
    public function testUpdateUserQuoteRequestThrowsErrorMessageQuoteRequestStatusNotEditable(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus();

        // Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()->updateUserQuoteRequest($quoteRequestTransfer);

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue()
        );
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
     * @param bool|null $isLatestVersionHidden
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function haveQuoteRequestInReadyStatus(
        ?string $validUntil = null,
        ?bool $isLatestVersionHidden = null
    ): QuoteRequestTransfer {
        $quoteRequestTransfer = $this->haveQuoteRequestInInProgressStatus($validUntil, $isLatestVersionHidden);

        return $this->tester->getFacade()
            ->sendQuoteRequestToCustomer($this->createCriteriaTransfer($quoteRequestTransfer))
            ->getQuoteRequest();
    }

    /**
     * @param string|null $validUntil
     * @param bool|null $isLatestVersionHidden
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function haveQuoteRequestInInProgressStatus(
        ?string $validUntil = null,
        ?bool $isLatestVersionHidden = null
    ): QuoteRequestTransfer {
        $quoteRequestTransfer = $this->haveQuoteRequestInWaitingStatus();

        $quoteRequestTransfer = $this->tester->getFacade()
            ->reviseUserQuoteRequest($this->createCriteriaTransfer($quoteRequestTransfer))
            ->getQuoteRequest();

        $quoteRequestTransfer
            ->setValidUntil($validUntil)
            ->setIsLatestVersionHidden($isLatestVersionHidden);

        if ($validUntil || $isLatestVersionHidden) {
            return $this->tester->getFacade()
                ->updateUserQuoteRequest($quoteRequestTransfer)
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
            ->sendQuoteRequestToUser($this->createCriteriaTransfer($quoteRequestTransfer))
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
     * @return \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer
     */
    protected function createCriteriaTransfer(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestCriteriaTransfer
    {
        return (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference())
            ->setIdCompanyUser($quoteRequestTransfer->getCompanyUser()->getIdCompanyUser());
    }
}

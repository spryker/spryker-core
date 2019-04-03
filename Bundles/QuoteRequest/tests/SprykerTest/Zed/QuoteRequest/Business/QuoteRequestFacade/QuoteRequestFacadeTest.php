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
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteRequestCriteriaTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
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
    protected const FAKE_QUOTE_REQUEST_VERSION_REFERENCE = 'FAKE_QUOTE_REQUEST_VERSION_REFERENCE';

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
    public function testCreateQuoteRequestCreatesFirstVersionWithDraftStatus(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder())->build()
            ->setCompanyUser($this->companyUserTransfer)
            ->setLatestVersion($this->tester->createQuoteRequestVersion($this->quoteTransfer));

        // Act
        $storedQuoteRequestTransfer = $this->tester->getFacade()->createQuoteRequest($quoteRequestTransfer)->getQuoteRequest();

        // Assert
        $this->assertEquals(QuoteRequestConfig::STATUS_DRAFT, $storedQuoteRequestTransfer->getStatus());
        $this->assertEquals(QuoteRequestConfig::INITIAL_VERSION_NUMBER, $storedQuoteRequestTransfer->getLatestVersion()->getVersion());
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
    public function testUpdateQuoteRequestUpdatesQuoteRequest(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus();

        $quoteRequestTransfer->setIsLatestVersionHidden(true);

        // Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()->updateQuoteRequest($quoteRequestTransfer);
        $storedQuoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertNotNull($storedQuoteRequestTransfer->getLatestVersion());
        $this->assertEquals($quoteRequestTransfer->getCompanyUser(), $storedQuoteRequestTransfer->getCompanyUser());
        $this->assertEquals($quoteRequestTransfer->getStatus(), $storedQuoteRequestTransfer->getStatus());
        $this->assertEquals($quoteRequestTransfer->getIsLatestVersionHidden(), $storedQuoteRequestTransfer->getIsLatestVersionHidden());
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
    public function testCloseQuoteRequestChangesQuoteRequestStatusToClosed(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus();

        // Act
         $this->tester
            ->getFacade()
            ->closeQuoteRequest($quoteRequestTransfer->getLatestVersion()->getVersionReference());

        // Assert
        $quoteRequestCollection = $this->tester->getFacade()
            ->getQuoteRequestCollectionByFilter((new QuoteRequestFilterTransfer())
                ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference()));

        $this->assertEquals(
            SharedQuoteRequestConfig::STATUS_CLOSED,
            $quoteRequestCollection->getQuoteRequests()[0]->getStatus()
        );
    }

    /**
     * @return void
     */
    public function testCheckQuoteRequestValidatesQuoteWithWrongQuoteRequestVersionReference(): void
    {
        // Arrange
        $this->haveQuoteRequestInDraftStatus();

        $this->quoteTransfer->setQuoteRequestVersionReference(static::FAKE_QUOTE_REQUEST_VERSION_REFERENCE);
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isValid = $this->tester
            ->getFacade()
            ->checkQuoteRequest($this->quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($isValid);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testCheckQuoteRequestValidatesQuoteWithWrongQuoteRequestStatus(): void
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
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
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
    public function testSendQuoteRequestToCustomerSuccessful(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInInProgressStatus();

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->sendQuoteRequestToCustomer($this->createCriteriaTransfer($quoteRequestTransfer));

        // Assert
        $quoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(SharedQuoteRequestConfig::STATUS_READY, $quoteRequestTransfer->getStatus());
        $this->assertFalse($quoteRequestTransfer->getIsLatestVersionHidden());
    }

    /**
     * @return void
     */
    public function testReviseUserQuoteRequestChangesQuoteRequestStatusToInProgress(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInWaitingStatus();

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->reviseUserQuoteRequest($this->createCriteriaTransfer($quoteRequestTransfer));

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            SharedQuoteRequestConfig::STATUS_IN_PROGRESS,
            $quoteRequestResponseTransfer->getQuoteRequest()->getStatus()
        );
    }

    /**
     * @return void
     */
    public function testReviseQuoteRequestChangesQuoteRequestStatusToDraft(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInReadyStatus();

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->reviseQuoteRequest($this->createCriteriaTransfer($quoteRequestTransfer));

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            SharedQuoteRequestConfig::STATUS_DRAFT,
            $quoteRequestResponseTransfer->getQuoteRequest()->getStatus()
        );
    }

    /**
     * @return void
     */
    public function testReviseQuoteRequestCreatesNewVersion(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInReadyStatus();

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->reviseQuoteRequest($this->createCriteriaTransfer($quoteRequestTransfer));

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertNotEquals(
            $quoteRequestTransfer->getLatestVersion()->getVersionReference(),
            $quoteRequestResponseTransfer->getQuoteRequest()->getLatestVersion()->getVersionReference()
        );
    }

    /**
     * @return void
     */
    public function testReviseQuoteRequestClearsSourcePrices(): void
    {
        // Arrange
        $quoteTransfer = clone $this->quoteTransfer;

        $quoteTransfer->getItems()->offsetGet(0)
            ->setSourceUnitGrossPrice(1)
            ->setSourceUnitNetPrice(2);

        $quoteRequestTransfer = $this->tester->createQuoteRequest(
            $this->tester->createQuoteRequestVersion($quoteTransfer),
            $this->companyUserTransfer
        );

        $quoteRequestCriteriaTransfer = $this->createCriteriaTransfer($quoteRequestTransfer);

        $this->tester->getFacade()->sendQuoteRequestToUser($quoteRequestCriteriaTransfer);
        $this->tester->getFacade()->reviseUserQuoteRequest($quoteRequestCriteriaTransfer);
        $this->tester->getFacade()->sendQuoteRequestToCustomer($quoteRequestCriteriaTransfer);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->reviseQuoteRequest($quoteRequestCriteriaTransfer);

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());

        $itemTransfer = $quoteRequestResponseTransfer->getQuoteRequest()->getLatestVersion()->getQuote()->getItems()->offsetGet(0);

        $this->assertNull($itemTransfer->getSourceUnitGrossPrice());
        $this->assertNull($itemTransfer->getSourceUnitNetPrice());
    }

    /**
     * @return void
     */
    public function testSendQuoteRequestToUserChangesQuoteRequestStatusToWaiting(): void
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
        $this->assertEquals($quoteRequestTransfer->getCompanyUser()->getIdCompanyUser(), $storedQuoteRequestTransfer->getCompanyUser()->getIdCompanyUser());
        $this->assertEquals(SharedQuoteRequestConfig::STATUS_IN_PROGRESS, $storedQuoteRequestTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testCloseOutdatedQuoteRequestsUpdateQuoteRequestStatusToClosed(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->haveQuoteRequestInWaitingStatus();

        $quoteRequestTransfer = $this->tester->getFacade()
            ->reviseUserQuoteRequest($this->createCriteriaTransfer($quoteRequestTransfer))
            ->getQuoteRequest();

        $quoteRequestTransfer->setValidUntil((new DateTime('+1 sec'))->format('Y-m-d H:i:s'));

        $this->tester->getFacade()->updateUserQuoteRequest($quoteRequestTransfer);
        $this->tester->getFacade()->sendQuoteRequestToCustomer($this->createCriteriaTransfer($quoteRequestTransfer));
        sleep(1);

        // Act
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
        $quoteRequestTransfer = $this->haveQuoteRequestInWaitingStatus();
        $quoteRequestTransfer = $this->tester->getFacade()
            ->reviseUserQuoteRequest($this->createCriteriaTransfer($quoteRequestTransfer))
            ->getQuoteRequest();

        $quoteRequestTransfer->setValidUntil((new DateTime('+1 sec'))->format('Y-m-d H:i:s'));
        $this->tester->getFacade()->updateUserQuoteRequest($quoteRequestTransfer);
        sleep(1);

        // Act
        $this->tester->getFacade()->closeOutdatedQuoteRequests();
        $storedQuoteRequestTransfer = $this->getQuoteRequestByReference($quoteRequestTransfer->getQuoteRequestReference());

        // Assert
        $this->assertEquals($storedQuoteRequestTransfer->getStatus(), SharedQuoteRequestConfig::STATUS_IN_PROGRESS);
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
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function haveQuoteRequestInInProgressStatus(): QuoteRequestTransfer
    {
        $quoteRequestTransfer = $this->haveQuoteRequestInWaitingStatus();

        return $this->tester->getFacade()
            ->reviseUserQuoteRequest($this->createCriteriaTransfer($quoteRequestTransfer))
            ->getQuoteRequest();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function haveQuoteRequestInReadyStatus(): QuoteRequestTransfer
    {
        $quoteRequestTransfer = $this->haveQuoteRequestInInProgressStatus();

        return $this->tester->getFacade()
            ->sendQuoteRequestToCustomer($this->createCriteriaTransfer($quoteRequestTransfer))
            ->getQuoteRequest();
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

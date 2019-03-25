<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteRequest\Business\QuoteRequestFacade;

use ArrayObject;
use Codeception\Test\Unit;
use DateInterval;
use DateTime;
use Generated\Shared\DataBuilder\QuoteRequestBuilder;
use Generated\Shared\DataBuilder\QuoteRequestFilterBuilder;
use Generated\Shared\DataBuilder\QuoteRequestVersionFilterBuilder;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
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
        $this->quoteTransfer = $this->tester->createQuote(
            $customerTransfer,
            $this->tester->haveProduct()
        );
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
        $this->assertEquals(
            $quoteRequestTransfer->getLatestVersion()->getQuote(),
            $storedQuoteRequestTransfer->getLatestVersion()->getQuote()
        );
    }

    /**
     * @return void
     */
    public function testCreateQuoteRequestCreatesQuoteRequestWithEmptyQuoteItems(): void
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
    public function testCreateQuoteRequestCreatesFirstVersionWithWaitingStatus(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder())->build()
            ->setCompanyUser($this->companyUserTransfer)
            ->setLatestVersion($this->tester->createQuoteRequestVersion($this->quoteTransfer));

        // Act
        $storedQuoteRequestTransfer = $this->tester->getFacade()->createQuoteRequest($quoteRequestTransfer)->getQuoteRequest();

        // Assert
        $this->assertEquals(QuoteRequestConfig::STATUS_WAITING, $storedQuoteRequestTransfer->getStatus());
        $this->assertEquals(QuoteRequestConfig::INITIAL_VERSION_NUMBER, $storedQuoteRequestTransfer->getLatestVersion()->getVersion());
    }

    /**
     * @return void
     */
    public function testGetQuoteRequestCollectionByFilterRetrievesCustomerQuoteRequests(): void
    {
        // Arrange
        $this->tester->createQuoteRequest(
            $this->tester->createQuoteRequestVersion($this->quoteTransfer),
            $this->companyUserTransfer
        );
        $this->tester->createQuoteRequest(
            $this->tester->createQuoteRequestVersion($this->quoteTransfer),
            $this->companyUserTransfer
        );
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
        $quoteRequestTransfer = $this->tester->createQuoteRequest(
            $this->tester->createQuoteRequestVersion($this->quoteTransfer),
            $this->companyUserTransfer
        );

        $quoteRequestTransfer = (new QuoteRequestBuilder([
            QuoteRequestTransfer::ID_QUOTE_REQUEST => $quoteRequestTransfer->getIdQuoteRequest(),
            QuoteRequestTransfer::COMPANY_USER => $quoteRequestTransfer->getCompanyUser(),
            QuoteRequestTransfer::METADATA => [],
            QuoteRequestTransfer::IS_HIDDEN => true,
        ]))->build();

        // Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()->updateQuoteRequest($quoteRequestTransfer);
        $storedQuoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertNull($storedQuoteRequestTransfer->getLatestVersion());
        $this->assertEquals($quoteRequestTransfer->getCompanyUser(), $storedQuoteRequestTransfer->getCompanyUser());
        $this->assertEquals($quoteRequestTransfer->getStatus(), $storedQuoteRequestTransfer->getStatus());
        $this->assertEquals($quoteRequestTransfer->getIsHidden(), $storedQuoteRequestTransfer->getIsHidden());
        $this->assertEquals($quoteRequestTransfer->getValidUntil(), $storedQuoteRequestTransfer->getValidUntil());
        $this->assertEquals($quoteRequestTransfer->getMetadata(), $storedQuoteRequestTransfer->getMetadata());
    }

    /**
     * @return void
     */
    public function testGetQuoteRequestCollectionByFilterRetrievesCustomerQuoteRequestByReference(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->createQuoteRequest(
            $this->tester->createQuoteRequestVersion($this->quoteTransfer),
            $this->companyUserTransfer
        );
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
        $quoteRequestTransfer = $this->tester->createQuoteRequest(
            $this->tester->createQuoteRequestVersion($this->quoteTransfer),
            $this->companyUserTransfer
        );
        $quoteRequestFilterTransfer = (new QuoteRequestCriteriaTransfer())
            ->setIdCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference());

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->cancelQuoteRequest($quoteRequestFilterTransfer);

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
        $quoteRequestTransfer = $this->tester->createQuoteRequest(
            $this->tester->createQuoteRequestVersion($this->quoteTransfer),
            $this->companyUserTransfer
        );

        $quoteRequestFilterTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference());

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->closeQuoteRequest($quoteRequestFilterTransfer);

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            SharedQuoteRequestConfig::STATUS_CLOSED,
            $quoteRequestResponseTransfer->getQuoteRequest()->getStatus()
        );
    }

    /**
     * @return void
     */
    public function testCloseQuoteRequestNotSuccessfulWithInvalidQuoteRequestReference(): void
    {
        $quoteRequestFilterTransfer = (new QuoteRequestCriteriaTransfer())
            ->setIdCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setQuoteRequestReference(static::FAKE_QUOTE_REQUEST_VERSION_REFERENCE);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->closeQuoteRequest($quoteRequestFilterTransfer);

        // Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testCheckCheckoutQuoteRequestValidatesQuoteWithWrongQuoteRequestVersionReference(): void
    {
        // Arrange
        $this->tester->createQuoteRequest(
            $this->tester->createQuoteRequestVersion($this->quoteTransfer),
            $this->companyUserTransfer
        );
        $this->quoteTransfer->setQuoteRequestVersionReference(static::FAKE_QUOTE_REQUEST_VERSION_REFERENCE);

        // Act
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $isValid = $this->tester
            ->getFacade()
            ->checkCheckoutQuoteRequest($this->quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($isValid);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testCheckCheckoutQuoteRequestValidatesQuoteWithWrongQuoteRequestStatus(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->createQuoteRequest(
            $this->tester->createQuoteRequestVersion($this->quoteTransfer),
            $this->companyUserTransfer
        );
        $this->quoteTransfer->setQuoteRequestVersionReference(
            $quoteRequestTransfer->getLatestVersion()->getVersionReference()
        );

        // Act
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $isValid = $this->tester
            ->getFacade()
            ->checkCheckoutQuoteRequest($this->quoteTransfer, $checkoutResponseTransfer);

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
        $quoteRequestTransfer = $this->tester->createQuoteRequest(
            $this->tester->createQuoteRequestVersion($this->quoteTransfer),
            $this->companyUserTransfer
        );
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
    public function testGetQuoteRequestVersionCollectionByFilterRetrievesQuoteRequestVersionsByReference(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->createQuoteRequest(
            $this->tester->createQuoteRequestVersion($this->quoteTransfer),
            $this->companyUserTransfer
        );
        $quoteRequestVersionFilterTransfer = (new QuoteRequestVersionFilterBuilder())->build()
            ->setQuoteRequest($quoteRequestTransfer)
            ->setQuoteRequestVersionReference($quoteRequestTransfer->getLatestVersion()->getVersionReference());

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
        $quoteRequestTransfer = $this->tester->createQuoteRequest(
            $this->tester->createQuoteRequestVersion($this->quoteTransfer),
            $this->companyUserTransfer
        );
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
    public function testSendQuoteRequestToCustomerCreatesLatestVersionFromQuoteInProgress(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->createQuoteRequest(
            $this->tester->createQuoteRequestVersion($this->quoteTransfer),
            $this->companyUserTransfer
        );

        $quoteRequestTransfer->setStatus(SharedQuoteRequestConfig::STATUS_IN_PROGRESS)
            ->setIsHidden(true)
            ->setQuoteInProgress($this->quoteTransfer)
            ->setValidUntil((new DateTime())->add(new DateInterval("PT1H"))->format('Y-m-d H:i:s'));

        $this->tester->getFacade()->updateQuoteRequest($quoteRequestTransfer);

        $quoteRequestVersionFilterTransfer = (new QuoteRequestCriteriaTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference())
            ->setWithHidden(true);

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->sendQuoteRequestToCustomer($quoteRequestVersionFilterTransfer);

        $quoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals(SharedQuoteRequestConfig::STATUS_READY, $quoteRequestTransfer->getStatus());
        $this->assertFalse($quoteRequestTransfer->getIsHidden());
        $this->assertEquals(2, $quoteRequestTransfer->getLatestVersion()->getVersion());
    }

    /**
     * @return void
     */
    public function testCloseOutdatedQuoteRequestsUpdateQuoteRequestStatusToClosed(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->createQuoteRequest(
            $this->tester->createQuoteRequestVersion($this->quoteTransfer),
            $this->companyUserTransfer
        );

        $quoteRequestTransfer
            ->setStatus(SharedQuoteRequestConfig::STATUS_READY)
            ->setValidUntil((new DateTime())->sub(new DateInterval("PT1H"))->format('Y-m-d H:i:s'));

        $this->tester->getFacade()->updateQuoteRequest($quoteRequestTransfer);

        // Act
        $this->tester->getFacade()->closeOutdatedQuoteRequests();
        $storedQuoteRequestTransfer = $this->getQuoteRequestByReference($quoteRequestTransfer->getQuoteRequestReference());

        // Assert
        $this->assertEquals(SharedQuoteRequestConfig::STATUS_CLOSED, $storedQuoteRequestTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testCloseOutdatedQuoteRequestsUpdateQuoteRequestStatusToClosedWhenQuoteRequestValid(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->createQuoteRequest(
            $this->tester->createQuoteRequestVersion($this->quoteTransfer),
            $this->companyUserTransfer
        );

        $quoteRequestTransfer
            ->setStatus(SharedQuoteRequestConfig::STATUS_READY)
            ->setValidUntil((new DateTime())->add(new DateInterval("PT1H"))->format('Y-m-d H:i:s'));

        $this->tester->getFacade()->updateQuoteRequest($quoteRequestTransfer);

        // Act
        $this->tester->getFacade()->closeOutdatedQuoteRequests();
        $storedQuoteRequestTransfer = $this->getQuoteRequestByReference($quoteRequestTransfer->getQuoteRequestReference());

        // Assert
        $this->assertEquals($quoteRequestTransfer->getStatus(), $storedQuoteRequestTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testCloseOutdatedQuoteRequestsUpdateQuoteRequestStatusToClosedWhenQuoteRequestNotReady(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->createQuoteRequest(
            $this->tester->createQuoteRequestVersion($this->quoteTransfer),
            $this->companyUserTransfer
        );

        $quoteRequestTransfer->setStatus(SharedQuoteRequestConfig::STATUS_IN_PROGRESS);

        $this->tester->getFacade()->updateQuoteRequest($quoteRequestTransfer);

        // Act
        $this->tester->getFacade()->closeOutdatedQuoteRequests();
        $storedQuoteRequestTransfer = $this->getQuoteRequestByReference($quoteRequestTransfer->getQuoteRequestReference());

        // Assert
        $this->assertEquals($quoteRequestTransfer->getStatus(), $storedQuoteRequestTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testCloseOutdatedQuoteRequestsUpdateQuoteRequestStatusToClosedWhenQuoteRequestWithoutValidUntil(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->createQuoteRequest(
            $this->tester->createQuoteRequestVersion($this->quoteTransfer),
            $this->companyUserTransfer
        );

        $quoteRequestTransfer
            ->setStatus(SharedQuoteRequestConfig::STATUS_READY)
            ->setValidUntil(null);

        $this->tester->getFacade()->updateQuoteRequest($quoteRequestTransfer);

        // Act
        $this->tester->getFacade()->closeOutdatedQuoteRequests();
        $storedQuoteRequestTransfer = $this->getQuoteRequestByReference($quoteRequestTransfer->getQuoteRequestReference());

        // Assert
        $this->assertEquals($quoteRequestTransfer->getStatus(), $storedQuoteRequestTransfer->getStatus());
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
}

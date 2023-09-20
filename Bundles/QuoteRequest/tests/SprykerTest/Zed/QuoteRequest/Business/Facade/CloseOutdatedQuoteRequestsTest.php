<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteRequest\Business\Facade;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
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
 * @group CloseOutdatedQuoteRequestsTest
 * Add your own group annotations below this line
 */
class CloseOutdatedQuoteRequestsTest extends Unit
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
    public function testCloseOutdatedQuoteRequestsUpdateReadyQuoteRequestStatusToClosed(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInReadyStatus(
            $this->companyUserTransfer,
            $this->quoteTransfer,
            (new DateTime('+1 second'))->format('Y-m-d H:i:s'),
        );

        // Act
        sleep(2);
        $this->tester->getFacade()->closeOutdatedQuoteRequests();
        $storedQuoteRequestTransfer = $this->getQuoteRequestByReference($quoteRequestTransfer->getQuoteRequestReference());

        // Assert
        $this->assertSame(SharedQuoteRequestConfig::STATUS_CLOSED, $storedQuoteRequestTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testCloseOutdatedQuoteRequestsNotUpdatesQuoteRequestStatusToClosedWhenQuoteRequestNotReady(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInInProgressStatus(
            $this->companyUserTransfer,
            $this->quoteTransfer,
            (new DateTime('+1 second'))->format('Y-m-d H:i:s'),
        );

        // Act
        sleep(2);
        $this->tester->getFacade()->closeOutdatedQuoteRequests();
        $storedQuoteRequestTransfer = $this->getQuoteRequestByReference($quoteRequestTransfer->getQuoteRequestReference());

        // Assert
        $this->assertSame($storedQuoteRequestTransfer->getStatus(), SharedQuoteRequestConfig::STATUS_IN_PROGRESS);
    }

    /**
     * @return void
     */
    public function testCloseOutdatedQuoteRequestsNotUpdatesQuoteRequestStatusToClosedWhenValidUntilNotSet(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInReadyStatus($this->companyUserTransfer, $this->quoteTransfer);

        // Act
        $this->tester->getFacade()->closeOutdatedQuoteRequests();
        $storedQuoteRequestTransfer = $this->getQuoteRequestByReference($quoteRequestTransfer->getQuoteRequestReference());

        // Assert
        $this->assertSame($storedQuoteRequestTransfer->getStatus(), SharedQuoteRequestConfig::STATUS_READY);
    }

    /**
     * @return void
     */
    public function testCloseOutdatedQuoteRequestsNotUpdatesQuoteRequestStatusToClosedWhenValidUntilGreaterCurrentTime(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInReadyStatus(
            $this->companyUserTransfer,
            $this->quoteTransfer,
            (new DateTime('+1 hour'))->format('Y-m-d H:i:s'),
        );

        // Act
        $this->tester->getFacade()->closeOutdatedQuoteRequests();
        $storedQuoteRequestTransfer = $this->getQuoteRequestByReference($quoteRequestTransfer->getQuoteRequestReference());

        // Assert
        $this->assertSame($storedQuoteRequestTransfer->getStatus(), SharedQuoteRequestConfig::STATUS_READY);
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

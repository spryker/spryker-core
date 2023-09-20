<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteRequest\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
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
 * @group CloseQuoteRequestTest
 * Add your own group annotations below this line
 */
class CloseQuoteRequestTest extends Unit
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
    public function testCloseQuoteRequestChangesQuoteRequestStatusToClosed(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInDraftStatus($this->companyUserTransfer, $this->quoteTransfer);
        $quoteTransfer = (new QuoteTransfer())
            ->setQuoteRequestVersionReference($quoteRequestTransfer->getLatestVersion()->getVersionReference());

        // Act
        $this->tester
            ->getFacade()
            ->closeQuoteRequest($quoteTransfer);

        // Assert
        $quoteRequestCollection = $this->tester->getFacade()->getQuoteRequestCollectionByFilter(
            (new QuoteRequestFilterTransfer())->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference()),
        );

        $this->assertSame(
            SharedQuoteRequestConfig::STATUS_CLOSED,
            $quoteRequestCollection->getQuoteRequests()[0]->getStatus(),
        );
    }

    /**
     * @return void
     */
    public function testCloseQuoteRequestSkipQuoteRequestWhenQuoteRequestNotFound(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInDraftStatus($this->companyUserTransfer, $this->quoteTransfer);
        $quoteTransfer = (new QuoteTransfer())
            ->setQuoteRequestVersionReference(QuoteRequestBusinessTester::FAKE_QUOTE_REQUEST_REFERENCE);

        // Act
        $this->tester
            ->getFacade()
            ->closeQuoteRequest($quoteTransfer);

        // Assert
        $quoteRequestCollection = $this->tester->getFacade()->getQuoteRequestCollectionByFilter(
            (new QuoteRequestFilterTransfer())->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference()),
        );

        $this->assertSame(
            SharedQuoteRequestConfig::STATUS_DRAFT,
            $quoteRequestCollection->getQuoteRequests()[0]->getStatus(),
        );
    }
}

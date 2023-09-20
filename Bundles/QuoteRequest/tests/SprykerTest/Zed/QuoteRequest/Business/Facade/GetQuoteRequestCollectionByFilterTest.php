<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteRequest\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteRequestFilterBuilder;
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
 * @group GetQuoteRequestCollectionByFilterTest
 * Add your own group annotations below this line
 */
class GetQuoteRequestCollectionByFilterTest extends Unit
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
    public function testGetQuoteRequestCollectionByFilterRetrievesCustomerQuoteRequests(): void
    {
        // Arrange
        $this->tester->haveQuoteRequestInDraftStatus($this->companyUserTransfer, $this->quoteTransfer);
        $this->tester->haveQuoteRequestInDraftStatus($this->companyUserTransfer, $this->quoteTransfer);

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
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInDraftStatus($this->companyUserTransfer, $this->quoteTransfer);

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
}

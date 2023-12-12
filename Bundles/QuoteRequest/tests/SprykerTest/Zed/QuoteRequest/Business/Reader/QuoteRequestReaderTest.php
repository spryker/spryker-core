<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteRequest\Business\Reader;

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
 * @group Reader
 * @group QuoteRequestReaderTest
 * Add your own group annotations below this line
 */
class QuoteRequestReaderTest extends Unit
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
    public function testGetQuoteRequestCollectionByFilterReturnsQuoteRequestsOfAnonymizedCustomers(): void
    {
        // Arrange
        $this->tester->haveQuoteRequestInDraftStatus($this->companyUserTransfer, $this->quoteTransfer);
        $this->tester->haveQuoteRequestInWaitingStatus($this->companyUserTransfer, $this->quoteTransfer);

        $quoteRequestFilterTransfer = (new QuoteRequestFilterBuilder())->build()
            ->setCompanyUser($this->companyUserTransfer);

        $this->tester->getLocator()->customer()->facade()->anonymizeCustomer(
            $this->companyUserTransfer->getCustomerOrFail(),
        );

        // Act
        $quoteRequestTransfers = $this->tester
            ->getFacade()
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer)
            ->getQuoteRequests();

        // Assert
        $this->assertCount(2, $quoteRequestTransfers);
        foreach ($quoteRequestTransfers as $quoteRequestTransfer) {
            $this->assertNotNull($quoteRequestTransfer->getCompanyUserOrFail()->getCustomerOrFail()->getAnonymizedAt());
            $this->assertNotNull($quoteRequestTransfer->getCompanyUserOrFail()->getCustomerOrFail()->getEmail());
        }
    }
}

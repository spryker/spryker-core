<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteRequest\Business\Facade;

use Codeception\Test\Unit;
use SprykerTest\Zed\QuoteRequest\QuoteRequestBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group QuoteRequest
 * @group Business
 * @group Facade
 * @group DeleteQuoteRequestsByIdCompanyUserTest
 * Add your own group annotations below this line
 */
class DeleteQuoteRequestsByIdCompanyUserTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\QuoteRequest\QuoteRequestBusinessTester
     */
    protected QuoteRequestBusinessTester $tester;

    /**
     * @return void
     */
    public function testDeleteQuoteRequestsForCompanyUserWillDeleteAllAssignedQuoteRequests(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $quoteRequestTransfer = $this->tester->haveQuoteRequestInInProgressStatus(
            $this->tester->createCompanyUser($customerTransfer),
            $this->tester->createQuoteWithCustomer($customerTransfer),
        );

        // Act
        $this->tester->getFacade()->deleteQuoteRequestsByIdCompanyUser(
            $quoteRequestTransfer->getCompanyUser()->getIdCompanyUser(),
        );
        $quoteRequestCollection = $this->tester->getFacade()->getQuoteRequestCollectionByFilter(
            $this->tester->createFilterTransfer($quoteRequestTransfer),
        );

        // Assert
        $this->assertSame(0, $quoteRequestCollection->getQuoteRequests()->count());
    }
}

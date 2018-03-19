<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SharedCart\Business\SharedCartFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group SharedCart
 * @group Business
 * @group SharedCartFacade
 * @group QuoteShareTest
 * Add your own group annotations below this line
 */
class QuoteShareTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SharedCart\SharedCartBusinessTester
     */
    protected $tester;
    
    /**
     * @return void
     */
    public function testCompanyUserShouldNotHaveAccessToForeignCartsByDefault()
    {
        // Arrange
        $customerTransfer1 = $this->tester->haveCustomer();
        $quoteTransfer = $this->tester->createQuote($customerTransfer1);

        $customerTransfer2 = $this->tester->haveCustomer();
        $companyUserTransfer = $this->tester->createCompanyUser($customerTransfer2);

        // Act
        $actualQuoteTransfer = $this->tester->getLocator()->multiCart()->facade()->findQuote($companyUserTransfer, $quoteTransfer->getIdQuote());

        // Assert
        $this->assertNull($actualQuoteTransfer, 'Company user shouldn\'t have been able to read the quote from database.');
    }

    /**
     * @return void
     */
    public function testShareCartWithCompanyUserShouldGiveAccessToRead()
    {
        // Arrange
        $customerTransfer1 = $this->tester->haveCustomer();
        $quoteTransfer = $this->tester->createQuote($customerTransfer1);

        $customerTransfer2 = $this->tester->haveCustomer();
        $companyUserTransfer = $this->tester->createCompanyUser($customerTransfer2);
        $quoteCompanyUserPermissionGroupTransfer = $this->tester->createQuoteCompanyUserPermissionGroupTransfer($companyUserTransfer);
        $quoteShareRequestTransfer = $this->tester->createQuoteShareRequestTransfer($quoteTransfer, $quoteCompanyUserPermissionGroupTransfer);

        // Act
        $this->tester->getFacade()->shareQuote($quoteShareRequestTransfer);

        $actualQuoteTransfer = $this->tester->getLocator()->multiCart()->facade()->findQuote($companyUserTransfer, $quoteTransfer->getIdQuote());

        // Assert
        $this->assertInstanceOf(QuoteTransfer::class, $actualQuoteTransfer, 'Company user should have been able to read the quote from database.');
    }

    /**
     * @return void
     */
    public function testRemoveCompanyUserPermissionGroupShouldDisallowToRead()
    {
        // Arrange
        $customerTransfer1 = $this->tester->haveCustomer();
        $quoteTransfer = $this->tester->createQuote($customerTransfer1);

        $customerTransfer2 = $this->tester->haveCustomer();
        $companyUserTransfer = $this->tester->createCompanyUser($customerTransfer2);
        $quoteCompanyUserPermissionGroupTransfer = $this->tester->createQuoteCompanyUserPermissionGroupTransfer($companyUserTransfer);
        $quoteShareRequestTransfer = $this->tester->createQuoteShareRequestTransfer($quoteTransfer, $quoteCompanyUserPermissionGroupTransfer);

        $this->tester->getFacade()->shareQuote($quoteShareRequestTransfer);

        // Act
        $this->tester->getFacade()->removeCompanyUserQuotePermissionGroup($companyUserTransfer);

        $actualQuoteTransfer = $this->tester->getLocator()->multiCart()->facade()->findQuote($companyUserTransfer, $quoteTransfer->getIdQuote());

        // Assert
        $this->assertNull($actualQuoteTransfer, 'Company user shouldn\'t have been able to read the quote from database.');
    }
}

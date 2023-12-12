<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUser\Business\Facade;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\DataBuilder\CompanyUserCriteriaFilterBuilder;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerTest\Zed\CompanyUser\CompanyUserBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyUser
 * @group Business
 * @group Facade
 * @group GetRawCompanyUsersByCriteriaTest
 * Add your own group annotations below this line
 */
class GetRawCompanyUsersByCriteriaTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CompanyUser\CompanyUserBusinessTester
     */
    protected CompanyUserBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureCompanyUserTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testShouldReturnTransfer(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserTransfer();
        $companyUserCriteriaFilterTransfer = (new CompanyUserCriteriaFilterBuilder([
            CompanyUserCriteriaFilterTransfer::ID_COMPANY => $companyUserTransfer->getFkCompany(),
        ]))->build();

        // Act
        $foundCompanyUserTransfer = $this->tester->getFacade()
            ->getRawCompanyUsersByCriteria($companyUserCriteriaFilterTransfer)
            ->getCompanyUsers()
            ->offsetGet(0);

        // Assert
        $this->assertNotEmpty($foundCompanyUserTransfer);
        $this->assertSame($companyUserTransfer->getIdCompanyUser(), $foundCompanyUserTransfer->getIdCompanyUser());
    }

    /**
     * @return void
     */
    public function testIgnoresAnonymizedCustomersByDefault(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder([
            CustomerTransfer::ANONYMIZED_AT => new DateTime(),
        ]))->build();

        $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::CUSTOMER => $customerTransfer],
        );

        $customerTransfer = (new CustomerBuilder())->build();

        $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::CUSTOMER => $customerTransfer],
        );

        // Act
        $companyUserTransfers = $this->tester
            ->getFacade()
            ->getRawCompanyUsersByCriteria(new CompanyUserCriteriaFilterTransfer())
            ->getCompanyUsers();

        // Assert
        $this->assertCount(1, $companyUserTransfers);
        $this->assertNull($companyUserTransfers->getIterator()->current()->getCustomer()->getAnonymizedAt());
    }

    /**
     * @return void
     */
    public function testIncludesAnonymizedCustomersWhenRequested(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder([
            CustomerTransfer::ANONYMIZED_AT => new DateTime(),
        ]))->build();

        $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::CUSTOMER => $customerTransfer],
        );

        $customerTransfer = (new CustomerBuilder())->build();

        $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::CUSTOMER => $customerTransfer],
        );

        $companyUserCriteriaFilterTransfer = (new CompanyUserCriteriaFilterTransfer())->setIncludeAnonymizedCustomers(true);

        // Act
        $companyUserCollectionTransfer = $this->tester
            ->getFacade()
            ->getRawCompanyUsersByCriteria($companyUserCriteriaFilterTransfer);

        // Assert
        $this->assertCount(2, $companyUserCollectionTransfer->getCompanyUsers());
    }
}

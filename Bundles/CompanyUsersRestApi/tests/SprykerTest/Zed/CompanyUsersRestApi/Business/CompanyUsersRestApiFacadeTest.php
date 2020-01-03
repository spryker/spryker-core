<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUsersRestApi\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerIdentifierTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyUsersRestApi
 * @group Business
 * @group Facade
 * @group CompanyUsersRestApiFacadeTest
 * Add your own group annotations below this line
 */
class CompanyUsersRestApiFacadeTest extends Test
{
    /**
     * @var \SprykerTest\Zed\CompanyUsersRestApi\CompanyUsersRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandCustomerIdentifierWhereCustomerSetCompanyUser(): void
    {
        // Assign
        $customerTransfer = $this->tester->haveCustomer();
        $companyTransfer = $this->tester->haveCompany();

        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
        ]);

        $customerTransfer->setCompanyUserTransfer($companyUserTransfer);

        // Act
        $expandedCustomerIdentifierTransfer = $this->tester->getFacade()->expandCustomerIdentifier(
            (new CustomerIdentifierTransfer()),
            $customerTransfer
        );

        // Assert
        $this->assertEquals($customerTransfer->getCompanyUserTransfer()->getUuid(), $expandedCustomerIdentifierTransfer->getIdCompanyUser());
    }

    /**
     * @return void
     */
    public function testExpandCustomerIdentifierWhereCustomerNotSetCompanyUser(): void
    {
        // Assign
        $customerTransfer = $this->tester->haveCustomer();

        // Act
        $expandedCustomerIdentifierTransfer = $this->tester->getFacade()->expandCustomerIdentifier(
            (new CustomerIdentifierTransfer()),
            $customerTransfer
        );

        // Assert
        $this->assertNull($expandedCustomerIdentifierTransfer->getIdCompanyUser());
    }

    /**
     * @return void
     */
    public function testGetCompanyUserCollectionWillReturnCollectionOfCompanyUsers(): void
    {
        // Assign
        $customerTransfer = $this->tester->haveCustomer();
        $companyTransfer = $this->tester->haveCompany();
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer,

        ]);
        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::COMPANY => $companyTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
            CompanyUserTransfer::COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer,
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
        ]);
        $companyUserCriteriaFilterTransfer = (new CompanyUserCriteriaFilterTransfer())
            ->setIdCompany($companyTransfer->getIdCompany());

        // Act
        $companyUserCollectionTransfer = $this->tester->getFacade()->getCompanyUserCollection(
            $companyUserCriteriaFilterTransfer
        );

        // Assert
        $this->assertCount(1, $companyUserCollectionTransfer->getCompanyUsers());
        /** @var \Generated\Shared\Transfer\CompanyUserTransfer $expectedCompanyUserTransfer */
        $expectedCompanyUserTransfer = $companyUserCollectionTransfer->getCompanyUsers()->offsetGet(0);
        $this->assertEquals($companyUserTransfer->getIdCompanyUser(), $expectedCompanyUserTransfer->getIdCompanyUser());
        $this->assertEquals($companyUserTransfer->getFkCompany(), $expectedCompanyUserTransfer->getFkCompany());
        $this->assertEquals($companyUserTransfer->getFkCompanyBusinessUnit(), $expectedCompanyUserTransfer->getFkCompanyBusinessUnit());
        $this->assertEquals($companyUserTransfer->getFkCustomer(), $expectedCompanyUserTransfer->getFkCustomer());
    }

    /**
     * @return void
     */
    public function testGetCompanyUserCollectionWillReturnEmptyCollectionWhenCompanyUsersNotFound(): void
    {
        // Assign
        $companyTransfer = $this->tester->haveCompany();
        $companyUserCriteriaFilterTransfer = (new CompanyUserCriteriaFilterTransfer())
            ->setIdCompany($companyTransfer->getIdCompany());

        // Act
        $companyUserCollectionTransfer = $this->tester->getFacade()->getCompanyUserCollection(
            $companyUserCriteriaFilterTransfer
        );

        // Assert
        $this->assertCount(0, $companyUserCollectionTransfer->getCompanyUsers());
    }
}

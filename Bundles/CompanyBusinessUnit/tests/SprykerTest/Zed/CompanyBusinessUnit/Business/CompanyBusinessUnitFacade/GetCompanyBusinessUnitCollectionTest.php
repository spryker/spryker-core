<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacade;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyBusinessUnit
 * @group Business
 * @group CompanyBusinessUnitFacade
 * @group GetCompanyBusinessUnitCollectionTest
 * Add your own group annotations below this line
 */
class GetCompanyBusinessUnitCollectionTest extends Test
{
    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnit\CompanyBusinessUnitTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetCompanyBusinessUnitCollectionReturnsTransfersCollectionByIdCompany(): void
    {
        // Arrange
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        $companyBusinessUnitCriteriaFilterTransfer = (new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setIdCompany($companyBusinessUnitTransfer->getFkCompany())
            ->setWithoutExpanders(true);

        // Act
        $companyBusinessUnitCollection = $this->tester
            ->getFacade()
            ->getCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer);

        // Assert
        $this->assertCount(1, $companyBusinessUnitCollection->getCompanyBusinessUnits());
    }

    /**
     * @return void
     */
    public function testGetCompanyBusinessUnitCollectionReturnsEmptyCollectionByFakeIdCompany(): void
    {
        // Arrange
        $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        $companyBusinessUnitCriteriaFilterTransfer = (new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setIdCompany(-1)
            ->setWithoutExpanders(true);

        // Act
        $companyBusinessUnitCollection = $this->tester
            ->getFacade()
            ->getCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer);

        // Assert
        $this->assertEmpty($companyBusinessUnitCollection->getCompanyBusinessUnits());
    }

    /**
     * @return void
     */
    public function testGetCompanyBusinessUnitCollectionReturnsTransfersCollectionByIdCompanyUser(): void
    {
        // Arrange
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $this->tester->haveCustomer(),
            CompanyUserTransfer::FK_COMPANY => $companyBusinessUnitTransfer->getFkCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
        ]);

        $companyBusinessUnitCriteriaFilterTransfer = (new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setIdCompanyUser($companyUserTransfer->getIdCompanyUser())
            ->setWithoutExpanders(true);

        // Act
        $companyBusinessUnitCollection = $this->tester
            ->getFacade()
            ->getCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer);

        // Assert
        $this->assertCount(1, $companyBusinessUnitCollection->getCompanyBusinessUnits());
    }

    /**
     * @return void
     */
    public function testGetCompanyBusinessUnitCollectionReturnsEmptyCollectionByFakeIdCompanyUser(): void
    {
        // Arrange
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $this->tester->haveCustomer(),
            CompanyUserTransfer::FK_COMPANY => $companyBusinessUnitTransfer->getFkCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
        ]);

        $companyBusinessUnitCriteriaFilterTransfer = (new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setIdCompany(-1)
            ->setWithoutExpanders(true);

        // Act
        $companyBusinessUnitCollection = $this->tester
            ->getFacade()
            ->getCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer);

        // Assert
        $this->assertEmpty($companyBusinessUnitCollection->getCompanyBusinessUnits());
    }

    /**
     * @return void
     */
    public function testGetCompanyBusinessUnitCollectionReturnsAllAvailableTransfers(): void
    {
        // Arrange
        $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);
        $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        $companyBusinessUnitCriteriaFilterTransfer = (new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setWithoutExpanders(true);

        // Act
        $companyBusinessUnitCollection = $this->tester
            ->getFacade()
            ->getCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer);

        // Assert
        $this->assertCount($this->tester->getBusinessUnitsCount(), $companyBusinessUnitCollection->getCompanyBusinessUnits());
    }
}

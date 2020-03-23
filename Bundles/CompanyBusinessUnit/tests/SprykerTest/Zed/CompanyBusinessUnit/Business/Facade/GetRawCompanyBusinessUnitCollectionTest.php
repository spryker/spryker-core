<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnit\Business\Facade;

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
 * @group Facade
 * @group GetRawCompanyBusinessUnitCollectionTest
 * Add your own group annotations below this line
 */
class GetRawCompanyBusinessUnitCollectionTest extends Test
{
    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnit\CompanyBusinessUnitTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetRawCompanyBusinessUnitCollectionReturnsTransfersCollectionByIdCompany(): void
    {
        // Arrange
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        $companyBusinessUnitCriteriaFilterTransfer = (new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setIdCompany($companyBusinessUnitTransfer->getFkCompany());

        // Act
        $companyBusinessUnitCollection = $this->tester
            ->getFacade()
            ->getRawCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer);

        // Assert
        $this->assertCount(1, $companyBusinessUnitCollection->getCompanyBusinessUnits());
    }

    /**
     * @return void
     */
    public function testGetRawCompanyBusinessUnitCollectionReturnsEmptyCollectionByFakeIdCompany(): void
    {
        // Arrange
        $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        $companyBusinessUnitCriteriaFilterTransfer = (new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setIdCompany(-1);

        // Act
        $companyBusinessUnitCollection = $this->tester
            ->getFacade()
            ->getRawCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer);

        // Assert
        $this->assertEmpty($companyBusinessUnitCollection->getCompanyBusinessUnits());
    }

    /**
     * @return void
     */
    public function testGetRawCompanyBusinessUnitCollectionReturnsTransfersCollectionByIdCompanyUser(): void
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
            ->setIdCompanyUser($companyUserTransfer->getIdCompanyUser());

        // Act
        $companyBusinessUnitCollection = $this->tester
            ->getFacade()
            ->getRawCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer);

        // Assert
        $this->assertCount(1, $companyBusinessUnitCollection->getCompanyBusinessUnits());
    }

    /**
     * @return void
     */
    public function testGetRawCompanyBusinessUnitCollectionReturnsEmptyCollectionByFakeIdCompanyUser(): void
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
            ->setIdCompany(-1);

        // Act
        $companyBusinessUnitCollection = $this->tester
            ->getFacade()
            ->getRawCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer);

        // Assert
        $this->assertEmpty($companyBusinessUnitCollection->getCompanyBusinessUnits());
    }

    /**
     * @return void
     */
    public function testGetRawCompanyBusinessUnitCollectionReturnsAllAvailableTransfers(): void
    {
        // Arrange
        $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);
        $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        // Act
        $companyBusinessUnitCollection = $this->tester
            ->getFacade()
            ->getRawCompanyBusinessUnitCollection(new CompanyBusinessUnitCriteriaFilterTransfer());

        // Assert
        $this->assertCount($this->tester->getBusinessUnitsCount(), $companyBusinessUnitCollection->getCompanyBusinessUnits());
    }
}

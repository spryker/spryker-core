<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnitGui\Business\CompanyBusinessUnitGuiFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CompanyBusinessUnitGui
 * @group Business
 * @group CompanyBusinessUnitGuiFacade
 * @group Facade
 * @group CompanyBusinessUnitGuiFacadeTest
 * Add your own group annotations below this line
 */
class CompanyBusinessUnitGuiFacadeTest extends Unit
{
    use LocatorHelperTrait;

    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnitGui\CompanyBusinessUnitGuiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindCompanyBusinessUnitNameFindCompanyBusinessUnitNameByIdCompanyUser(): void
    {
        // Arrange
        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        $customerTransfer = $this->tester->haveCustomer();
        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $businessUnitTransfer->getFkCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $businessUnitTransfer->getIdCompanyBusinessUnit(),
        ]);

        // Act
        $companyBusinessUnitName = $this->tester
            ->getFacade()
            ->findCompanyBusinessUnitName($companyUserTransfer->getIdCompanyUser());

        // Assert
        $this->assertEquals($businessUnitTransfer->getName(), $companyBusinessUnitName);
    }
}

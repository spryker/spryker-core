<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUser\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CompanyUser
 * @group Business
 * @group Facade
 * @group CompanyUserFacadeTest
 * Add your own group annotations below this line
 */
class CompanyUserFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CompanyUser\CompanyUserBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetCountOfActiveCompanyUsersByCustomerId(): void
    {
        //Arrange
        $expectedCompanyUserAmount = 1;
        $customer = $this->tester->haveCustomer();
        $activeCompany = $this->tester->haveCompany(['isActive' => true]);
        $inactiveCompany = $this->tester->haveCompany(['isActive' => false]);

        $seedDataWithActiveCompany = [
            CompanyUserTransfer::CUSTOMER => $customer,
            CompanyUserTransfer::FK_COMPANY => $activeCompany->getIdCompany(),
        ];
        $seedDataWithInactiveCompany = [
            CompanyUserTransfer::CUSTOMER => $customer,
            CompanyUserTransfer::FK_COMPANY => $inactiveCompany->getIdCompany(),
        ];
        $this->tester->haveCompanyUser($seedDataWithActiveCompany);
        $this->tester->haveCompanyUser($seedDataWithInactiveCompany);

        //Act
        $actualCompanyUserAmount = $this->tester->getFacade()->getCountOfActiveCompanyUsersByCustomerId($customer);

        //Assert
        $this->tester->assertEquals($expectedCompanyUserAmount, $actualCompanyUserAmount);
    }
}

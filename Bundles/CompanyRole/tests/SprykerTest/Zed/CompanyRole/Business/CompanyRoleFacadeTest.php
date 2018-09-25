<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyRole\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CompanyResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CompanyRole
 * @group Business
 * @group Facade
 * @group CompanyRoleFacadeTest
 * Add your own group annotations below this line
 */
class CompanyRoleFacadeTest extends Test
{
    /**
     * @var \SprykerTest\Zed\CompanyRole\CompanyRoleBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetCompanyRoleByIdShouldReturnCorrectData(): void
    {
        // Prepare
        $companyTransfer = $this->tester->haveCompany();
        $existingCompanyRole = $this->tester->haveCompanyRole([
            CompanyRoleTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        // Action
        $resultCompanyRoleTransfer = $this->getFacade()->getCompanyRoleById(
            (new CompanyRoleTransfer())
                ->setIdCompanyRole($existingCompanyRole->getIdCompanyRole())
        );

        // Assert
        $this->assertEquals($existingCompanyRole->getName(), $resultCompanyRoleTransfer->getName());
    }

    /**
     * @return void
     */
    public function testCreateCompanyRoleShouldReturnIsSuccess(): void
    {
        // Prepare
        $companyTransfer = $this->tester->haveCompany();
        $companyRoleTransfer = $this->tester->haveCompanyRole([
            CompanyRoleTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        // Action
        $companyRoleResponseTransfer = $this->getFacade()->create($companyRoleTransfer);

        // Assert
        $this->assertTrue($companyRoleResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testCreateCompanyRoleByCompanyShouldReturnIsSuccess(): void
    {
        // Prepare
        $companyTransfer = $this->tester->haveCompany();
        $companyResponseTransfer = (new CompanyResponseTransfer())
            ->setIsSuccessful(true)
            ->setCompanyTransfer($companyTransfer);

        // Action
        $companyResponseTransfer = $this->getFacade()->createByCompany($companyResponseTransfer);

        // Assert
        $this->assertTrue($companyResponseTransfer->getIsSuccessful());
        $this->assertEmpty($companyResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testUpdateCompanyRoleShouldUpdateSuccessfully(): void
    {
        // Prepare
        $companyTransfer = $this->tester->haveCompany();
        $existingCompanyRole = $this->tester->haveCompanyRole([
            CompanyRoleTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyRoleTransfer::NAME => 'Updated Name',
        ]);
        $companyRoleTransfer = $this->tester->haveCompanyRole([
            CompanyRoleTransfer::ID_COMPANY_ROLE => $existingCompanyRole->getIdCompanyRole(),
        ]);

        // Action
        $this->getFacade()->update($existingCompanyRole);
        $resultCompanyRoleTransfer = $this->getFacade()->getCompanyRoleById($companyRoleTransfer);

        // Assert
        $this->assertEquals('Updated Name', $resultCompanyRoleTransfer->getName());
    }

    /**
     * @return void
     */
    public function testDeleteCompanyRoleShouldReturnIsSuccess(): void
    {
        // Prepare
        $companyTransfer = $this->tester->haveCompany();
        $companyResponseTransfer = $this->tester->haveCompanyRole([
            CompanyRoleTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        // Action
        $companyResponseTransfer = $this->getFacade()->delete($companyResponseTransfer);

        // Assert
        $this->assertTrue($companyResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testFindDefaultCompanyRoleByIdCompanyReturnNullIfNonFound(): void
    {
        // Prepare
        $companyTransfer = $this->tester->haveCompany();
        $companyRoleTransfer = $this->tester->haveCompanyRole([
            CompanyRoleTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyRoleTransfer::IS_DEFAULT => false,
        ]);

        // Action
        $resultCompanyRoleTransfer = $this->getFacade()
            ->findDefaultCompanyRoleByIdCompany($companyTransfer->getIdCompany());

        // Assert
        $this->assertNull($resultCompanyRoleTransfer);
    }

    /**
     * @return void
     */
    public function testFindDefaultCompanyRoleByIdCompany(): void
    {
        // Prepare
        $companyTransfer = $this->tester->haveCompany();
        $companyRoleTransfer = $this->tester->haveCompanyRole([
            CompanyRoleTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyRoleTransfer::IS_DEFAULT => true,
        ]);

        // Action
        $resultCompanyRoleTransfer = $this->getFacade()
            ->findDefaultCompanyRoleByIdCompany($companyTransfer->getIdCompany());

        // Assert
        $this->assertNotNull($resultCompanyRoleTransfer);
        $this->assertSame($resultCompanyRoleTransfer->getIdCompanyRole(), $companyRoleTransfer->getIdCompanyRole());
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\CompanyRole\Business\CompanyRoleFacadeInterface
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}

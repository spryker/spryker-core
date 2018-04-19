<?php

namespace SprykerTest\Zed\CompanyRole\Business;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\CompanyResponseBuilder;
use Generated\Shared\DataBuilder\CompanyRoleBuilder;
use Generated\Shared\Transfer\CompanyResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyTransfer;

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
    public function testGetCompanyRoleByIdShouldReturnCorrectData()
    {
        $existingCompanyRole = $this->haveCompanyRole();
        $companyRoleTransfer = (new CompanyRoleBuilder([
            CompanyRoleTransfer::ID_COMPANY_ROLE => $existingCompanyRole->getIdCompanyRole(),
        ]))->build();

        $resultCompanyRoleTransfer = $this->getFacade()->getCompanyRoleById($companyRoleTransfer);

        $this->tester->assertEquals($existingCompanyRole->getName(), $resultCompanyRoleTransfer->getName());
    }

    /**
     * @return void
     */
    public function testCreateCompanyRoleShouldReturnIsSuccess()
    {
        $companyRoleTransfer = (new CompanyRoleBuilder([
            CompanyRoleTransfer::FK_COMPANY => $this->haveCompany()->getIdCompany(),
        ]))->build();

        $companyRoleResponseTransfer = $this->getFacade()->create($companyRoleTransfer);

        $this->tester->assertTrue($companyRoleResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testCreateCompanyRoleByCompanyShouldReturnIsSuccess()
    {
        $companyResponseTransfer = (new CompanyResponseBuilder([
            CompanyResponseTransfer::COMPANY_TRANSFER => $this->haveCompany(),
            CompanyResponseTransfer::IS_SUCCESSFUL => true,
        ]))->build();

        $companyResponseTransfer = $this->getFacade()->createByCompany($companyResponseTransfer);

        $this->tester->assertTrue($companyResponseTransfer->getIsSuccessful());
        $this->tester->assertEmpty($companyResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testUpdateCompanyRoleShouldUpdateSuccessfully()
    {
        $existingCompanyRole = $this->haveCompanyRole()->setName('Updated Name');
        $companyRoleTransfer = (new CompanyRoleBuilder([
            CompanyRoleTransfer::ID_COMPANY_ROLE => $existingCompanyRole->getIdCompanyRole(),
        ]))->build();

        $this->getFacade()->update($existingCompanyRole);
        $resultCompanyRoleTransfer = $this->getFacade()->getCompanyRoleById($companyRoleTransfer);

        $this->tester->assertEquals('Updated Name', $resultCompanyRoleTransfer->getName());
    }

    /**
     * @return void
     */
    public function testDeleteCompanyRoleShouldReturnIsSuccess()
    {
        $companyResponseTransfer = $this->haveCompanyRole();

        $companyResponseTransfer = $this->getFacade()->delete($companyResponseTransfer);

        $this->tester->assertTrue($companyResponseTransfer->getIsSuccessful());
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    protected function haveCompany(): CompanyTransfer
    {
        return $this->tester->haveCompany();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    protected function haveCompanyRole(array $seedData = []): CompanyRoleTransfer
    {
        return $this->tester->haveCompanyRole($seedData);
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}

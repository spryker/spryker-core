<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyRole\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CompanyResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Shared\CompanyUser\Plugin\AddCompanyUserPermissionPlugin;
use Spryker\Zed\CompanyRole\Communication\Plugin\PermissionStoragePlugin;
use Spryker\Zed\Permission\PermissionDependencyProvider;

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
    public function testGetCompanyUserIdsByPermissionKeyReturnsCorrectData(): void
    {
        //Assign
        $this->tester->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION, [
            new AddCompanyUserPermissionPlugin(),
        ]);
        $this->tester->preparePermissionStorageDependency(new PermissionStoragePlugin());

        $permissionFacade = $this->tester->getLocator()->permission()->facade();
        $permissionFacade->syncPermissionPlugins();

        $permissionCollectionTransfer = (new PermissionCollectionTransfer())
            ->addPermission($permissionFacade->findPermissionByKey(AddCompanyUserPermissionPlugin::KEY));

        $companyWithPermissionTransfer = $this->tester->haveCompany();
        $companyRoleWithPermissionTransfer = $this->tester->haveCompanyRole([
            CompanyRoleTransfer::FK_COMPANY => $companyWithPermissionTransfer->getIdCompany(),
            CompanyRoleTransfer::PERMISSION_COLLECTION => $permissionCollectionTransfer,
        ]);

        $companyRoleCollection = (new CompanyRoleCollectionTransfer())
            ->addRole($companyRoleWithPermissionTransfer);

        $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $this->tester->haveCustomer(),
            CompanyUserTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        $companyUserWithPermissionTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $this->tester->haveCustomer(),
            CompanyUserTransfer::FK_COMPANY => $companyWithPermissionTransfer->getIdCompany(),
            CompanyUserTransfer::COMPANY_ROLE_COLLECTION => $companyRoleCollection,
        ]);

        $this->tester->assignCompanyRolesToCompanyUser($companyUserWithPermissionTransfer);

        //Act
        $companyUserIds = $this->getFacade()->getCompanyUserIdsByPermissionKey(AddCompanyUserPermissionPlugin::KEY);

        //Assert
        $this->assertEquals([$companyUserWithPermissionTransfer->getIdCompanyUser()], $companyUserIds);
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
        $this->tester->preparePermissionStorageDependency(new PermissionStoragePlugin());

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
     * @return void
     */
    public function testFindCompanyRoleByIdShouldReturnCorrectDataIfCompanyRoleExists(): void
    {
        // Prepare
        $companyTransfer = $this->tester->haveCompany();
        $existingCompanyRole = $this->tester->haveCompanyRole([
            CompanyRoleTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        // Action
        $resultCompanyRoleTransfer = $this->getFacade()->findCompanyRoleById(
            (new CompanyRoleTransfer())
                ->setIdCompanyRole($existingCompanyRole->getIdCompanyRole())
        );

        // Assert
        $this->assertEquals($existingCompanyRole->getName(), $resultCompanyRoleTransfer->getName());
    }

    /**
     * @return void
     */
    public function testFindCompanyRoleByIdShouldReturnNullIfCompanyRoleDoesNotExist(): void
    {
        // Prepare
        $companyRoleFacade = $this->getFacade();
        $notExistingCompanyRole = (new CompanyRoleTransfer())->setIdCompanyRole(-1);

        // Action
        $resultCompanyRole = $companyRoleFacade->findCompanyRoleById($notExistingCompanyRole);

        // Assert
        $this->assertNull($resultCompanyRole);
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\CompanyRole\Business\CompanyRoleFacadeInterface
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}

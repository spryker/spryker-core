<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyRole\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CompanyResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Shared\CompanyUser\Plugin\AddCompanyUserPermissionPlugin;
use Spryker\Zed\CompanyRole\Communication\Plugin\PermissionStoragePlugin;

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
    protected const CONFIGURATION = ['testKey' => 'testValue'];
    protected const TEST_NAME = 'Test Name';

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
        $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $this->tester->haveCustomer(),
            CompanyUserTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);
        $companyUserWithPermissionTransfer = $this->tester->getCompanyUserWithPermission();

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
        $companyRoleTransfer = $this->tester->getCompanyRoleTransfer([
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
            CompanyRoleTransfer::NAME => static::TEST_NAME,
        ]);
        $companyRoleTransfer = $this->tester->haveCompanyRole([
            CompanyRoleTransfer::ID_COMPANY_ROLE => $existingCompanyRole->getIdCompanyRole(),
        ]);

        // Action
        $this->getFacade()->update($existingCompanyRole);
        $resultCompanyRoleTransfer = $this->getFacade()->getCompanyRoleById($companyRoleTransfer);

        // Assert
        $this->assertEquals(static::TEST_NAME, $resultCompanyRoleTransfer->getName());
    }

    /**
     * @return void
     */
    public function testDeleteCompanyRoleShouldReturnIsSuccess(): void
    {
        // Prepare
        $companyTransfer = $this->tester->haveCompany();
        $companyRoleTransfer = $this->tester->haveCompanyRole([
            CompanyRoleTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        // Action
        $companyRoleResponseTransfer = $this->getFacade()->delete($companyRoleTransfer);

        // Assert
        $this->assertTrue($companyRoleResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testFindDefaultCompanyRoleByIdCompanyReturnNullIfNonFound(): void
    {
        // Prepare
        $companyTransfer = $this->tester->haveCompany();
        $this->tester->haveCompanyRole([
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
        $notExistingCompanyRole = (new CompanyRoleTransfer())->setIdCompanyRole(-1);

        // Action
        $resultCompanyRole = $this->getFacade()->findCompanyRoleById($notExistingCompanyRole);

        // Assert
        $this->assertNull($resultCompanyRole);
    }

    /**
     * @return void
     */
    public function testFindCompanyRolesShouldReturnCollection(): void
    {
        // Arrange
        $this->tester->getCompanyRoleWithPermission();

        // Act
        $companyRoleCollectionTransfer = $this->getFacade()->findCompanyRoles();

        // Assert
        $this->assertGreaterThan(0, $companyRoleCollectionTransfer->getRoles()->count());
    }

    /**
     * @return void
     */
    public function testGetCompanyRoleCollectionShouldReturnCollectionByIdCompanyCriteria(): void
    {
        // Arrange
        $companyRoleTransfer = $this->tester->getCompanyRoleWithPermission();
        $criteriaFilterTransfer = (new CompanyRoleCriteriaFilterTransfer())
            ->setIdCompany($companyRoleTransfer->getFkCompany());

        // Act
        $companyRoleCollectionTransfer = $this->getFacade()->getCompanyRoleCollection($criteriaFilterTransfer);

        // Assert
        $this->assertGreaterThan(0, $companyRoleCollectionTransfer->getRoles()->count());
    }

    /**
     * @return void
     */
    public function testGetCompanyRoleCollectionShouldReturnCollectionByIdCompanyUserCriteria(): void
    {
        // Arrange
        $companyUserWithPermissionTransfer = $this->tester->getCompanyUserWithPermission();
        $criteriaFilterTransfer = (new CompanyRoleCriteriaFilterTransfer())
            ->setIdCompanyUser($companyUserWithPermissionTransfer->getIdCompanyUser());

        // Act
        $companyRoleCollectionTransfer = $this->getFacade()->getCompanyRoleCollection($criteriaFilterTransfer);

        // Assert
        $this->assertGreaterThan(0, $companyRoleCollectionTransfer->getRoles()->count());
    }

    /**
     * @return void
     */
    public function testFindCompanyRolePermissionsShouldReturnCollection(): void
    {
        // Arrange
        $companyRoleTransfer = $this->tester->getCompanyRoleWithPermission();

        // Act
        $permissionCollectionTransfer = $this->getFacade()->findCompanyRolePermissions($companyRoleTransfer->getIdCompanyRole());

        // Assert
        $this->assertGreaterThan(0, $permissionCollectionTransfer->getPermissions()->count());
    }

    /**
     * @return void
     */
    public function testFindPermissionsByIdCompanyUserShouldReturnCollection(): void
    {
        // Arrange
        $companyUserWithPermissionTransfer = $this->tester->getCompanyUserWithPermission();

        // Act
        $permissionCollectionTransfer = $this->getFacade()->findPermissionsByIdCompanyUser($companyUserWithPermissionTransfer->getIdCompanyUser());

        // Assert
        $this->assertGreaterThan(0, $permissionCollectionTransfer->getPermissions()->count());
    }

    /**
     * @return void
     */
    public function testUpdateCompanyRolePermissionShouldPersistNewConfiguration(): void
    {
        // Arrange
        $companyRoleTransfer = $this->tester->getCompanyRoleWithPermission();
        $idPermission = $companyRoleTransfer->getPermissionCollection()->getPermissions()->offsetGet(0)->getIdPermission();
        $idCompanyRole = $companyRoleTransfer->getIdCompanyRole();
        $permissionTransfer = $this->getFacade()->findPermissionByIdCompanyRoleByIdPermission($idCompanyRole, $idPermission);

        // Act
        $permissionTransfer->setConfiguration(static::CONFIGURATION);
        $this->getFacade()->updateCompanyRolePermission($permissionTransfer);

        // Assert
        $permissionTransferUpdated = $this->getFacade()->findPermissionByIdCompanyRoleByIdPermission($idCompanyRole, $idPermission);
        $this->assertEquals(static::CONFIGURATION, $permissionTransferUpdated->getConfiguration());
    }

    /**
     * @return void
     */
    public function testHydrateCompanyUserShouldReturnHydratedCompanyUser(): void
    {
        // Arrange
        $companyUserWithPermissionTransfer = $this->tester->getCompanyUserWithPermission();
        $companyUserTransfer = (new CompanyUserTransfer())
            ->setIdCompanyUser($companyUserWithPermissionTransfer->getIdCompanyUser());

        // Act
        $companyUserTransferHydrated = $this->getFacade()->hydrateCompanyUser($companyUserTransfer);

        // Assert
        $this->assertNotNull($companyUserTransferHydrated->getCompanyRoleCollection());
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\CompanyRole\Business\CompanyRoleFacadeInterface
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}

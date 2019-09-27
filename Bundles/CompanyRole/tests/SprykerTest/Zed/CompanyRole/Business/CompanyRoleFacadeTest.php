<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyRole\Business;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\CompanyRoleBuilder;
use Generated\Shared\Transfer\CompanyResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Shared\CompanyUser\Plugin\AddCompanyUserPermissionPlugin;
use Spryker\Zed\CompanyRole\Communication\Plugin\PermissionStoragePlugin;

/**
 * Auto-generated group annotations
 *
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
        $resultCompanyRoleTransfer = $this->tester->getFacade()
            ->getCompanyRoleById(
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
        $companyUserWithPermissionTransfer = $this->tester->createCompanyUserWithPermission();

        //Act
        $companyUserIds = $this->tester->getFacade()
            ->getCompanyUserIdsByPermissionKey(AddCompanyUserPermissionPlugin::KEY);

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
        $companyRoleTransfer = (new CompanyRoleBuilder([
            CompanyRoleTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]))->build();

        // Action
        $companyRoleResponseTransfer = $this->tester->getFacade()
            ->create($companyRoleTransfer);

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
        $companyResponseTransfer = $this->tester->getFacade()
            ->createByCompany($companyResponseTransfer);

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
        $this->tester->getFacade()
            ->update($existingCompanyRole);
        $resultCompanyRoleTransfer = $this->tester->getFacade()
            ->getCompanyRoleById($companyRoleTransfer);

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
        $companyRoleResponseTransfer = $this->tester->getFacade()
            ->delete($companyRoleTransfer);

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
        $resultCompanyRoleTransfer = $this->tester->getFacade()
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
        $resultCompanyRoleTransfer = $this->tester->getFacade()
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
        $resultCompanyRoleTransfer = $this->tester->getFacade()
            ->findCompanyRoleById(
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
        $resultCompanyRole = $this->tester->getFacade()
            ->findCompanyRoleById($notExistingCompanyRole);

        // Assert
        $this->assertNull($resultCompanyRole);
    }

    /**
     * @return void
     */
    public function testFindCompanyRolesShouldReturnCollection(): void
    {
        // Arrange
        $this->tester->createCompanyRoleWithPermission();

        // Act
        $companyRoleCollectionTransfer = $this->tester->getFacade()
            ->findCompanyRoles();

        // Assert
        $this->assertGreaterThan(0, $companyRoleCollectionTransfer->getRoles()->count());
    }

    /**
     * @return void
     */
    public function testGetCompanyRoleCollectionShouldReturnCollectionByIdCompanyCriteria(): void
    {
        // Arrange
        $companyRoleTransfer = $this->tester->createCompanyRoleWithPermission();
        $criteriaFilterTransfer = (new CompanyRoleCriteriaFilterTransfer())
            ->setIdCompany($companyRoleTransfer->getFkCompany());

        // Act
        $companyRoleCollectionTransfer = $this->tester->getFacade()
            ->getCompanyRoleCollection($criteriaFilterTransfer);

        // Assert
        $this->assertGreaterThan(0, $companyRoleCollectionTransfer->getRoles()->count());
    }

    /**
     * @return void
     */
    public function testGetCompanyRoleCollectionShouldReturnCollectionByIdCompanyUserCriteria(): void
    {
        // Arrange
        $companyUserWithPermissionTransfer = $this->tester->createCompanyUserWithPermission();
        $criteriaFilterTransfer = (new CompanyRoleCriteriaFilterTransfer())
            ->setIdCompanyUser($companyUserWithPermissionTransfer->getIdCompanyUser());

        // Act
        $companyRoleCollectionTransfer = $this->tester->getFacade()
            ->getCompanyRoleCollection($criteriaFilterTransfer);

        // Assert
        $this->assertGreaterThan(0, $companyRoleCollectionTransfer->getRoles()->count());
    }

    /**
     * @return void
     */
    public function testFindCompanyRolePermissionsShouldReturnCollection(): void
    {
        // Arrange
        $companyRoleTransfer = $this->tester->createCompanyRoleWithPermission();

        // Act
        $permissionCollectionTransfer = $this->tester->getFacade()
            ->findCompanyRolePermissions($companyRoleTransfer->getIdCompanyRole());

        // Assert
        $this->assertGreaterThan(0, $permissionCollectionTransfer->getPermissions()->count());
    }

    /**
     * @return void
     */
    public function testFindPermissionsByIdCompanyUserShouldReturnCollection(): void
    {
        // Arrange
        $companyUserWithPermissionTransfer = $this->tester->createCompanyUserWithPermission();

        // Act
        $permissionCollectionTransfer = $this->tester->getFacade()
            ->findPermissionsByIdCompanyUser($companyUserWithPermissionTransfer->getIdCompanyUser());

        // Assert
        $this->assertGreaterThan(0, $permissionCollectionTransfer->getPermissions()->count());
    }

    /**
     * @return void
     */
    public function testUpdateCompanyRolePermissionShouldPersistNewConfiguration(): void
    {
        // Arrange
        $companyRoleTransfer = $this->tester->createCompanyRoleWithPermission();
        $idPermission = $companyRoleTransfer->getPermissionCollection()->getPermissions()->offsetGet(0)->getIdPermission();
        $idCompanyRole = $companyRoleTransfer->getIdCompanyRole();
        $permissionTransfer = $this->tester->getFacade()
            ->findPermissionByIdCompanyRoleByIdPermission($idCompanyRole, $idPermission);

        // Act
        $permissionTransfer->setConfiguration(static::CONFIGURATION);
        $this->tester->getFacade()
            ->updateCompanyRolePermission($permissionTransfer);

        // Assert
        $permissionTransferUpdated = $this->tester->getFacade()
            ->findPermissionByIdCompanyRoleByIdPermission($idCompanyRole, $idPermission);
        $this->assertEquals(static::CONFIGURATION, $permissionTransferUpdated->getConfiguration());
    }

    /**
     * @return void
     */
    public function testHydrateCompanyUserShouldReturnHydratedCompanyUser(): void
    {
        // Arrange
        $companyUserWithPermissionTransfer = $this->tester->createCompanyUserWithPermission();
        $companyUserTransfer = (new CompanyUserTransfer())
            ->setIdCompanyUser($companyUserWithPermissionTransfer->getIdCompanyUser());

        // Act
        $companyUserTransferHydrated = $this->tester->getFacade()
            ->hydrateCompanyUser($companyUserTransfer);

        // Assert
        $this->assertNotNull($companyUserTransferHydrated->getCompanyRoleCollection());
    }
}

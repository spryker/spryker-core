<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Permission\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface;
use Spryker\Zed\Permission\Business\PermissionFacadeInterface;
use Spryker\Zed\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Permission
 * @group Business
 * @group Facade
 * @group PermissionFacadeTest
 * Add your own group annotations below this line
 */
class PermissionFacadeTest extends Unit
{
    protected const PERMISSION_PLUGIN_KEY = 'TestPermissionPlugin';

    /**
     * @var \SprykerTest\Zed\Permission\PermissionBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindMergedRegisteredNonInfrastructuralPermissionsDoesNotReturnInfrastructuralPermissions(): void
    {
        //Assign
        $this->tester->preparePermissionStorageDependency($this->createPermissionStoragePluginStub());

        // Act
        $registeredNonInfrastructuralPermissions = $this->getPermissionFacade()
            ->findMergedRegisteredNonInfrastructuralPermissions()
            ->getPermissions();

        // Assert
        $this->assertFalse($this->hasInfrastructuralPermissions($registeredNonInfrastructuralPermissions));
    }

    /**
     * @return void
     */
    public function testGetPermissionsByIdentifierShouldReturnPermissionsAssignedForCompanyUser(): void
    {
        //Assign
        $this->tester->preparePermissionStorageDependency($this->createPermissionStoragePluginStub());
        $companyUserTransfer = $this->tester->haveCompanyUserWithPermissions($this->createPermissionPluginMock());

        // Act
        $permissionCollectionTransfer = $this->getPermissionFacade()
            ->getPermissionsByIdentifier((string)$companyUserTransfer->getIdCompanyUser());

        //Assert
        $this->assertCount(1, $permissionCollectionTransfer->getPermissions());
        $this->assertEquals(
            static::PERMISSION_PLUGIN_KEY,
            $permissionCollectionTransfer->getPermissions()->offsetGet(0)->getKey()
        );
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\PermissionTransfer[] $availablePermissions
     *
     * @return bool
     */
    protected function hasInfrastructuralPermissions(ArrayObject $availablePermissions): bool
    {
        foreach ($availablePermissions as $availablePermission) {
            if (!$availablePermission->getIsInfrastructural()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface
     */
    protected function createPermissionPluginMock(): MockObject
    {
        $mock = $this->getMockBuilder(PermissionPluginInterface::class)
            ->setMethods(['getKey'])
            ->getMock();
        $mock->method('getKey')
            ->willReturn(static::PERMISSION_PLUGIN_KEY);

        return $mock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface
     */
    protected function createPermissionStoragePluginStub(): PermissionStoragePluginInterface
    {
        $permissionStoragePluginStub = $this->getMockBuilder(PermissionStoragePluginInterface::class)
            ->setMethods(['getPermissionCollection'])
            ->getMock();
        $permissionStoragePluginStub->method('getPermissionCollection')
            ->willReturn($this->createPermissionCollectionTransfer());

        return $permissionStoragePluginStub;
    }

    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    protected function createPermissionCollectionTransfer(): PermissionCollectionTransfer
    {
        $permissionTransfer = (new PermissionTransfer())->setKey(static::PERMISSION_PLUGIN_KEY);

        return (new PermissionCollectionTransfer())->addPermission($permissionTransfer);
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\Permission\Business\PermissionFacadeInterface
     */
    protected function getPermissionFacade(): PermissionFacadeInterface
    {
        return $this->tester->getFacade();
    }
}

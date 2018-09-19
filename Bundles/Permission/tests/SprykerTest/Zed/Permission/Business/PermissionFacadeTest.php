<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Permission\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Spryker\Zed\Permission\Business\PermissionFacadeInterface;

/**
 * Auto-generated group annotations
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
    /**
     * @var \SprykerTest\Zed\Permission\PermissionBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindMergedRegisteredNonInfrastructuralPermissionsDoesNotReturnInfrastructuralPermissions(): void
    {
        // Act
        $registeredNonInfrastructuralPermissions = $this->getPermissionFacade()
            ->findMergedRegisteredNonInfrastructuralPermissions()
            ->getPermissions();

        // Assert
        $this->assertFalse($this->hasInfrastructuralPermissions($registeredNonInfrastructuralPermissions));
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
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\Permission\Business\PermissionFacadeInterface
     */
    protected function getPermissionFacade(): PermissionFacadeInterface
    {
        return $this->tester->getFacade();
    }
}

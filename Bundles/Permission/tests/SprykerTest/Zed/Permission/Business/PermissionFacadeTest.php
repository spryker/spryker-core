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
        $availablePermissions = $this->getPermissionFacade()
            ->findAll()
            ->getPermissions();

        $registeredNonInfrastructuralPermissions = $this->getPermissionFacade()
            ->findMergedRegisteredNonInfrastructuralPermissions()
            ->getPermissions();

        $this->assertCount(
            $this->getNonInfrastructuralPermissionsCount($availablePermissions),
            $registeredNonInfrastructuralPermissions
        );
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\PermissionTransfer[] $availablePermissions
     *
     * @return int
     */
    protected function getNonInfrastructuralPermissionsCount(ArrayObject $availablePermissions): int
    {
        $nonInfrastructuralPermissionsCount = 0;
        foreach ($availablePermissions as $availablePermission) {
            if (!$availablePermission->getIsInfrastructural()) {
                $nonInfrastructuralPermissionsCount++;
            }
        }

        return $nonInfrastructuralPermissionsCount;
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\Permission\Business\PermissionFacadeInterface
     */
    protected function getPermissionFacade(): PermissionFacadeInterface
    {
        return $this->tester->getFacade();
    }
}

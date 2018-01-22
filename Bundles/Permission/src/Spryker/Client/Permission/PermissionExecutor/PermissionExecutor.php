<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Permission\PermissionExecutor;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Client\Permission\Dependency\Client\PermissionToCustomerClientInterface;
use Spryker\Client\Permission\PermissionFinder\PermissionFinderInterface;
use Spryker\Client\Permission\Plugin\ExecutablePermissionPluginInterface;

class PermissionExecutor implements PermissionExecutorInterface
{
    /**
     * @var \Spryker\Client\Permission\PermissionFinder\PermissionFinderInterface
     */
    protected $permissionFinder;

    /**
     * @var \Spryker\Client\Permission\Dependency\Client\PermissionToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \Spryker\Client\Permission\Dependency\Client\PermissionToCustomerClientInterface $customerClient
     * @param \Spryker\Client\Permission\PermissionFinder\PermissionFinderInterface $permissionConfigurator
     */
    public function __construct(
        PermissionToCustomerClientInterface $customerClient,
        PermissionFinderInterface $permissionConfigurator
    ) {
        $this->permissionFinder = $permissionConfigurator;
        $this->customerClient = $customerClient;
    }

    /**
     * @param string $permissionKey
     * @param string|int|array|null $context
     *
     * @return bool
     */
    public function can($permissionKey, $context = null): bool
    {
        $permissionCollectionTransfer = $this->findPermissions($permissionKey);

        if ($permissionCollectionTransfer->getPermissions()->count() === 0) {
            return false;
        }

        return $this->executePermissionCollection($permissionCollectionTransfer, $context);
    }

    /**
     * If one of the permission configurations wins, then a subject has the permission
     * Example: even if an admin user assigned to a junior sales manager role (with up to 1000 euro order),
     *  the user could perform actions as an admin.
     *
     * @param \Generated\Shared\Transfer\PermissionCollectionTransfer $permissionCollectionTransfer
     * @param string|int|array|null $context
     *
     * @return bool
     */
    protected function executePermissionCollection(PermissionCollectionTransfer $permissionCollectionTransfer, $context = null): bool
    {
        $hasPermission = false;

        foreach ($permissionCollectionTransfer->getPermissions() as $permissionTransfer) {
            $hasPermission |= $this->executePermission($permissionTransfer, $context);
        }

        return (bool)$hasPermission;
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     * @param string|int|array|null $context
     *
     * @return bool
     */
    protected function executePermission(PermissionTransfer $permissionTransfer, $context = null): bool
    {
        $permissionPlugin = $this->permissionFinder->findPermissionPlugin($permissionTransfer);

        if (!$permissionPlugin) {
            return true;
        }

        if (!($permissionPlugin instanceof ExecutablePermissionPluginInterface)) {
            return true;
        }

        return $permissionPlugin->can($permissionTransfer->getConfiguration(), $context);
    }

    /**
     * @param string $permissionKey
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    protected function findPermissions($permissionKey): PermissionCollectionTransfer
    {
        $permissionCollectionTransfer = new PermissionCollectionTransfer();

        $companyUser = $this->customerClient->getCompanyUser();
        /** @var \Generated\Shared\Transfer\CompanyRoleTransfer $companyRole */
        foreach ($companyUser->getCompanyRoleCollection() as $companyRole) {

            /** @var \Generated\Shared\Transfer\PermissionTransfer $permission */
            foreach ($companyRole->getPermissionCollection() as $permission) {
                if ($permission->getKey() === $permissionKey) {
                    $permissionCollectionTransfer->addPermission($permission);
                }
            }
        }

        return $permissionCollectionTransfer;
    }
}

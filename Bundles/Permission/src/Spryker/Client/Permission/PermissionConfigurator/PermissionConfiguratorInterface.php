<?php

namespace Spryker\Client\Permission\PermissionConfigurator;

use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Client\Permission\Plugin\ExecutionAwarePermissionPluginInterface;

interface PermissionConfiguratorInterface
{
    /**
     * @param string $permissionKey
     *
     * @return bool
     */
    public function isExecutable($permissionKey);

    /**
     * Specification:
     * - Configures a permission by its transfer
     *
     * @param PermissionTransfer $permissionTransfer
     *
     * @return ExecutionAwarePermissionPluginInterface
     */
    public function configurePermission(PermissionTransfer $permissionTransfer);
}
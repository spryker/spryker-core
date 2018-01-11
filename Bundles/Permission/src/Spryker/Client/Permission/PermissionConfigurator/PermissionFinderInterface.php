<?php

namespace Spryker\Client\Permission\PermissionFinder;

use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Client\Permission\Plugin\ExecutablePermissionPluginInterface;

interface PermissionFinderInterface
{
    /**
     * Specification:
     * - Configures a permission by its transfer
     *
     * @param string $permissionKey
     *
     * @return ExecutablePermissionPluginInterface
     */
    public function findPermissionPlugin($permissionKey);
}
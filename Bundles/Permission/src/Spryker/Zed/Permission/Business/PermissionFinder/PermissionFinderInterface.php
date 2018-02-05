<?php

namespace Spryker\Zed\Permission\Business\PermissionFinder;


interface PermissionFinderInterface
{
    /**
     * Specification:
     * - Finds permission plugins in the permission plugin stack
     * - Provides its instance
     * - Currently permission plugins are state-full by creation (created once)
     *
     * @param string $permissionKey
     *
     * @return \Spryker\Zed\Permission\Communication\Plugin\ExecutablePermissionPluginInterface
     */
    public function findPermissionPlugin($permissionKey);
}
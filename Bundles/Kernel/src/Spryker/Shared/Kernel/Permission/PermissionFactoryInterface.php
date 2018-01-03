<?php

namespace Spryker\Shared\Kernel\Permission;


interface PermissionFactoryInterface
{
    /**
     * @return PermissionInterface
     */
    public function createZedPermission();

    /**
     * @return PermissionInterface
     */
    public function createYvesPermission();
}
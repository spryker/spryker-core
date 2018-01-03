<?php

namespace Spryker\Shared\Kernel\Permission;


class PermissionMockFactory implements PermissionFactoryInterface
{
    /**
     * @return PermissionInterface
     */
    public function createZedPermission()
    {
        return new PermissionMock();
    }

    public function createYvesPermission()
    {
        return new PermissionMock();
    }
}
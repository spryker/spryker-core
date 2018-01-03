<?php


namespace Spryker\Shared\Kernel\Permission;


class PermissionMock implements PermissionInterface
{
    /**
     * @param string $permissionKey
     * @param array $context
     *
     * @return bool
     */
    public function can($permissionKey, $context = null)
    {
        return true;
    }
}
<?php


namespace Spryker\Shared\Kernel\Permission;


interface PermissionInterface
{
    /**
     * @param string $permissionKey
     * @param array|mixed|null $context
     *
     * @return bool
     */
    public function can($permissionKey, $context = null);
}
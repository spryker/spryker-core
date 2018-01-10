<?php


namespace Spryker\Client\Permission;


interface PermissionClientInterface
{
    /**
     * @param string $permissionKey
     * @param array|mixed|null $context
     *
     * @return bool
     */
    public function can($permissionKey, $context = null);
}
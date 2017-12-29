<?php


namespace Spryker\Client\Permission;


interface PermissionClientInterface
{
    /**
     * @param string $permissionKey
     * @param array $options
     *
     * @return bool
     */
    public function can($permissionKey, array $options);
}
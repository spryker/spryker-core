<?php

namespace Spryker\Client\Permission\PermissionExecutor;


interface PermissionExecutorInterface
{
    /**
     * @param string $permissionKey
     * @param mixed|array|null $context
     *
     * @return bool
     */
    public function can($permissionKey, $context = null);
}
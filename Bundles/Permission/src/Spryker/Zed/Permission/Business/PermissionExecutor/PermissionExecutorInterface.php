<?php
namespace Spryker\Zed\Permission\Business\PermissionExecutor;


interface PermissionExecutorInterface
{
    /* @param string $permissionKey
     * @param int|string $identifier
     * @param int|string|array|null $context
     *
     * @return bool
     */
    public function can($permissionKey, $identifier, $context = null);
}
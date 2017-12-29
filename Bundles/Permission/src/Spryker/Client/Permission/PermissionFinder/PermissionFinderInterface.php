<?php

namespace Spryker\Client\Permission\PermissionFinder;

interface PermissionFinderInterface
{
    /**
     * Specification:
     * - If not installed app could have two cases: skip or invalidate an access
     * - Loops over RbacDependencyProvider::RIGHTS
     *
     * @param string $permissionKey
     *
     * @return bool
     */
    public function isInstalled($permissionKey);

    /**
     * Specification:
     * - Has the current session requested right
     * - Goes to session where right is stored as following
     *
     *  key.customer.1 => [
     *      right1 => [param1 => 1],
     *      right2 => []
     *      ...
     * ]
     *
     * @param string $permissionKey
     *
     * @return bool
     */
    public function hasRight($permissionKey);

    /**
     * Specification:
     * - Gives a right for an execution
     * - Configures it (passes [param1 => 1] into configure() method)
     *
     * @param string $permissionKey
     *
     * @return mixed
     */
    public function getRight($permissionKey);
}
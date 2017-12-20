<?php

namespace Spryker\Client\Rbac\RightFinder;

interface RightFinderInterface
{
    /**
     * Specification:
     * - If not installed app could have two cases: skip or invalidate an access
     * - Loops over RbacDependencyProvider::RIGHTS
     *
     * @param string $rightKey
     *
     * @return bool
     */
    public function isInstalled($rightKey);

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
     * @param string $rightKey
     *
     * @return bool
     */
    public function hasRight($rightKey);

    /**
     * Specification:
     * - Gives a right for an execution
     * - Configures it (passes [param1 => 1] into configure() method)
     *
     * @param string $rightKey
     *
     * @return mixed
     */
    public function getRight($rightKey);
}
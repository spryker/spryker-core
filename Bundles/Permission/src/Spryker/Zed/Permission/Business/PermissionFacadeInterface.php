<?php


namespace Spryker\Zed\Permission\Business;


use Generated\Shared\Transfer\PermissionCollectionTransfer;

interface PermissionFacadeInterface
{
    /**
     * Specification:
     * - Finds available permission list
     *
     * @return PermissionCollectionTransfer
     */
    public function findAll(): PermissionCollectionTransfer;

    /**
     * Specification:
     * - Checks that permission is assigned to identifier (plugin handler)
     * - Finds a plugin by the permissionKey (if there is no enabled plugin return TRUE)
     * - If the plugin is not executable, returns TRUE
     * - Finds configuration by the permissionKey and the identifier
     * - Passes the configuration and the context to the found plugin
     * - Returns the result of the plugin execution
     *
     * @param string $permissionKey
     * @param int|string $identifier
     * @param int|string|array|null $context
     *
     * @return bool
     */
    public function can($permissionKey, $identifier, $context = null);
}
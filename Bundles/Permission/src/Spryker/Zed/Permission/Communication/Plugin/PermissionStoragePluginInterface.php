<?php


namespace Spryker\Zed\Permission\Communication\Plugin;



use Generated\Shared\Transfer\PermissionCollectionTransfer;

interface PermissionStoragePluginInterface
{
    /**
     * Specification:
     * - Finds permission in a database for a specific user
     * - Populates them into a permission collection with configurations
     *
     * @param int|string $identifier
     *
     * @return PermissionCollectionTransfer
     */
    public function getPermissionCollection($identifier): PermissionCollectionTransfer;
}
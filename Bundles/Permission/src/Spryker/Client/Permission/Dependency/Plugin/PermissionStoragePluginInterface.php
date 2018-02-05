<?php


namespace Spryker\Client\Permission\Communication\Plugin;


use Generated\Shared\Transfer\PermissionCollectionTransfer;

interface PermissionStoragePluginInterface
{
    /**
     * Specification:
     * - Finds permissions in a user session
     * - Populates them in a permission collection with configurations
     *
     * @return PermissionCollectionTransfer
     */
    public function getPermissionCollection(): PermissionCollectionTransfer;
}
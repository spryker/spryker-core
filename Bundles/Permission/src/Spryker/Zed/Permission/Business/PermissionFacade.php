<?php


namespace Spryker\Zed\Permission\Business;


use Generated\Shared\Transfer\PermissionCollectionTransfer;

class PermissionFacade implements PermissionFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @return PermissionCollectionTransfer
     */
    public function findAll()
    {
        return new PermissionCollectionTransfer();
    }

    /**
     * {@inheritdoc}
     *
     * @param string $permissionKey
     * @param int|string $identifier
     * @param int|string|array|null $context
     *
     * @return bool
     */
    public function can($permissionKey, $identifier, $context = null)
    {
        //does the identifier contain permission key? (use a plugin from Company Role get this info)
        //get configuration by PermissionKey and Identifier (use same plugin from Company Role)
        //find the plugin in provided dependency
        //pass the configuration and the context to the plugin.

        return true;
    }
}
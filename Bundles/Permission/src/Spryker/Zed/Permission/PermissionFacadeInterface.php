<?php


namespace Spryker\Zed\Permission;


use Generated\Shared\Transfer\PermissionCollectionTransfer;

interface PermissionFacadeInterface
{
    /**
     * @return PermissionCollectionTransfer
     */
    public function findAll();
}
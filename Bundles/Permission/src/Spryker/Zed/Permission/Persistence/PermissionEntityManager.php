<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Permission\Persistence;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Permission\Persistence\PermissionPersistenceFactory getFactory()
 */
class PermissionEntityManager extends AbstractEntityManager implements PermissionEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PermissionCollectionTransfer $permissionCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function upsertPermissionCollection(PermissionCollectionTransfer $permissionCollectionTransfer): PermissionCollectionTransfer
    {
        foreach ($permissionCollectionTransfer->getPermissions() as $permissionTransfer) {
            $this->upsertPermission($permissionTransfer);
        }

        return $permissionCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer
     */
    public function upsertPermission(PermissionTransfer $permissionTransfer): PermissionTransfer
    {
        $permissionEntity = $this->getFactory()
            ->createPermissionQuery()
            ->filterByKey($permissionTransfer->getKey())
            ->findOneOrCreate();

        $permissionEntity = $this->getFactory()
            ->createPropelPermissionMapper()
            ->mapPermissionTransferToEntity($permissionTransfer, $permissionEntity);

        $permissionEntity->save();

        $permissionTransfer->setIdPermission($permissionEntity->getIdPermission());

        return $permissionTransfer;
    }
}

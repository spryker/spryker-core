<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Persistence;

use Generated\Shared\Transfer\SpyPermissionEntityTransfer;
use Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\SharedCart\Persistence\SharedCartPersistenceFactory getFactory()
 */
class SharedCartEntityManager extends AbstractEntityManager implements SharedCartEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyPermissionEntityTransfer $permissionEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyPermissionEntityTransfer
     */
    public function savePermissionEntity(SpyPermissionEntityTransfer $permissionEntityTransfer): SpyPermissionEntityTransfer
    {
        $permissionEntity = $this->getFactory()
            ->createPermissionQuery()
            ->filterByKey($permissionEntityTransfer->getKey())
            ->findOneOrCreate();

        $permissionEntity->fromArray($permissionEntityTransfer->modifiedToArray());
        $permissionEntity->save();

        $permissionEntityTransfer->fromArray($permissionEntity->toArray(), true);

        return $permissionEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer $quotePermissionGroupEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer
     */
    public function saveQuotePermissionGroupEntity(SpyQuotePermissionGroupEntityTransfer $quotePermissionGroupEntityTransfer): SpyQuotePermissionGroupEntityTransfer
    {
        $quotePermissionGroupEntity = $this->getFactory()
            ->createQuotePermissionGroupQuery()
            ->filterByName($quotePermissionGroupEntityTransfer->getName())
            ->findOneOrCreate();

        $quotePermissionGroupEntity->fromArray($quotePermissionGroupEntityTransfer->modifiedToArray());
        $quotePermissionGroupEntity->save();

        $quotePermissionGroupEntityTransfer->fromArray($quotePermissionGroupEntity->toArray(), true);

        return $quotePermissionGroupEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer $quotePermissionGroupEntityTransfer
     * @param \Generated\Shared\Transfer\SpyPermissionEntityTransfer $permissionEntityTransfer
     *
     * @return void
     */
    public function saveQuotePermissionGroupToPermissionEntity(
        SpyQuotePermissionGroupEntityTransfer $quotePermissionGroupEntityTransfer,
        SpyPermissionEntityTransfer $permissionEntityTransfer
    ): void {
        $this->getFactory()
            ->createQuotePermissionGroupToPermissionQuery()
            ->filterByFkQuotePermissionGroup($quotePermissionGroupEntityTransfer->getIdQuotePermissionGroup())
            ->filterByFkPermission($permissionEntityTransfer->getIdPermission())
            ->findOneOrCreate()
            ->save();
    }
}

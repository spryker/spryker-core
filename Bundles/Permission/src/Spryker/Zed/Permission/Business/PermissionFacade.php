<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Permission\Business;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Permission\Persistence\PermissionRepositoryInterface getRepository()
 * @method \Spryker\Zed\Permission\Business\PermissionBusinessFactory getFactory()
 * @method \Spryker\Zed\Permission\Persistence\PermissionEntityManagerInterface getEntityManager()
 */
class PermissionFacade extends AbstractFacade implements PermissionFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findAll(): PermissionCollectionTransfer
    {
        return $this->getRepository()->findAll();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $permissionKey
     * @param int|string $identifier
     * @param int|string|array|null $context
     *
     * @return bool
     */
    public function can($permissionKey, $identifier, $context = null): bool
    {
        return $this->getFactory()
            ->createPermissionExecutor()
            ->can($permissionKey, $identifier, $context);
    }

    /**
     * @api
     *
     * @return void
     */
    public function syncPermissionPlugins(): void
    {
        $this->getFactory()
            ->createPermissionSynchronizer()
            ->sync();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer|null
     */
    public function findPermissionByKey(string $key): ?PermissionTransfer
    {
        return $this->getRepository()->findPermissionByKey($key);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findMergedRegisteredNonInfrastructuralPermissions(): PermissionCollectionTransfer
    {
        return $this->getFactory()
            ->createPermissionFinder()
            ->findMergedRegisteredNonInfrastructuralPermissions();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $identifier
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getPermissionsByIdentifier(string $identifier): PermissionCollectionTransfer
    {
        return $this->getFactory()->createPermissionFinder()->getPermissionsByIdentifier($identifier);
    }
}

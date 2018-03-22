<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Permission\Business;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Permission\Persistence\PermissionRepositoryInterface getRepository()
 * @method \Spryker\Zed\Permission\Business\PermissionBusinessFactory getFactory()
 */
class PermissionFacade extends AbstractFacade implements PermissionFacadeInterface
{
    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
}

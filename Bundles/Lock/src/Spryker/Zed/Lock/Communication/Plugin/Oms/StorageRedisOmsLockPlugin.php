<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Lock\Communication\Plugin\Oms;

use Generated\Shared\Transfer\LockTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OmsExtension\Dependency\Plugin\OmsLockPluginInterface;

/**
 * @method \Spryker\Zed\Lock\Business\LockFacadeInterface getFacade()
 * @method \Spryker\Zed\Lock\LockConfig getConfig()
 */
class StorageRedisOmsLockPlugin extends AbstractPlugin implements OmsLockPluginInterface
{
    /**
     * {@inheritDoc}
     * - `LockTransfer.key` is a lock entity id and `LockTransfer.entityName` is a type of the entity. Both values will be used to generate a lock key like `lock-{entity}:{key}`.
     * - `LockTransfer.expiration` is the time in seconds after which the lock will expire. If not provided, a default value will be used.
     * - `LockTransfer.blocking` determines whether the lock acquisition should block until the lock is available or return immediately if the lock cannot be acquired.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LockTransfer $lockTransfer
     *
     * @return \Generated\Shared\Transfer\LockTransfer
     */
    public function acquireLock(LockTransfer $lockTransfer): LockTransfer
    {
        return $this->getFacade()->acquireLock($lockTransfer);
    }

    /**
     * {@inheritDoc}
     * - Releases a lock based on the provided LockTransfer.
     * - `LockTransfer.key` is a lock entity id and `LockTransfer.entity` is a type of the entity. Both values will be used to generate a lock key like `lock-{entity}:{key}`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LockTransfer $lockTransfer
     *
     * @return \Generated\Shared\Transfer\LockTransfer
     */
    public function releaseLock(LockTransfer $lockTransfer): LockTransfer
    {
        return $this->getFacade()->releaseLock($lockTransfer);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsExtension\Dependency\Plugin;

use Generated\Shared\Transfer\LockTransfer;

/**
 * Plugin is used to provide alternative lock mechanism for OMS.
 */
interface OmsLockPluginInterface
{
    /**
     * Specification:
     * - Acquires a lock based on the provided LockTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LockTransfer $lockTransfer
     *
     * @return \Generated\Shared\Transfer\LockTransfer
     */
    public function acquireLock(LockTransfer $lockTransfer): LockTransfer;

    /**
     * Specification:
     * - Releases a lock based on the provided LockTransfer..
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LockTransfer $lockTransfer
     *
     * @return \Generated\Shared\Transfer\LockTransfer
     */
    public function releaseLock(LockTransfer $lockTransfer): LockTransfer;
}

<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\StateMachine\Business\Lock;

interface ItemLockInterface
{
    /**
     * @param int $identifier
     *
     * @return bool
     */
    public function acquire($identifier);

    /**
     * @param int $identifier
     *
     * @return void
     */
    public function release($identifier);

    /**
     * @return void
     */
    public function clearLocks();
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\Lock;

interface ItemLockInterface
{
    /**
     * @param string $identifier
     *
     * @return bool
     */
    public function acquire($identifier);

    /**
     * @param string $identifier
     *
     * @return void
     */
    public function release($identifier);

    /**
     * @return void
     */
    public function clearLocks();
}

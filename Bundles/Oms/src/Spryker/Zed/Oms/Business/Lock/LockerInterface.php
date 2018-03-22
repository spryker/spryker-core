<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Lock;

interface LockerInterface
{
    /**
     * @param string $identifier
     * @param string|null $details
     *
     * @return bool
     */
    public function acquire($identifier, $details = null);

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

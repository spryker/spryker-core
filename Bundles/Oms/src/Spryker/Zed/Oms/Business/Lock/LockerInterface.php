<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Lock;

interface LockerInterface
{
    /**
     * @param string|string[] $identifiers
     * @param string|null $details
     *
     * @return bool
     */
    public function acquire($identifiers, $details = null);

    /**
     * @param string|string[] $identifiers
     *
     * @return void
     */
    public function release($identifiers);

    /**
     * @return void
     */
    public function clearLocks();
}

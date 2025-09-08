<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Lock;

interface LockerInterface
{
    /**
     * @param array<string>|string $identifiers
     * @param string|null $details
     * @param bool $blocking
     *
     * @return bool
     */
    public function acquire($identifiers, $details = null, bool $blocking = false);

    /**
     * @param array<string>|string $identifiers
     *
     * @return void
     */
    public function release($identifiers);

    /**
     * @return void
     */
    public function clearLocks();

    /**
     * @param array<string>|string $identifiers
     * @param bool $blocking
     *
     * @return bool
     */
    public function acquireForOrder($identifiers, bool $blocking = false): bool;

    /**
     * @param array<string>|string $identifiers
     *
     * @return void
     */
    public function releaseForOrder($identifiers): void;
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Session\Business\Handler\Locker;

interface SessionLockerInterface
{

    /**
     * @param string $sessionKey
     *
     * @return bool
     */
    public function lock($sessionKey);

    /**
     * @return void
     */
    public function unlock();

}

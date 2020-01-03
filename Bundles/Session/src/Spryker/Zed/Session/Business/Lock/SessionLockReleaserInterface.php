<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session\Business\Lock;

/**
 * @deprecated Use `Spryker\Zed\SessionExtension\Dependency\Plugin\SessionLockReleaserPluginInterface` instead.
 */
interface SessionLockReleaserInterface
{
    /**
     * @param string $sessionId
     *
     * @return bool
     */
    public function release($sessionId);
}

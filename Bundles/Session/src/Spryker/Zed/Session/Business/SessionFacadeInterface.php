<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session\Business;

interface SessionFacadeInterface
{
    /**
     * @api
     *
     * @param string $sessionId
     *
     * @return void
     */
    public function removeYvesSessionLockFor($sessionId);

    /**
     * @api
     *
     * @param string $sessionId
     *
     * @return void
     */
    public function removeZedSessionLockFor($sessionId);
}

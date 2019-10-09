<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Session\Business\SessionBusinessFactory getFactory()
 */
class SessionFacade extends AbstractFacade implements SessionFacadeInterface
{
    /**
     * @api
     *
     * @inheritDoc
     *
     * @param string $sessionId
     *
     * @return void
     */
    public function removeYvesSessionLockFor($sessionId)
    {
        $this
            ->getFactory()
            ->createYvesSessionLockReleaser()
            ->release($sessionId);
    }

    /**
     * @api
     *
     * @inheritDoc
     *
     * @param string $sessionId
     *
     * @return void
     */
    public function removeZedSessionLockFor($sessionId)
    {
        $this
            ->getFactory()
            ->createZedSessionLockReleaser()
            ->release($sessionId);
    }
}

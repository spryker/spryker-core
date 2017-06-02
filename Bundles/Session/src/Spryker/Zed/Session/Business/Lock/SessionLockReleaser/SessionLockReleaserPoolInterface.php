<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session\Business\Lock\SessionLockReleaser;

use Spryker\Zed\Session\Business\Lock\SessionLockReleaserInterface;

interface SessionLockReleaserPoolInterface
{

    /**
     * @param \Spryker\Zed\Session\Business\Lock\SessionLockReleaserInterface $lockReleaser
     * @param string $handlerName
     *
     * @return $this
     */
    public function addLockReleaser(SessionLockReleaserInterface $lockReleaser, $handlerName);

    /**
     * @param string $handlerName
     *
     * @throws \Spryker\Zed\Session\Business\Exception\NotALockingSessionHandlerException
     *
     * @return \Spryker\Zed\Session\Business\Lock\SessionLockReleaserInterface
     */
    public function getLockReleaser($handlerName);

}

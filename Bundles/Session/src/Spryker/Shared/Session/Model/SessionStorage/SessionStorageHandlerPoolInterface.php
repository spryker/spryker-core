<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Session\Model\SessionStorage;

use SessionHandlerInterface;

interface SessionStorageHandlerPoolInterface
{

    /**
     * @param \SessionHandlerInterface $sessionHandler
     * @param string $sessionHandlerName
     *
     * @return $this
     */
    public function addHandler(SessionHandlerInterface $sessionHandler, $sessionHandlerName);

    /**
     * @param string $sessionHandlerName
     *
     * @return \SessionHandlerInterface
     */
    public function getHandler($sessionHandlerName);

}

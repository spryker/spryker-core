<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionRedis\Plugin\Session;

use SessionHandlerInterface;

class SessionHandlerRedisPlugin extends AbstractSessionHandlerRedisPlugin
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getSessionHandlerName(): string
    {
        return $this->getConfig()->getSessionHandlerRedisName();
    }

    /**
     * @return \SessionHandlerInterface
     */
    protected function getSessionHandler(): SessionHandlerInterface
    {
        return $this->getFactory()->createSessionRedisHandler();
    }
}

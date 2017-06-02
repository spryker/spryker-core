<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session\Communication;

use Spryker\Shared\Session\Business\Model\SessionFactory;

class SessionHandlerFactory extends SessionFactory
{

    /**
     * @var int
     */
    protected $sessionLifeTime;

    /**
     * @param int $sessionLifeTime
     */
    public function __construct($sessionLifeTime)
    {
        $this->sessionLifeTime = $sessionLifeTime;
    }

    /**
     * @return int
     */
    protected function getSessionLifetime()
    {
        return $this->sessionLifeTime;
    }

}

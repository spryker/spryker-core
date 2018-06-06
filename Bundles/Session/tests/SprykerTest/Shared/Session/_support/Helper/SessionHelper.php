<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Session\Helper;

use Codeception\Module;
use Spryker\Client\Session\SessionClient;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class SessionHelper extends Module
{
    /**
     * @return void
     */
    public function _initialize()
    {
        $sessionClient = new SessionClient();
        $sessionClient->setContainer(new Session(new MockArraySessionStorage()));
    }
}

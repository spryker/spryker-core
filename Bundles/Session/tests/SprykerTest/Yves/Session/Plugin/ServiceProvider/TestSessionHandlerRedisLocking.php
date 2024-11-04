<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Session\Plugin\ServiceProvider;

use Spryker\Shared\Session\Business\Handler\SessionHandlerRedisLocking;

/**
 * @deprecated Will be removed without replacement in the next major release.
 */
class TestSessionHandlerRedisLocking extends SessionHandlerRedisLocking
{
    public function __destruct()
    {
    }
}

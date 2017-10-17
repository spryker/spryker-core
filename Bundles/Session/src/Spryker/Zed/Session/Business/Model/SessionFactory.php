<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session\Business\Model;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Session\Business\Model\SessionFactory as SharedSessionFactory;
use Spryker\Shared\Session\SessionConstants;

class SessionFactory extends SharedSessionFactory
{
    /**
     * @return int
     */
    public function getSessionLifetime()
    {
        $lifetime = (int)Config::get(SessionConstants::ZED_SESSION_TIME_TO_LIVE);

        return $lifetime;
    }
}

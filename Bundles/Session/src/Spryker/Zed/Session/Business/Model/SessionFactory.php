<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session\Business\Model;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Session\Business\Model\SessionFactory as SharedSessionFactory;

class SessionFactory extends SharedSessionFactory
{

    /**
     * @return int
     */
    public function getSessionLifetime()
    {
        $lifetime = (int)Config::get(ApplicationConstants::ZED_STORAGE_SESSION_TIME_TO_LIVE);

        return $lifetime;
    }

}

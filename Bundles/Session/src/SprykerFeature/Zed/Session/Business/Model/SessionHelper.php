<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Session\Business\Model;

use SprykerEngine\Shared\Config;
use SprykerFeature\Shared\Session\Business\Model\SessionHelper as SharedSessionHelper;
use SprykerFeature\Shared\System\SystemConfig;

class SessionHelper extends SharedSessionHelper
{

    /**
     * @return int
     */
    public function getSessionLifetime()
    {
        $lifetime = (int) Config::get(SystemConfig::ZED_STORAGE_SESSION_TTL);

        return $lifetime;
    }

}

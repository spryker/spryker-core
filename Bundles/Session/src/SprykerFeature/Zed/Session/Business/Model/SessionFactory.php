<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Session\Business\Model;

use SprykerEngine\Shared\Config;
use SprykerFeature\Shared\Session\Business\Model\SessionFactory as SharedSessionFactory;
use SprykerFeature\Shared\System\SystemConfig;

class SessionFactory extends SharedSessionFactory
{

    /**
     * @return int
     */
    public function getSessionLifetime()
    {
        $lifetime = (int) Config::get(SystemConfig::ZED_STORAGE_SESSION_TIME_TO_LIVE);

        return $lifetime;
    }

}

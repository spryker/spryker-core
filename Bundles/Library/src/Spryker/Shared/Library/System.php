<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library;

use Spryker\Shared\UtilNetwork\Host AS UtilNetworkHost;

/**
 * @deprecated use \Spryker\Zed\UtilNetwork\Business\UtilNetworkFacade instead
 */
class System
{

    /**
     * @var \Spryker\Shared\UtilNetwork\Host
     */
    protected static $utilNetworkHost;

    /**
     * @return string
     */
    public static function getHostname()
    {
        return self::createUtilNetworkHost()->getHostname();
    }

    /**
     * @return \Spryker\Shared\UtilNetwork\Host
     */
    protected static function createUtilNetworkHost()
    {
        if (static::$utilNetworkHost === null) {
            static::$utilNetworkHost = new UtilNetworkHost();
        }

        return static::$utilNetworkHost;
    }

}

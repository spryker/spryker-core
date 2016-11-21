<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library;

use Spryker\Service\UtilNetwork\Model\Host AS UtilNetworkHost;

/**
 * @deprecated use \Spryker\Service\UtilNetwork\UtilNetworkService instead
 */
class System
{

    /**
     * @var \Spryker\Service\UtilNetwork\Model\Host
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
     * @return \Spryker\Service\UtilNetwork\Model\Host
     */
    protected static function createUtilNetworkHost()
    {
        if (static::$utilNetworkHost === null) {
            static::$utilNetworkHost = new UtilNetworkHost();
        }

        return static::$utilNetworkHost;
    }

}

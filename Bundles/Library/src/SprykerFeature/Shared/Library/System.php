<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library;

class System
{

    /**
     * @var string
     */
    protected static $hostname;

    /**
     * @return string
     * @static
     */
    public static function getHostname()
    {
        if (!isset(self::$hostname)) {
            self::$hostname = (gethostname()) ?: php_uname('n');
        }

        return self::$hostname;
    }

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Application;

use ErrorException;

class TestEnvironment
{

    /**
     * @return void
     */
    public static function initialize()
    {
        if (PHP_SAPI === 'cli') {
            defined('SYSTEM_UNDER_TEST') || define('SYSTEM_UNDER_TEST', getenv('SYSTEM_UNDER_TEST') === '1' ? true : false);
        } elseif (isset($_GET['SYSTEM_UNDER_TEST']) && $_GET['SYSTEM_UNDER_TEST'] === 1) {
            defined('SYSTEM_UNDER_TEST') || define('SYSTEM_UNDER_TEST', 1);
        } else {
            defined('SYSTEM_UNDER_TEST') || define('SYSTEM_UNDER_TEST', 0);
        }
    }

    /**
     * @throws \ErrorException
     * @return void
     */
    public static function forceSystemUnderTest()
    {
        if (defined('SYSTEM_UNDER_TEST') && SYSTEM_UNDER_TEST === 1) {
            return;
        }

        if (defined('SYSTEM_UNDER_TEST') && SYSTEM_UNDER_TEST === 0) {
            throw new ErrorException('Cannot change to Test-Mode after a previous initialisation.');
        }

        define('SYSTEM_UNDER_TEST', 1);
    }

    /**
     * @return bool
     */
    public static function isSystemUnderTest()
    {
        if (defined('SYSTEM_UNDER_TEST') && SYSTEM_UNDER_TEST === 1) {
            return true;
        }

        return false;
    }

}

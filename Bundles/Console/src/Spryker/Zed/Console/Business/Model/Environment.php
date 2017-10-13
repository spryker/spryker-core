<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Console\Business\Model;

use Spryker\Shared\Config\Application\Environment as SprykerEnvironment;
use Spryker\Shared\ErrorHandler\ErrorHandlerEnvironment;

class Environment
{
    /**
     * @return void
     */
    public static function initialize()
    {
        defined('APPLICATION_SOURCE_DIR')
            || define('APPLICATION_SOURCE_DIR', APPLICATION_ROOT_DIR . '/src');

        defined('APPLICATION_VENDOR_DIR')
            || define('APPLICATION_VENDOR_DIR', APPLICATION_ROOT_DIR . '/vendor');

        defined('APPLICATION')
            || define('APPLICATION', 'ZED');

        defined('SYSTEM_UNDER_TEST')
            || define('SYSTEM_UNDER_TEST', false);

        SprykerEnvironment::initialize();

        $errorHandlerEnvironment = new ErrorHandlerEnvironment();
        $errorHandlerEnvironment->initialize();
    }
}

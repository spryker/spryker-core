<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Configuration;
use Codeception\Module;

class Environment extends Module
{

    const MODE_ISOLATED = 'isolated';

    const MODE_DEFAULT_ROOT = '../../../../../../../../..';
    const MODE_ISOLATED_ROOT = 'vendor/spryker/testify';

    /**
     * @return void
     */
    public function _initialize()
    {
        $path = self::MODE_DEFAULT_ROOT;

        if (isset($this->config['mode']) && $this->config['mode'] === self::MODE_ISOLATED) {
            $path = self::MODE_ISOLATED_ROOT;
        }

        $pathParts = explode(DIRECTORY_SEPARATOR, Configuration::projectDir());
        $srcDirectoryPosition = array_search('current', $pathParts);

        $rootDirPathParts = array_slice($pathParts, 1, $srcDirectoryPosition);

        $rootDir = DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $rootDirPathParts);

        defined('APPLICATION_ENV') || define('APPLICATION_ENV', 'devtest');
        defined('APPLICATION_STORE') || define('APPLICATION_STORE', (isset($_SERVER['APPLICATION_STORE'])) ? $_SERVER['APPLICATION_STORE'] : 'DE');
        defined('APPLICATION') || define('APPLICATION', 'ZED');

        defined('APPLICATION_ROOT_DIR') || define('APPLICATION_ROOT_DIR', $rootDir);
        defined('APPLICATION_SOURCE_DIR') || define('APPLICATION_SOURCE_DIR', APPLICATION_ROOT_DIR . '/src');
        defined('APPLICATION_VENDOR_DIR') || define('APPLICATION_VENDOR_DIR', APPLICATION_ROOT_DIR . '/vendor');
    }

}

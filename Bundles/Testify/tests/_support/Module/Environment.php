<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Testify\Module;

use Codeception\Configuration;
use Codeception\Lib\ModuleContainer;
use Codeception\Module;

/**
 * @deprecated Use SprykerTest\Shared\Testify\Helper\Environment instead.
 */
class Environment extends Module
{
    public const MODE_ISOLATED = 'isolated';

    public const MODE_DEFAULT_ROOT = '../../../../..';
    public const MODE_ISOLATED_ROOT = 'vendor/spryker/testify';

    /**
     * @param \Codeception\Lib\ModuleContainer $moduleContainer
     * @param array|null $config
     */
    public function __construct(ModuleContainer $moduleContainer, $config)
    {
        parent::__construct($moduleContainer, $config);

        $this->initEnvironment();
    }

    /**
     * @return void
     */
    private function initEnvironment()
    {
        $path = self::MODE_DEFAULT_ROOT;

        if (isset($this->config['mode']) && $this->config['mode'] === self::MODE_ISOLATED) {
            $path = self::MODE_ISOLATED_ROOT;
        }

        $applicationRoot = Configuration::projectDir() . $path;

        defined('APPLICATION_ENV') || define('APPLICATION_ENV', 'devtest');
        defined('APPLICATION_STORE') || define('APPLICATION_STORE', (isset($_SERVER['APPLICATION_STORE'])) ? $_SERVER['APPLICATION_STORE'] : 'DE');
        defined('APPLICATION') || define('APPLICATION', 'ZED');

        defined('APPLICATION_ROOT_DIR') || define('APPLICATION_ROOT_DIR', $applicationRoot);
        defined('APPLICATION_VENDOR_DIR') || define('APPLICATION_VENDOR_DIR', APPLICATION_ROOT_DIR . '/vendor');
        defined('APPLICATION_SOURCE_DIR') || define('APPLICATION_SOURCE_DIR', APPLICATION_ROOT_DIR . '/src');
    }
}

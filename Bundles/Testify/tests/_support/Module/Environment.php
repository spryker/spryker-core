<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Testify\Module;

use Codeception\Configuration;
use Codeception\Lib\ModuleContainer;
use Codeception\Module;

class Environment extends Module
{

    const MODE_ISOLATED = 'isolated';

    const MODE_DEFAULT_ROOT = '../../../../..';
    const MODE_ISOLATED_ROOT = 'vendor/spryker/testify';

    /**
     * @param \Codeception\Lib\ModuleContainer $moduleContainer
     * @param null $config
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
        $sprykerRoot = '/vendor/spryker/spryker/Bundles';

        if (isset($this->config['mode']) && $this->config['mode'] === self::MODE_ISOLATED) {
            $path = self::MODE_ISOLATED_ROOT;
            $sprykerRoot = '/../';
        }

        $applicationRoot = Configuration::projectDir() . $path;

        defined('APPLICATION_ENV') || define('APPLICATION_ENV', 'test');
        defined('APPLICATION_STORE') || define('APPLICATION_STORE', 'DE');
        defined('APPLICATION') || define('APPLICATION', 'ZED');

        defined('APPLICATION_ROOT_DIR') || define('APPLICATION_ROOT_DIR', $applicationRoot);
        defined('APPLICATION_SOURCE_DIR') || define('APPLICATION_SOURCE_DIR', APPLICATION_ROOT_DIR . '/src');
    }

}

<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Testify\Module;

use Codeception\Exception\ModuleConfigException;
use Codeception\Lib\Framework;
use Codeception\TestCase;
use Spryker\Shared\Testify\SystemUnderTestBootstrap;
use Symfony\Component\HttpKernel\Client;

class ZedBootstrap extends Framework
{

    const CONFIG_KEY_SERVICE_PROVIDER = 'serviceProvider';

    /**
     * @var \Spryker\Shared\Testify\SystemUnderTestBootstrap
     */
    protected $application;

    /**
     * @var array
     */
    protected $requiredFields = [
        self::CONFIG_KEY_SERVICE_PROVIDER,
    ];

    /**
     * @return void
     */
    public function _initialize()
    {
        $this->loadApplication();
    }

    /**
     * @param \Codeception\TestCase $test
     *
     * @return void
     */
    public function _before(TestCase $test)
    {
        $this->client = new Client($this->application->bootstrap(SystemUnderTestBootstrap::APPLICATION_ZED));
    }

    /**
     * @throws \Codeception\Exception\ModuleConfigException
     *
     * @return void
     */
    protected function loadApplication()
    {
        $this->application = SystemUnderTestBootstrap::getInstance();
//        $this->application = new SystemUnderTestBootstrap();

//        $this->application = new BootstrapZedBootstrap($this->config[self::CONFIG_KEY_SERVICE_PROVIDER]);
//        $this->application;

        if (!isset($this->application)) {
            throw new ModuleConfigException(__CLASS__, 'Application instance was not received from bootstrap file');
        }
    }

    /**
     * @return array
     */
    public function getInternalDomains()
    {
        $internalDomains = [];

        foreach ($this->application['routes'] as $route) {
            if ($domain = $route->getHost()) {
                $internalDomains[] = '/^' . preg_quote($domain, '/') . '$/';
            }
        }

        return $internalDomains;
    }

}

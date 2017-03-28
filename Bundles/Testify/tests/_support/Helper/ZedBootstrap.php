<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Testify\Helper;

use Codeception\Exception\ModuleConfigException;
use Codeception\Lib\Framework;
use Codeception\TestInterface;
use Spryker\Zed\Testify\Bootstrap\ZedBootstrap as TestifyBootstrap;
use Symfony\Component\HttpKernel\Client;

class ZedBootstrap extends Framework
{

    const CONFIG_KEY_SERVICE_PROVIDER = 'serviceProvider';

    /**
     * @var \Spryker\Zed\Testify\Bootstrap\ZedBootstrap
     */
    protected $application;

    /**
     * @var array
     */
    protected $config = [
        self::CONFIG_KEY_SERVICE_PROVIDER => [],
    ];

    /**
     * @return void
     */
    public function _initialize()
    {
        $this->loadApplication();
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test)
    {
        $this->client = new Client($this->application->boot());
    }

    /**
     * @throws \Codeception\Exception\ModuleConfigException
     *
     * @return void
     */
    protected function loadApplication()
    {
        $this->application = new TestifyBootstrap($this->config[static::CONFIG_KEY_SERVICE_PROVIDER]);

        if (!isset($this->application)) {
            throw new ModuleConfigException(__CLASS__, 'Application instance was not received from bootstrap file');
        }
    }

}

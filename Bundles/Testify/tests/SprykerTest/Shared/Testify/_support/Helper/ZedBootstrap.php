<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Exception\ModuleConfigException;
use Codeception\Lib\Framework;
use Codeception\Lib\Interfaces\DependsOnModule;
use Codeception\TestInterface;
use Codeception\Util\Stub;
use Spryker\Zed\Testify\Bootstrap\ZedBootstrap as TestifyBootstrap;
use Spryker\Zed\Twig\TwigConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Client;

class ZedBootstrap extends Framework implements DependsOnModule
{
    public const CONFIG_KEY_SERVICE_PROVIDER = 'serviceProvider';

    /**
     * @var \Spryker\Zed\Testify\Bootstrap\ZedBootstrap
     */
    private $application;

    /**
     * @var \SprykerTest\Shared\Testify\Helper\BundleConfig
     */
    private $bundleConfig;

    /**
     * @var array
     */
    protected $config = [
        self::CONFIG_KEY_SERVICE_PROVIDER => [],
    ];

    /**
     * @return array
     */
    public function _depends()
    {
        return [
            BundleConfig::class => 'You need to enable \SprykerTest\Shared\Testify\Helper\BundleConfig in order to mock bundle configurations',
        ];
    }

    /**
     * @param \SprykerTest\Shared\Testify\Helper\BundleConfig $bundleConfig
     *
     * @return void
     */
    public function _inject(BundleConfig $bundleConfig)
    {
        $this->bundleConfig = $bundleConfig;
    }

    /**
     * @return void
     */
    public function _initialize()
    {
        $this->loadApplication();
        $this->mockBundleConfigs();
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
        Request::createFromGlobals();
        Request::setTrustedHosts(['localhost']);

        $this->application = new TestifyBootstrap($this->config[static::CONFIG_KEY_SERVICE_PROVIDER]);

        if (!isset($this->application)) {
            throw new ModuleConfigException(__CLASS__, 'Application instance was not received from bootstrap file');
        }
    }

    /**
     * @return void
     */
    private function mockBundleConfigs()
    {
        $this->bundleConfig->addBundleConfigMock($this->getTwigBundleConfigMock());
    }

    /**
     * @return \Spryker\Shared\Kernel\AbstractBundleConfig|\PHPUnit_Framework_MockObject_Builder_InvocationMocker
     */
    private function getTwigBundleConfigMock()
    {
        $twigConfig = new TwigConfig();
        /** @var \Spryker\Shared\Kernel\AbstractBundleConfig $twigBundleConfigMock */
        $twigBundleConfigMock = Stub::make(TwigConfig::class, [
            'getTemplatePaths' => function () use ($twigConfig) {
                $paths = $twigConfig->getTemplatePaths();
                $paths[] = APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/%2$s/src/*/Zed/%1$s/Presentation';

                return $paths;
            },
        ]);

        return $twigBundleConfigMock;
    }
}

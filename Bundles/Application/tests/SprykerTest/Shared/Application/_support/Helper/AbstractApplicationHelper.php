<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Application\Helper;

use Codeception\Lib\Framework;
use Codeception\TestInterface;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Application\Application;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use SprykerTest\Service\Container\Helper\ContainerHelperTrait;
use SprykerTest\Zed\Testify\Helper\Communication\CommunicationHelperTrait;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelBrowser;
use Symfony\Component\HttpKernel\HttpKernelInterface;

abstract class AbstractApplicationHelper extends Framework
{
    use ContainerHelperTrait;
    use CommunicationHelperTrait;

    protected const MODULE_NAME = 'Application';

    /**
     * @uses \Spryker\Yves\Http\Plugin\Application\HttpApplicationPlugin::SERVICE_KERNEL
     * @uses \Spryker\Zed\Http\Communication\Plugin\Application\HttpApplicationPlugin::SERVICE_KERNEL
     */
    protected const SERVICE_KERNEL = 'kernel';

    /**
     * @var \Symfony\Component\BrowserKit\AbstractBrowser|null
     */
    public $client;

    /**
     * @var \Spryker\Shared\Application\Application|null
     */
    protected $application;

    /**
     * @var \Symfony\Component\HttpKernel\HttpKernelBrowser|null
     */
    protected $httpKernelBrowser;

    /**
     * @var \Symfony\Component\HttpFoundation\Request|null
     */
    protected $request;

    /**
     * @return void
     */
    public function _initialize(): void
    {
        $requestFactory = function (array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null) {
            $request = new Request($query, $request, $attributes, $cookies, $files, $server, $content);
            $request->server->set('SERVER_NAME', 'localhost');

            return $request;
        };

        Request::setFactory($requestFactory);
    }

    /**
     * @param \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface $applicationPlugin
     *
     * @return $this
     */
    public function addApplicationPlugin(ApplicationPluginInterface $applicationPlugin)
    {
        $this->getApplication()->registerApplicationPlugin($applicationPlugin);

        return $this;
    }

    /**
     * @return \Symfony\Component\BrowserKit\AbstractBrowser
     */
    public function getHttpKernelBrowser(): AbstractBrowser
    {
        if ($this->httpKernelBrowser === null) {
            $this->httpKernelBrowser = new HttpKernelBrowser($this->getKernel());
        }

        return $this->httpKernelBrowser;
    }

    /**
     * @return \Symfony\Component\BrowserKit\AbstractBrowser
     */
    public function getClient(): AbstractBrowser
    {
        if ($this->client === null) {
            $this->client = $this->getHttpKernelBrowser();
        }

        return $this->client;
    }

    /**
     * @param string $page
     *
     * @return void
     */
    public function amOnPage($page): void
    {
        if ($this->client === null) {
            $this->getClient();
        }

        $this->_loadPage('GET', $page);
    }

    /**
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function getKernel(): HttpKernelInterface
    {
        $this->getApplication()->boot();

        return $this->getContainer()->get(static::SERVICE_KERNEL);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest(): Request
    {
        if (!$this->request) {
            $this->request = Request::createFromGlobals();
        }

        return $this->request;
    }

    /**
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function getContainer(): ContainerInterface
    {
        return $this->getContainerHelper()->getContainer();
    }

    /**
     * @return \Spryker\Shared\Application\Application
     */
    protected function getApplication(): Application
    {
        if ($this->application === null) {
            $this->application = new Application(
                $this->getContainer()
            );
        }

        return $this->application;
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        $this->application = null;
        $this->client = null;
        $this->httpKernelBrowser = null;
    }

    /**
     * @param array $settings
     *
     * @return void
     */
    public function _beforeSuite($settings = [])
    {
        $requestFactory = function (array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null) {
            $request = new Request($query, $request, $attributes, $cookies, $files, $server, $content);
            $request->server->set('SERVER_NAME', 'localhost');

            return $request;
        };

        Request::setFactory($requestFactory);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Testify\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Application\Application;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Yves\Http\Plugin\Application\HttpApplicationPlugin;
use SprykerTest\Service\Container\Helper\ContainerHelper;
use Symfony\Component\HttpKernel\HttpKernelBrowser;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ApplicationHelper extends Module
{
    /**
     * @uses \Spryker\Yves\Http\Plugin\Application\HttpApplicationPlugin::SERVICE_KERNEL
     */
    protected const SERVICE_KERNEL = 'kernel';

    /**
     * @var \Spryker\Shared\Application\Application|null
     */
    protected $application;

    /**
     * @var \Symfony\Component\HttpKernel\HttpKernelBrowser
     */
    protected $httpKernelBrowser;

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $this->addApplicationPlugin(new HttpApplicationPlugin());
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
     * @return \Symfony\Component\HttpKernel\HttpKernelBrowser
     */
    public function getHttpKernelBrowser(): HttpKernelBrowser
    {
        if ($this->httpKernelBrowser === null) {
            $this->httpKernelBrowser = new HttpKernelBrowser($this->getKernel());
        }

        return $this->httpKernelBrowser;
    }

    /**
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function getKernel(): HttpKernelInterface
    {
        $this->getApplication()->boot();
        $container = $this->getContainer();

        return $container->get(static::SERVICE_KERNEL);
    }

    /**
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function getContainer(): ContainerInterface
    {
        return $this->getContainerHelper()->getContainer();
    }

    /**
     * @return \SprykerTest\Service\Container\Helper\ContainerHelper
     */
    protected function getContainerHelper(): ContainerHelper
    {
        /** @var \SprykerTest\Service\Container\Helper\ContainerHelper $containerHelper */
        $containerHelper = $this->getModule('\\' . ContainerHelper::class);

        return $containerHelper;
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
    }
}

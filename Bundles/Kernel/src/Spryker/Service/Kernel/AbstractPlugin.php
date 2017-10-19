<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Kernel;

use Spryker\Service\Kernel\ClassResolver\Config\BundleConfigResolver;
use Spryker\Service\Kernel\ClassResolver\Factory\FactoryResolver;
use Spryker\Service\Kernel\ClassResolver\Service\ServiceResolver;

abstract class AbstractPlugin
{
    /**
     * @var \Spryker\Service\Kernel\AbstractBundleConfig
     */
    private $config;

    /**
     * @var \Spryker\Service\Kernel\AbstractServiceFactory
     */
    private $factory;

    /**
     * @var \Spryker\Service\Kernel\AbstractService
     */
    private $service;

    /**
     * @param \Spryker\Service\Kernel\AbstractServiceFactory $factory
     *
     * @return $this
     */
    public function setFactory(AbstractServiceFactory $factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * @return \Spryker\Service\Kernel\AbstractServiceFactory
     */
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        return $this->factory;
    }

    /**
     * @return \Spryker\Service\Kernel\AbstractServiceFactory
     */
    private function resolveFactory()
    {
        return $this->getFactoryResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Service\Kernel\ClassResolver\Factory\FactoryResolver
     */
    private function getFactoryResolver()
    {
        return new FactoryResolver();
    }

    /**
     * @param \Spryker\Service\Kernel\AbstractService $service
     *
     * @return $this
     */
    public function setService(AbstractService $service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return \Spryker\Service\Kernel\AbstractService
     */
    protected function getService()
    {
        if ($this->service === null) {
            $this->service = $this->resolveService();
        }

        return $this->service;
    }

    /**
     * @return \Spryker\Service\Kernel\AbstractService
     */
    private function resolveService()
    {
        return $this->getServiceResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Service\Kernel\ClassResolver\Service\ServiceResolver
     */
    private function getServiceResolver()
    {
        return new ServiceResolver();
    }

    /**
     * @return \Spryker\Service\Kernel\AbstractBundleConfig
     */
    protected function getConfig()
    {
        if ($this->config === null) {
            $this->config = $this->resolveBundleConfig();
        }

        return $this->config;
    }

    /**
     * @return \Spryker\Service\Kernel\AbstractBundleConfig
     */
    private function resolveBundleConfig()
    {
        return $this->getBundleConfigResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Service\Kernel\ClassResolver\Config\BundleConfigResolver
     */
    private function getBundleConfigResolver()
    {
        return new BundleConfigResolver();
    }
}

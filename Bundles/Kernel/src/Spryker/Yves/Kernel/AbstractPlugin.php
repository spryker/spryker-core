<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel;

use Spryker\Client\Kernel\ClassResolver\Client\ClientResolver;
use Spryker\Shared\Kernel\Container\GlobalContainer;
use Spryker\Yves\Kernel\ClassResolver\Config\BundleConfigResolver;
use Spryker\Yves\Kernel\ClassResolver\Factory\FactoryResolver;

abstract class AbstractPlugin
{
    protected const SERVICE_LOCALE = 'locale';

    /**
     * @var \Spryker\Yves\Kernel\FactoryInterface
     */
    private $factory;

    /**
     * @var \Spryker\Client\Kernel\AbstractClient
     */
    private $client;

    /**
     * @var \Spryker\Yves\Kernel\AbstractBundleConfig
     */
    private $config;

    /**
     * @var \Spryker\Service\Container\ContainerInterface|null
     */
    protected static $container;

    /**
     * @var string|null
     */
    protected static $locale;

    /**
     * @param \Spryker\Yves\Kernel\AbstractFactory $factory
     *
     * @return $this
     */
    public function setFactory(AbstractFactory $factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * @return \Spryker\Yves\Kernel\FactoryInterface
     */
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        return $this->factory;
    }

    /**
     * @return \Spryker\Yves\Kernel\AbstractFactory
     */
    private function resolveFactory()
    {
        return $this->getFactoryResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Yves\Kernel\ClassResolver\Factory\FactoryResolver
     */
    private function getFactoryResolver()
    {
        return new FactoryResolver();
    }

    /**
     * @return \Spryker\Client\Kernel\AbstractClient
     */
    protected function getClient()
    {
        if ($this->client === null) {
            $this->client = $this->resolveClient();
        }

        return $this->client;
    }

    /**
     * @return \Spryker\Client\Kernel\AbstractClient
     */
    private function resolveClient()
    {
        return $this->getClientResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Client\Kernel\ClassResolver\Client\ClientResolver
     */
    private function getClientResolver()
    {
        return new ClientResolver();
    }

    /**
     * @param \Spryker\Yves\Kernel\AbstractBundleConfig $config
     *
     * @return $this
     */
    public function setConfig(AbstractBundleConfig $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return \Spryker\Yves\Kernel\AbstractBundleConfig
     */
    protected function getConfig()
    {
        if ($this->config === null) {
            $this->config = $this->resolveBundleConfig();
        }

        return $this->config;
    }

    /**
     * @return \Spryker\Yves\Kernel\AbstractBundleConfig
     */
    private function resolveBundleConfig()
    {
        return $this->getBundleConfigResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Yves\Kernel\ClassResolver\Config\BundleConfigResolver
     */
    private function getBundleConfigResolver()
    {
        return new BundleConfigResolver();
    }

    /**
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function getApplication()
    {
        if (static::$container === null) {
            static::$container = (new GlobalContainer())->getContainer();
        }

        return static::$container;
    }

    /**
     * @return string
     */
    protected function getLocale()
    {
        if (static::$locale === null) {
            static::$locale = $this->getApplication()->get(static::SERVICE_LOCALE);
        }

        return static::$locale;
    }
}

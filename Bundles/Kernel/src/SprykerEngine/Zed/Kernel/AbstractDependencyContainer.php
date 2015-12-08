<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Zed\Kernel\ClassResolver\Config\BundleConfigResolver;

abstract class AbstractDependencyContainer
{

    /**
     * @var AbstractBundleConfig
     */
    private $config;


    /**
     * @deprecated Will be removed soon, please use new instead
     *
     * @return FactoryInterface
     */
    protected function getFactory()
    {
        return $this->factory;
    }

    /**
     * @deprecated Will be removed soon. Use DependencyProvider instead
     *
     * @return AutoCompletion|LocatorLocatorInterface
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }

    /**
     * @param AbstractBundleConfig $config
     *
     * @return self
     */
    public function setConfig(AbstractBundleConfig $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @TODO this method should not be public
     *
     * @return AbstractBundleConfig
     */
    public function getConfig()
    {
        if ($this->config === null) {
            $this->config = $this->findBundleConfig();
        }

        return $this->config;
    }

    /**
     * @throws \Exception
     *
     * @return mixed
     */
    private function findBundleConfig()
    {
        $resolver = new BundleConfigResolver();

        return $resolver->resolve($this);
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return static::class;
    }

}

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
     * @var Factory
     */
    private $factory;

    /**
     * @var AbstractBundleConfig
     */
    private $config;

    /**
     * @param FactoryInterface $factory
     * @param LocatorLocatorInterface $locator
     * @param AbstractBundleConfig $config
     */
    public function __construct(FactoryInterface $factory = null, LocatorLocatorInterface $locator = null, AbstractBundleConfig $config = null)
    {
        $this->factory = $factory;
    }

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
     * @return AbstractBundleConfig
     */
    protected function getConfig()
    {
        if (is_null($this->config)) {
            $this->config = $this->findBundleConfig();
        }

        return $this->config;
    }

    /**
     * @throws \Exception
     * @return mixed
     */
    private function findBundleConfig()
    {
        $resolver = new BundleConfigResolver();

        return $resolver->resolve($this);
    }

}

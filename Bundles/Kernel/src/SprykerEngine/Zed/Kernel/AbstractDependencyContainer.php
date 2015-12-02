<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

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
     * @param AbstractBundleConfig $config
     */
    public function __construct(FactoryInterface $factory = null, AbstractBundleConfig $config = null)
    {
        $this->factory = $factory;
        $this->config = $config;
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
    public function getConfig()
    {
        return $this->config;
    }

}

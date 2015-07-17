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
     * @var AutoCompletion|LocatorLocatorInterface
     */
    private $locator;

    /**
     * @var AbstractBundleConfig
     */
    private $config;

    /**
     * @param FactoryInterface $factory
     * @param LocatorLocatorInterface $locator
     * @param AbstractBundleConfig $config
     */
    public function __construct(FactoryInterface $factory, LocatorLocatorInterface $locator, AbstractBundleConfig $config)
    {
        $this->factory = $factory;
        $this->locator = $locator;
        $this->config = $config;
    }

    /**
     * @return FactoryInterface
     */
    protected function getFactory()
    {
        return $this->factory;
    }

    /**
     * @deprecated
     *
     * @return AutoCompletion|LocatorLocatorInterface
     */
    protected function getLocator()
    {
        return $this->locator;
    }

    /**
     * @return AbstractBundleConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

}

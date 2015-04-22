<?php

namespace SprykerEngine\Zed\Kernel\Persistence;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Persistence\DependencyContainer\DependencyContainerInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

abstract class AbstractDependencyContainer implements DependencyContainerInterface
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
     * @param Factory $factory
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(Factory $factory, LocatorLocatorInterface $locator)
    {
        $this->factory = $factory;
        $this->locator = $locator;
    }

    /**
     * @return Factory
     */
    protected function getFactory()
    {
        return $this->factory;
    }

    /**
     * @return AutoCompletion|LocatorLocatorInterface
     */
    protected function getLocator()
    {
        return $this->locator;
    }
}


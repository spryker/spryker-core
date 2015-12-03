<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Kernel;

use Generated\Yves\Ide\AutoCompletion;
use SprykerEngine\Yves\Kernel\DependencyContainer\DependencyContainerInterface;
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
     * @deprecated Will be removed soon, please use new instead
     *
     * @return Factory
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
        return $this->locator;
    }

}

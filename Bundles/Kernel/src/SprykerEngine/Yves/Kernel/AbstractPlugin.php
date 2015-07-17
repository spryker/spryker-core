<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Kernel;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Yves\Kernel\DependencyContainer\DependencyContainerInterface;

abstract class AbstractPlugin
{

    /**
     * @var DependencyContainerInterface
     */
    private $dependencyContainer;

    /**
     * @param Factory $factory
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(Factory $factory, LocatorLocatorInterface $locator)
    {
        if ($factory->exists('DependencyContainer')) {
            $this->dependencyContainer = $factory->create('DependencyContainer', $factory, $locator);
        }
    }

    /**
     * @return DependencyContainerInterface
     */
    protected function getDependencyContainer()
    {
        return $this->dependencyContainer;
    }

}

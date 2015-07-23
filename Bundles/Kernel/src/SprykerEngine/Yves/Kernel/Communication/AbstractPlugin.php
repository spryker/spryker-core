<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Kernel\Communication;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Yves\Kernel\DependencyContainer\DependencyContainerInterface;

abstract class AbstractPlugin
{

    const DEPENDENCY_CONTAINER = 'DependencyContainer';

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
        if ($factory->exists(self::DEPENDENCY_CONTAINER)) {
            $this->dependencyContainer = $factory->create(self::DEPENDENCY_CONTAINER, $factory, $locator);
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

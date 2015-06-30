<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Sdk\Kernel;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Sdk\Kernel\DependencyContainer\DependencyContainerInterface;

abstract class AbstractSdk
{

    /**
     * @var DependencyContainerInterface
     */
    private $dependencyContainer;

    /**
     * @param FactoryInterface $factory
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(FactoryInterface $factory, LocatorLocatorInterface $locator)
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

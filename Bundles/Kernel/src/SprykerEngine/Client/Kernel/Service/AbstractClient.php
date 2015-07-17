<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Client\Kernel\Service;

use SprykerEngine\Client\Kernel\Container;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Client\Kernel\DependencyContainer\DependencyContainerInterface;

abstract class AbstractClient
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
     * @param Container $container
     */
    public function setExternalDependencies(Container $container)
    {
        $dependencyContainer = $this->getDependencyContainer();
        if (isset($dependencyContainer)) {
            $this->getDependencyContainer()->setContainer($container);
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

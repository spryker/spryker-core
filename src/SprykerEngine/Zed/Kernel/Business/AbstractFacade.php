<?php

namespace SprykerEngine\Zed\Kernel\Business;

use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Kernel\Business\DependencyContainer\DependencyContainerInterface;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;

abstract class AbstractFacade
{

    /**
     * @var DependencyContainerInterface
     */
    private $dependencyContainer;

    /**
     * @param FactoryInterface $factory
     * @param Locator $locator
     */
    public function __construct(FactoryInterface $factory, Locator $locator)
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

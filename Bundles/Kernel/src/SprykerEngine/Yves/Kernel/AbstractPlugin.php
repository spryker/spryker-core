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
     * @var Factory
     */
    private $factory;

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
        $this->factory = $factory;

        if ($factory->exists('DependencyContainer')) {
            $this->dependencyContainer = $factory->create('DependencyContainer', $factory, $locator);
        }
    }

    /**
     * @return Factory
     */
    protected function getFactory()
    {
        return $this->factory;
    }

    /**
     * @return DependencyContainerInterface
     */
    protected function getDependencyContainer()
    {
        return $this->dependencyContainer;
    }

}

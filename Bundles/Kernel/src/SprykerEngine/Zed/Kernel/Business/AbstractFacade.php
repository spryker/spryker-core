<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Business;

use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Zed\Kernel\Business\DependencyContainer\DependencyContainerInterface;
use SprykerEngine\Zed\Kernel\ClassResolver;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;

abstract class AbstractFacade implements FacadeInterface
{

    const DEPENDENCY_CONTAINER = 'DependencyContainer';

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

//        if ($factory->exists(self::DEPENDENCY_CONTAINER)) {
//            $this->dependencyContainer = $factory->create(self::DEPENDENCY_CONTAINER, $factory, $locator);
//        }
    }

    /**
     * @param Container $container
     *
     * @return void
     */
    public function setExternalDependencies(Container $container)
    {
        $dependencyContainer = $this->getDependencyContainer();
        if (isset($dependencyContainer)) {
            $this->getDependencyContainer()->setContainer($container);
        }
    }

    /**
     * @param AbstractBusinessDependencyContainer $businessDependencyContainer
     *
     * @return self
     */
    public function setDependencyContainer(AbstractBusinessDependencyContainer $businessDependencyContainer)
    {
        $this->dependencyContainer = $businessDependencyContainer;

        return $this;
    }

    /**
     * @return DependencyContainerInterface
     */
    protected function getDependencyContainer()
    {
        if (is_null($this->dependencyContainer)) {
            $this->dependencyContainer = $this->findDependencyContainer();
        }

        return $this->dependencyContainer;
    }

    /**
     * @throws \Exception
     * @return mixed
     */
    private function findDependencyContainer()
    {
        $classResolver = new ClassResolver();

        return $classResolver->resolve('DependencyContainer', $this);
    }

    /**
     * TODO move to constructor
     *
     * @param AbstractQueryContainer $queryContainer
     *
     * @return void
     */
    public function setOwnQueryContainer(AbstractQueryContainer $queryContainer)
    {
        $dependencyContainer = $this->getDependencyContainer();
        if (isset($dependencyContainer)) {
            $this->getDependencyContainer()->setQueryContainer($queryContainer);
        }
    }

}

<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Persistence;

use Propel\Runtime\Connection\ConnectionInterface;
use SprykerEngine\Zed\Kernel\ClassResolver\DependencyContainer\DependencyContainerResolver;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Kernel\Persistence\DependencyContainer\DependencyContainerInterface;
use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

abstract class AbstractQueryContainer implements QueryContainerInterface
{

    const DEPENDENCY_CONTAINER = 'DependencyContainer';
    const PROPEL_CONNECTION = 'propel connection';

    /**
     * @var DependencyContainerInterface
     */
    private $dependencyContainer;

    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     *
     * @return self
     */
    public function setExternalDependencies(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @param string $key
     *
     * @throws \ErrorException
     *
     * @return mixed
     */
    public function getProvidedDependency($key)
    {
        if ($this->container->offsetExists($key) === false) {
            throw new \ErrorException('Key ' . $key . ' does not exist in container.');
        }

        return $this->container[$key];
    }

    /**
     * @return AbstractPersistenceDependencyContainer
     */
    protected function getDependencyContainer()
    {
        if ($this->dependencyContainer === null) {
            $this->dependencyContainer = $this->findDependencyContainer();
        }

        if ($this->container !== null) {
            $this->dependencyContainer->setContainer($this->container);
        }

        return $this->dependencyContainer;
    }

    /**
     * @throws \Exception
     *
     * @return mixed
     */
    private function findDependencyContainer()
    {
        $classResolver = new DependencyContainerResolver();

        return $classResolver->resolve($this);
    }

    /**
     * @return ConnectionInterface
     */
    public function getConnection()
    {
        return $this->getProvidedDependency(self::PROPEL_CONNECTION);
    }

}

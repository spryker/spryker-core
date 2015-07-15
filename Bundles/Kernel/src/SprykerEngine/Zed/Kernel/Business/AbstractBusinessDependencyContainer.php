<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Business;

use SprykerEngine\Zed\Kernel\AbstractDependencyContainer as BaseDependencyContainer;
use SprykerEngine\Zed\Kernel\Business\DependencyContainer\DependencyContainerInterface;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;

abstract class AbstractBusinessDependencyContainer extends BaseDependencyContainer implements DependencyContainerInterface
{

    /**
     * @var Container
     */
    private $container;

    /**
     * @var AbstractQueryContainer
     */
    private $queryContainer;

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param $key
     *
     * @throws \ErrorException
     *
     * @return mixed
     */
    public function getProvidedDependency($key)
    {
        if (is_null($this->container)) {
            throw new \ErrorException('Container does not exist in ' . get_class($this));
        }

        if (false === $this->container->offsetExists($key)) {
            throw new \ErrorException('Key ' . $key . ' does not exist in container: ' . get_class($this));
        }

        return $this->container[$key];
    }

    /**
     * @return AbstractQueryContainer
     */
    protected function getQueryContainer()
    {
        return $this->queryContainer;
    }

    /**
     * @param AbstractQueryContainer $queryContainer
     */
    public function setQueryContainer(AbstractQueryContainer $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

}

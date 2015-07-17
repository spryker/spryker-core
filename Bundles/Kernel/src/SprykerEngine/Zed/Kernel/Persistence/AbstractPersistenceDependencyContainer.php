<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Persistence;

use SprykerEngine\Zed\Kernel\AbstractDependencyContainer as BaseDependencyContainer;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Kernel\Persistence\DependencyContainer\DependencyContainerInterface;

abstract class AbstractPersistenceDependencyContainer extends BaseDependencyContainer implements DependencyContainerInterface
{

    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @todo remove from here. This should go to QueryContainer directly
     *
     * @param string $key
     *
     * @throws \ErrorException
     *
     * @return mixed
     */
    public function getProvidedDependency($key)
    {
        if (false === $this->container->offsetExists($key)) {
            throw new \ErrorException('Key ' . $key . ' does not exist in container.');
        }

        return $this->container[$key];
    }

}

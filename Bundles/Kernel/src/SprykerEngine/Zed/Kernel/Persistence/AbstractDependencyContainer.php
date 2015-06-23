<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Persistence;

use SprykerEngine\Zed\Kernel\AbstractDependencyContainer as BaseDependencyContainer;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Kernel\Persistence\DependencyContainer\DependencyContainerInterface;

abstract class AbstractDependencyContainer extends BaseDependencyContainer implements DependencyContainerInterface
{

    /**
     * External dependencies
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
     * TODO remove from here. This should go to QueryContainer directly
     * @param string $key
     * @return mixed
     * @throws \ErrorException
     */
    public function getProvidedDependency($key)
    {
        echo $key .PHP_EOL;
        if(false === $this->container->offsetExists($key)){
            throw new \ErrorException('Key ' . $key . ' does not exist in container.');
        }

        return $this->container[$key];
    }

}


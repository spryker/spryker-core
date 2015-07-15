<?php

namespace SprykerEngine\Client\Kernel\Service;

use Generated\Client\Ide\AutoCompletion;
use SprykerEngine\Client\Kernel\Container;
use SprykerEngine\Client\Kernel\DependencyContainer\DependencyContainerInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

abstract class AbstractServiceDependencyContainer implements DependencyContainerInterface
{

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @var AutoCompletion|LocatorLocatorInterface
     */
    private $locator;

    /**
     * @var Container
     */
    private $container;

    /**
     * @param Factory $factory
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(Factory $factory, LocatorLocatorInterface $locator)
    {
        $this->factory = $factory;
        $this->locator = $locator;
    }

    /**
     * @return Factory
     */
    protected function getFactory()
    {
        return $this->factory;
    }

    /**
     * @return AutoCompletion|LocatorLocatorInterface
     */
    protected function getLocator()
    {
        return $this->locator;
    }

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

}

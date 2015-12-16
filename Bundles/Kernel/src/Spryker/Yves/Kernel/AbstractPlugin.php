<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Yves\Kernel;

use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\Kernel\ClassResolver\Client\ClientNotFoundException;
use Spryker\Client\Kernel\ClassResolver\Client\ClientResolver;
use Spryker\Yves\Kernel\ClassResolver\Factory\FactoryNotFoundException;
use Spryker\Yves\Kernel\ClassResolver\Factory\FactoryResolver;

abstract class AbstractPlugin
{

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var AbstractClient
     */
    private $client;

    /**
     * @return FactoryInterface
     */
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        return $this->factory;
    }

    /**
     * @throws FactoryNotFoundException
     *
     * @return AbstractFactory
     */
    protected function resolveFactory()
    {
        return $this->getFactoryResolver()->resolve($this);
    }

    /**
     * @return FactoryResolver
     */
    protected function getFactoryResolver()
    {
        return new FactoryResolver();
    }

    /**
     * @return AbstractClient
     */
    protected function getClient()
    {
        if ($this->client === null) {
            $this->client = $this->resolveClient();
        }

        return $this->client;
    }

    /**
     * @throws ClientNotFoundException
     *
     * @return AbstractClient
     */
    protected function resolveClient()
    {
        return $this->getClientResolver()->resolve($this);
    }

    /**
     * @return ClientResolver
     */
    protected function getClientResolver()
    {
        return new ClientResolver();
    }

}

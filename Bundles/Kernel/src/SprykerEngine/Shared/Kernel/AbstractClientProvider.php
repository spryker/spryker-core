<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Kernel;

use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;

abstract class AbstractClientProvider
{

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var LocatorLocatorInterface
     */
    protected $locator;

    /**
     * @var mixed
     */
    protected $client;

    /**
     * @throws \Exception
     *
     * @return mixed
     */
    public function getInstance()
    {
        if (is_null($this->client)) {
            $this->client = $this->createClient();
        }

        return $this->client;
    }

    /**
     * @param FactoryInterface $factory
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(FactoryInterface $factory, LocatorLocatorInterface $locator)
    {
        $this->factory = $factory;
        $this->locator = $locator;
    }

    /**
     * @return mixed
     */
    abstract protected function createClient();

}

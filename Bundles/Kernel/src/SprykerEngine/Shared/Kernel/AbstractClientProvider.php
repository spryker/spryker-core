<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Kernel;

use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

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
     * @return mixed
     * @throws \Exception
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

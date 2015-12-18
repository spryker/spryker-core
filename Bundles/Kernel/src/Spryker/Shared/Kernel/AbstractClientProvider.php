<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Kernel;

abstract class AbstractClientProvider
{

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
        if ($this->client === null) {
            $this->client = $this->createZedClient();
        }

        return $this->client;
    }

    /**
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(LocatorLocatorInterface $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @return mixed
     */
    abstract protected function createZedClient();

}

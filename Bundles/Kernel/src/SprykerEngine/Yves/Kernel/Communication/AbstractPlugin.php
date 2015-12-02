<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Kernel\Communication;

use SprykerEngine\Client\Kernel\Service\AbstractClient;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Yves\Kernel\DependencyContainer\DependencyContainerInterface;

abstract class AbstractPlugin
{

    const DEPENDENCY_CONTAINER = 'DependencyContainer';

    /**
     * @var DependencyContainerInterface
     */
    private $dependencyContainer;

    /**
     * @var AbstractClient
     */
    private $client;

    /**
     * @param Factory $factory
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(Factory $factory, LocatorLocatorInterface $locator)
    {
        if ($factory->exists(self::DEPENDENCY_CONTAINER)) {
            $this->dependencyContainer = $factory->create(self::DEPENDENCY_CONTAINER, $factory, $locator);
        }

        $this->setClient($factory);
    }

    /**
     * @param Factory $factory
     *
     * @return void
     */
    private function setClient(Factory $factory)
    {
        $bundleName = lcfirst($factory->getBundle());
        $this->client = $factory->$bundleName()->client();
    }

    /**
     * @return DependencyContainerInterface
     */
    protected function getDependencyContainer()
    {
        return $this->dependencyContainer;
    }

    /**
     * @return AbstractClient
     */
    protected function getClient()
    {
        return $this->client;
    }

}

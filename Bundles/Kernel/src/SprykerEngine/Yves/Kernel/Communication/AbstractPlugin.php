<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Kernel\Communication;

use Generated\Yves\Ide\AutoCompletion;
use SprykerEngine\Client\Kernel\Service\AbstractClient;
use SprykerEngine\Shared\Kernel\Locator\LocatorInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Yves\Kernel\DependencyContainer\DependencyContainerInterface;
use SprykerEngine\Yves\Kernel\Locator;

abstract class AbstractPlugin
{

    const DEPENDENCY_CONTAINER = 'DependencyContainer';

    /**
     * @var DependencyContainerInterface
     */
    private $dependencyContainer;

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @var AutoCompletion
     */
    private $locator;

    /**
     * @var AbstractClient
     */
    private $client;

//    /**
//     * @param Factory $factory
//     * @param LocatorLocatorInterface $locator
//     */
//    public function __construct(Factory $factory, LocatorLocatorInterface $locator)
//    {
//        $this->factory = $factory;
//        $this->locator = $locator;
//
//        if ($factory->exists(self::DEPENDENCY_CONTAINER)) {
//            $this->dependencyContainer = $factory->create(self::DEPENDENCY_CONTAINER, $factory, $locator);
//        }
//    }

    /**
     * @return DependencyContainerInterface
     */
    protected function getDependencyContainer()
    {
        if ($this->dependencyContainer === null) {
            $factory = new Factory($this->getBundleName());

            $this->dependencyContainer = $factory->create(self::DEPENDENCY_CONTAINER, $factory, $this->getLocator());
        }

        return $this->dependencyContainer;
    }

    /**
     * @return AbstractClient
     */
    protected function getClient()
    {
        if ($this->client === null) {
            $bundleName = lcfirst($this->factory->getBundle());
            $this->client = $this->locator->$bundleName()->client();
        }

        return $this->client;
    }

    /**
     * @return string
     */
    private function getBundleName()
    {
        $className = get_class($this);
        $expl = explode('\\', $className);
        $bundle = $expl[2];
        $bundle = lcfirst($bundle);

        return $bundle;
    }

    /**
     * @return LocatorInterface
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

}

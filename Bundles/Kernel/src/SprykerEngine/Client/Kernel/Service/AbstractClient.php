<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Client\Kernel\Service;

use SprykerEngine\Client\Kernel\Container;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Client\Kernel\DependencyContainer\DependencyContainerInterface;
use SprykerFeature\Client\ZedRequest\Service\Stub\BaseStub;
use SprykerFeature\Shared\ZedRequest\Client\Message;

abstract class AbstractClient
{

    const DEPENDENCY_CONTAINER = 'DependencyContainer';

    /**
     * @var DependencyContainerInterface
     */
    private $dependencyContainer;

    /**
     * @param FactoryInterface $factory
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(FactoryInterface $factory, LocatorLocatorInterface $locator)
    {
        if ($factory->exists(self::DEPENDENCY_CONTAINER)) {
            $this->dependencyContainer = $factory->create(self::DEPENDENCY_CONTAINER, $factory, $locator);
        }
    }

    /**
     * @param Container $container
     */
    public function setExternalDependencies(Container $container)
    {
        $dependencyContainer = $this->getDependencyContainer();
        if (isset($dependencyContainer)) {
            $this->getDependencyContainer()->setContainer($container);
        }
    }

    /**
     * @return DependencyContainerInterface
     */
    protected function getDependencyContainer()
    {
        return $this->dependencyContainer;
    }

    /**
     * @return BaseStub
     */
    protected function getZedStub()
    {
        $dependencyContainer = $this->getDependencyContainer();
        if (!method_exists($dependencyContainer, 'createZedStub')) {
            throw new \BadMethodCallException(
                sprintf('createZedStub method is not implemented in "%s".', get_class($dependencyContainer))
            );
        }

        return $this->getDependencyContainer()->createZedStub();
    }

    /**
     * @return Message[]
     */
    public function getZedInfoMessages()
    {
        return $this->getZedStub()->getInfoMessages();
    }

    /**
     * @return Message[]
     */
    public function getZedSuccessMessages()
    {
        return $this->getZedStub()->getSuccessMessages();
    }

    /***
     * @return Message[]
     */
    public function getZedErrorMessages()
    {
        return $this->getZedStub()->getErrorMessages();
    }

}

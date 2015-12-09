<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Communication;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Zed\Kernel\BundleDependencyProviderLocator;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;

abstract class AbstractPlugin
{

    const DEPENDENCY_CONTAINER = 'DependencyContainer';

    const CLASS_PART_BUNDLE = 2;

    /**
     * @var AbstractFacade
     */
    private $facade;

    /**
     * @var AbstractCommunicationDependencyContainer
     */
    private $dependencyContainer;

    /**
     * @var AbstractQueryContainer
     */
    private $queryContainer;

    /**

     * @return AbstractFacade
     */
    protected function getFacade()
    {
        if ($this->facade === null) {
            $bundle = lcfirst($this->getBundleName());

            $this->facade = $this->getLocator()->$bundle()->facade();
        }

        return $this->facade;
    }

    /**
     * @return AbstractCommunicationDependencyContainer
     */
    protected function getDependencyContainer()
    {
        if ($this->dependencyContainer === null) {
            $factory = new Factory($this->getBundleName());

            $this->dependencyContainer = $factory->create(self::DEPENDENCY_CONTAINER, $factory, $this->getLocator());

            $bundleConfigLocator = new BundleDependencyProviderLocator();
            $container = new Container();
            $bundleBuilder = $bundleConfigLocator->locate($this->getBundleName(), $this->getLocator());
            $bundleBuilder->provideCommunicationLayerDependencies($container);

            $this->dependencyContainer->setContainer($container);
        }

        return $this->dependencyContainer;
    }

    /**
     * @return AbstractQueryContainer
     */
    protected function getQueryContainer()
    {
        if ($this->queryContainer === null) {
            $bundle = lcfirst($this->getBundleName());
            $this->queryContainer = $this->getLocator()->$bundle()->queryContainer();
        }

        return $this->queryContainer;
    }

    /**
     * @return string
     */
    protected function getBundleName()
    {
        $className = get_class($this);
        $classParts = explode('\\', $className);
        $bundle = $classParts[self::CLASS_PART_BUNDLE];

        return $bundle;
    }

    /**
     * @return LocatorLocatorInterface
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

}

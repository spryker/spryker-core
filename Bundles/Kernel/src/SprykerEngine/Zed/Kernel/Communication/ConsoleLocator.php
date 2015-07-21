<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Communication;

use SprykerEngine\Shared\Kernel\AbstractLocator;
use SprykerEngine\Shared\Kernel\Locator\LocatorException;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Zed\Kernel\BundleDependencyProviderLocator;
use SprykerEngine\Zed\Kernel\Container;

class ConsoleLocator extends AbstractLocator
{

    const DEPENDENCY_CONTAINER = 'DependencyContainer';

    /**
     * @var string
     */
    protected $factoryClassNamePattern = '\\{{namespace}}\\Zed\\Kernel\\Communication\\Factory';

    /**
     * @param string $bundle
     * @param LocatorLocatorInterface $locator
     * @param null|string $className
     *
     * @throws LocatorException
     *
     * @return object
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null)
    {
        $factory = $this->getFactory($bundle);
        $resolvedConsole = $factory->create('Console' . $className);

        if ($factory->exists(self::DEPENDENCY_CONTAINER)) {
            $resolvedConsole->setDependencyContainer($factory->create(self::DEPENDENCY_CONTAINER, $factory, $locator));
        }

        $bundleName = lcfirst($bundle);

        $bundleConfigLocator = new BundleDependencyProviderLocator(); // @todo Make singleton because of performance
        $bundleBuilder = $bundleConfigLocator->locate($bundle, $locator);

        $container = new Container();
        $bundleBuilder->provideCommunicationLayerDependencies($container);
        $resolvedConsole->setExternalDependencies($container);

        // @todo make lazy
        if ($locator->$bundleName()->hasFacade()) {
            $resolvedConsole->setFacade($locator->$bundleName()->facade());
        }

        if ($locator->$bundleName()->hasQueryContainer()) {
            $resolvedConsole->setQueryContainer($locator->$bundleName()->queryContainer());
        }

        return $resolvedConsole;
    }

}

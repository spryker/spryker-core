<?php

namespace SprykerEngine\Zed\Kernel;

use SprykerEngine\Shared\Kernel\AbstractLocator;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

abstract class AbstractDependencyContainerLocator extends AbstractLocator
{

    const DEPENDENCY_CONTAINER_SUFFIX = 'DependencyContainer';

    /**
     * @param $bundle
     * @param LocatorLocatorInterface $locator
     * @param null|string $className
     *
     * @return object
     * @throws \SprykerEngine\Shared\Kernel\Locator\LocatorException
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null)
    {
        $factory = $this->getFactory($bundle);

        $configLocator = new BundleConfigLocator();
        $bundleConfig = $configLocator->locate($bundle, $locator);

        return $factory->create($bundle . self::DEPENDENCY_CONTAINER_SUFFIX, [$bundleConfig]);
    }
}

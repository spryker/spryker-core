<?php

namespace SprykerEngine\Zed\Kernel\Communication;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Shared\Kernel\AbstractLocator;
use SprykerEngine\Zed\Kernel\BundleConfigLocator;

class DependencyContainerLocator extends AbstractLocator
{

    const DEPENDENCY_CONTAINER_SUFFIX = 'DependencyContainer';

    /**
     * @var string
     */
    protected $factoryClassNamePattern = '\\{{namespace}}\\Zed\\{{bundle}}\\Communication\\Factory';

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

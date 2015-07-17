<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel;

use SprykerEngine\Shared\Kernel\Locator\LocatorException;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Shared\Kernel\AbstractLocator;

class BundleDependencyProviderLocator extends AbstractLocator
{

    const CLASS_NAME_SUFFIX = 'DependencyProvider';

    /**
     * @var string
     */
    protected $factoryClassNamePattern = '\\{{namespace}}\\Zed\\Kernel\\Factory';

    /**
     * @param string $bundle
     * @param LocatorLocatorInterface $locator
     * @param string|null $className
     *
     * @throws LocatorException
     *
     * @return BundleDependencyProviderInterface
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null)
    {
        $factory = $this->getFactory($bundle);
        $className = $bundle . self::CLASS_NAME_SUFFIX;

        return $factory->create($className);
    }

}

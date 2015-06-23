<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Communication;

use SprykerEngine\Shared\Kernel\Locator\LocatorException;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Shared\Kernel\AbstractLocator;

class DependencyContainerLocator extends AbstractLocator
{

    const DEPENDENCY_CONTAINER_SUFFIX = 'DependencyContainer';

    /**
     * @var string
     */
    protected $factoryClassNamePattern = '\\{{namespace}}\\Zed\\{{bundle}}\\Communication\\Factory';

    /**
     * @param string $bundle
     * @param LocatorLocatorInterface $locator
     * @param string|null $className
     *
     * @return object
     * @throws LocatorException
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null)
    {
        $factory = $this->getFactory($bundle);

        return $factory->create($bundle . self::DEPENDENCY_CONTAINER_SUFFIX);
    }

}

<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Sdk\Kernel;

use SprykerEngine\Shared\Kernel\AbstractLocator;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

/**
 * Class SdkLocator
 * @package SprykerEngine\Sdk\Kernel
 */
class SdkLocator extends AbstractLocator
{

    const SDK_SUFFIX = 'Sdk';

    /**
     * @var string
     */
    protected $factoryClassNamePattern = '\\{{namespace}}\\Sdk\\Kernel\\Factory';

    /**
     * @param string                 $bundle
     * @param LocatorLocatorInterface $locator
     * @param null|string            $className
     *
     * @return object
     * @throws \SprykerEngine\Shared\Kernel\Locator\LocatorException
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null)
    {
        $factory = $this->getFactory($bundle);

        return $factory->create($bundle . self::SDK_SUFFIX, $factory, $locator);
    }

}

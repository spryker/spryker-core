<?php

namespace SprykerEngine\Client\Kernel;

use SprykerEngine\Shared\Kernel\AbstractLocator;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

class ClientLocator extends AbstractLocator
{

    const CLIENT_SUFFIX = 'Client';

    /**
     * @var string
     */
    protected $factoryClassNamePattern = '\\{{namespace}}\\Client\\Kernel\\Factory';

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

        return $factory->create($bundle . self::CLIENT_SUFFIX, $factory, $locator);
    }

}

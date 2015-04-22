<?php

namespace SprykerEngine\Zed\Kernel\Business;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Shared\Kernel\AbstractLocator;

class FacadeLocator extends AbstractLocator
{

    const FACADE_SUFFIX = 'Facade';

    /**
     * @var string
     */
    protected $factoryClassNamePattern = '\\{{namespace}}\\Zed\\Kernel\\Business\\Factory';

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

        return $factory->create($bundle . self::FACADE_SUFFIX, $factory, $locator);
    }

}

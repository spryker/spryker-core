<?php

namespace SprykerEngine\Zed\Kernel\Persistence;

use SprykerEngine\Shared\Kernel\Locator\LocatorException;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Shared\Kernel\AbstractLocator;

class QueryContainerLocator extends AbstractLocator
{

    /**
     * @var string
     */
    protected $factoryClassNamePattern = '\\{{namespace}}\\Zed\\Kernel\\Persistence\\Factory';

    /**
     * @param string $bundle
     * @param LocatorLocatorInterface $locator
     * @param null|string $className
     *
     * @return object
     * @throws LocatorException
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null)
    {
        $factory = $this->getFactory($bundle);

        return $factory->create($bundle . 'QueryContainer', $factory, $locator);
    }

    /**
     * @param string $bundle
     * 
     * @return bool
     */
    public function canLocate($bundle)
    {
        $factory = $this->getFactory($bundle);
        return $factory->exists($bundle . 'QueryContainer');
    }
}

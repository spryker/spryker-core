<?php

namespace SprykerEngine\Zed\Kernel\Communication;

use SprykerEngine\Shared\Kernel\AbstractLocator;
use SprykerEngine\Shared\Kernel\ClassResolver;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

class PluginLocator extends AbstractLocator
{

    /**
     * @var string
     */
    protected $factoryClassNamePattern = '\\{{namespace}}\\Zed\\Kernel\\Communication\\Factory';

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

        return $factory->create('Plugin' . $className, $factory, $locator);
    }

}

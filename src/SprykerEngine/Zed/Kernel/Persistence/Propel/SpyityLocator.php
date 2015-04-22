<?php

namespace SprykerEngine\Zed\Kernel\Persistence\Propel;

use SprykerEngine\Shared\Kernel\CamelHumpClassResolver;
use SprykerEngine\Shared\Kernel\ClassResolver;
use SprykerEngine\Shared\Kernel\IdentityMapClassResolver;
use SprykerEngine\Shared\Kernel\Locator\LocatorInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

class SpyityLocator implements LocatorInterface
{

    /**
     * @var string
     */
    private $classNamePattern = '\\{{namespace}}\\Zed\\{{bundle}}{{store}}\\Persistence\\Propel\\';

    /**
     * @param string|null $classNamePattern
     */
    public function __construct($classNamePattern = null)
    {
        if (!is_null($classNamePattern)) {
            $this->classNamePattern = $classNamePattern;
        }
    }

    /**
     * @param $bundle
     * @param LocatorLocatorInterface $locator
     * @param null $className
     *
     * @return object
     * @throws ClassResolver\ClassNameAmbiguousException
     * @throws ClassResolver\ClassNotFoundException
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null)
    {
        $classToLocate = $this->classNamePattern . $className;
        $classResolver = new ClassResolver();
        $camelHumpClassResolver = new CamelHumpClassResolver($classResolver);
        $resolver = IdentityMapClassResolver::getInstance($camelHumpClassResolver);
        $resolvedTransfer = $camelHumpClassResolver->resolve($classToLocate, $bundle);

        return $resolvedTransfer;
    }
}

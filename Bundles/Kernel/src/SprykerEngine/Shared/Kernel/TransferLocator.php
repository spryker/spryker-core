<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Kernel;

use SprykerEngine\Shared\Kernel\Locator\LocatorInterface;

class TransferLocator implements LocatorInterface
{

    /**
     * @var string
     */
    private $classNamePattern = '\\{{namespace}}\\Shared\\{{bundle}}{{store}}\\Transfer\\';

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
     * @param string $bundle
     * @param LocatorLocatorInterface $locator
     * @param string $className
     *
     * @throws ClassResolver\ClassNameAmbiguousException
     * @throws ClassResolver\ClassNotFoundException
     *
     * @return object
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

    /**
     * @param string $bundle
     *
     * @throws \ErrorException
     *
     * @return bool
     */
    public function canLocate($bundle)
    {
        throw new \ErrorException('Not available here');
    }

}

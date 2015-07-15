<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Kernel;

use SprykerEngine\Shared\Kernel\Locator\LocatorInterface;
use SprykerEngine\Shared\Kernel\Locator\LocatorException;

abstract class AbstractLocator implements LocatorInterface
{

    /**
     * @var string
     */
    protected $factoryClassNamePattern;

    /**
     * @param string $factoryClassNamePattern
     *
     * @throws LocatorException
     */
    final public function __construct($factoryClassNamePattern = null)
    {
        if (!is_null($factoryClassNamePattern)) {
            $this->factoryClassNamePattern = $factoryClassNamePattern;
        }

        if (is_null($this->factoryClassNamePattern)) {
            throw new LocatorException(
                sprintf('You must provide a factoryClassNamePattern for "%s"', get_class($this))
            );
        }
    }

    /**
     * @param string $bundle
     * @param LocatorLocatorInterface $locator
     * @param null|string $className
     *
     * @return object
     */
    abstract public function locate($bundle, LocatorLocatorInterface $locator, $className = null);

    /**
     * TODO make abstract
     *
     * @param $bundle
     *
     * @throws \ErrorException
     *
     * @return bool
     */
    public function canLocate($bundle)
    {
        throw new \ErrorException('Need implementation in each locator');
    }

    /**
     * @param string $bundle
     *
     * @throws LocatorException
     *
     * @return AbstractFactory
     */
    protected function getFactory($bundle)
    {
        $resolver = IdentityMapClassResolver::getInstance(new ClassResolver());
        $classNamePattern = $this->getFactoryClassNamePattern();

        if ($resolver->canResolve($classNamePattern, $bundle)) {
            return $resolver->resolve($classNamePattern, $bundle, [$bundle]);
        }

        throw new LocatorException(sprintf('Could not find Factory "%s', $classNamePattern));
    }

    /**
     * @throws LocatorException
     *
     * @return null|string
     */
    private function getFactoryClassNamePattern()
    {
        return $this->factoryClassNamePattern;
    }

}

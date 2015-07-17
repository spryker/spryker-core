<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel;

use SprykerEngine\Shared\Kernel\ClassResolver;
use SprykerEngine\Shared\Kernel\IdentityMapClassResolver;
use SprykerEngine\Zed\Kernel\Factory\FactoryException;
use SprykerEngine\Zed\Kernel\Factory\FactoryInterface;

abstract class AbstractFactory implements FactoryInterface
{

    const SUFFIX_FACTORY = 'Factory';

    /**
     * @var string
     */
    protected $classNamePattern;

    /**
     * @var ClassResolver
     */
    private $resolver;

    /**
     * @var string
     */
    private $bundle;

    /**
     * @var array
     */
    protected $baseClasses = [
        'DependencyContainer',
        'Factory',
    ];

    /**
     * @param string $class
     *
     * @return bool
     */
    public function exists($class)
    {
        $class = $this->buildClassName($class);
        $resolver = $this->getResolver();

        return $resolver->canResolve($class, $this->getBundle());
    }

    /**
     * @return ClassResolver
     */
    protected function getResolver()
    {
        if (is_null($this->resolver)) {
            $this->resolver = IdentityMapClassResolver::getInstance(new ClassResolver());
        }

        return $this->resolver;
    }

    /**
     * @return string
     */
    protected function getBundle()
    {
        if (is_null($this->bundle)) {
            $classNameParts = explode('\\', get_class($this));
            $bundleFactory = array_pop($classNameParts);

            $this->bundle = str_replace(self::SUFFIX_FACTORY, '', $bundleFactory);
        }

        return $this->bundle;
    }

    /**
     * @param string $class
     *
     * @return string
     */
    protected function buildClassName($class)
    {
        if (in_array($class, $this->baseClasses)) {
            $class = $this->getBundle() . $class;
        }

        return $this->getClassNamePattern() . $class;
    }

    /**
     * @throws FactoryException
     *
     * @return string
     */
    protected function getClassNamePattern()
    {
        if (is_null($this->classNamePattern)) {
            throw new FactoryException(sprintf('Couldn\'t find a classNamePattern in "%s"', get_class($this)));
        }

        return $this->classNamePattern;
    }

}

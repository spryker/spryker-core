<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Kernel;

use SprykerEngine\Shared\Kernel\Factory\FactoryException;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Shared\Kernel\Factory2;

abstract class AbstractFactory implements FactoryInterface
{

    const SUFFIX_FACTORY = 'Factory';
    const METHOD_EXISTS = 'exists';
    const METHOD_CREATE = 'create';
    const DEPENDENCY_CONTAINER = 'DependencyContainer';

    /**
     * @var string
     */
    protected $classNamePattern;

    /**
     * @var string
     */
    protected $application;

    /**
     * @var string
     */
    protected $layer;

    /**
     * @var ClassResolver
     */
    private $resolver;

    /**
     * @var string
     */
    private $bundle;

    /**
     * @var bool
     */
    protected $isMagicCall = false;

    /**
     * @param string $bundle
     */
    public function __construct($bundle)
    {
        $this->bundle = $bundle;
    }

    /**
     * @var array
     */
    protected $baseClasses = [
        self::DEPENDENCY_CONTAINER,
    ];

    /**
     * @param string $method
     * @param array $arguments
     *
     * @return bool|object
     */
    public function __call($method, array $arguments = [])
    {
        $this->isMagicCall = true;

        if (strpos($method, self::METHOD_EXISTS) === 0) {
            $className = substr($method, strlen(self::METHOD_EXISTS));

            return $this->exists($className);
        }

        if (strpos($method, self::METHOD_CREATE) === 0) {
            $className = substr($method, strlen(self::METHOD_CREATE));

            if (count($arguments) > 0) {
                return $this->create($className, $arguments);
            } else {
                return $this->create($className);
            }
        }

    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function exists($class)
    {

//
        if (in_array($class, $this->baseClasses)) {
            $class = $this->getBundle() . $class;
        }
        if(!isset($this->application)){
            die('<pre><b>'.print_r('Property missing: '.get_class($this), true).'</b>'.PHP_EOL.__CLASS__.' '.__LINE__);
        }
                \SprykerFeature_Shared_Library_Log::log($class. ' - '.$this->application. ' - '.$this->getBundle(). ' - ' .$this->layer, 'exist.log');

        return Factory2::getInstance()->has($this->application, $this->getBundle(), $class, $this->layer);

        $class = $this->buildClassName($class);

        $resolver = $this->getResolver();

        return $resolver->canResolve($class, $this->getBundle());
    }

    /**
     * @param string $class
     *
     * @throws \Exception
     *
     * @return object
     */
    public function create($class)
    {

        $arguments = func_get_args();

        if (in_array($class, $this->baseClasses)) {
            $class = $this->getBundle().$class;
        }

        array_shift($arguments);

        if ($this->isMagicCall) {
            $arguments = (count($arguments) > 0) ? $arguments[0] : [];
        }
        $this->isMagicCall = false;
if(!isset($this->application)){
    die('<pre><b>'.print_r('Property missing: '.get_class($this), true).'</b>'.PHP_EOL.__CLASS__.' '.__LINE__);
}
        return Factory2::getInstance()->create($this->application, $this->getBundle(), $class, $this->layer, $arguments);
//
//        $class = $this->buildClassName($class);
//        $resolver = $this->getResolver();
//
//        return $resolver->resolve($class, $this->getBundle(), $arguments);
    }

    /**
     * @return ClassResolver
     */
    protected function getResolver()
    {
        $classResolver = new ClassResolver();
        $camelHumpClassResolver = new CamelHumpClassResolver($classResolver);
        $this->resolver = IdentityMapClassResolver::getInstance($camelHumpClassResolver);

        return $this->resolver;
    }

    /**
     * @return string
     */
    protected function getBundle()
    {
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

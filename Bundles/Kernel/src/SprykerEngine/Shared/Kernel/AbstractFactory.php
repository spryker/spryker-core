<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Kernel;

use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;

abstract class AbstractFactory implements FactoryInterface
{

    const SUFFIX_FACTORY = 'Factory';
    const METHOD_EXISTS = 'exists';
    const METHOD_CREATE = 'create';
    const DEPENDENCY_CONTAINER = 'DependencyContainer';

    /**
     * @var string
     */
    protected $application;

    /**
     * @var string
     */
    protected $layer;

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
        if (in_array($class, $this->baseClasses)) {
            $class = $this->getBundle() . $class;
        }

        return ClassMapFactory::getInstance()->has($this->application, $this->getBundle(), $class, $this->layer);
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
            $class = $this->getBundle() . $class;
        }

        array_shift($arguments);

        if ($this->isMagicCall) {
            $arguments = (count($arguments) > 0) ? $arguments[0] : [];
        }
        $this->isMagicCall = false;

        return ClassMapFactory::getInstance()->create($this->application, $this->getBundle(), $class, $this->layer, $arguments);
    }

    /**
     * @return string
     */
    protected function getBundle()
    {
        return $this->bundle;
    }

}

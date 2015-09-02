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
     * @var string
     */
    protected $bundle;

    /**
     * @var string
     */
    protected $layer;

    /**
     * @var string
     */
    protected $suffix;

    /**
     * @var string
     */
    protected $application;

    /**
     * @throws LocatorException
     */
    final public function __construct()
    {
        if (is_null($this->application)) {
            throw new LocatorException('Properties missing for: ' . get_class($this));
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
        return ClassMapFactory::getInstance()->create($this->application, $this->bundle, $this->suffix, $this->layer, [$bundle]);
    }

}

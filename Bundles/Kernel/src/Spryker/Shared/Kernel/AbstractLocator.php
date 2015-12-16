<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Kernel;

use Spryker\Shared\Kernel\Locator\LocatorInterface;
use Spryker\Shared\Kernel\Locator\LocatorException;

abstract class AbstractLocator implements LocatorInterface
{

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
        if ($this->application === null) {
            throw new LocatorException('Properties missing for: ' . get_class($this));
        }
    }

    /**
     * @param string $bundle
     * @param LocatorLocatorInterface $locator
     * @param string|null $className
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

}

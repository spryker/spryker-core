<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel;

use LogicException;
use Spryker\Shared\Kernel\Locator\LocatorInterface;

class BundleProxy
{
    const LOCATOR_MATCHER_SUFFIX = 'Matcher';

    /**
     * @var string
     */
    private $bundle;

    /**
     * @var \Spryker\Shared\Kernel\Locator\LocatorInterface[]
     */
    private $locator;

    /**
     * @var \Spryker\Shared\Kernel\Locator\LocatorMatcherInterface[]
     */
    private $locatorMatcher;

    /**
     * @var array
     */
    protected static $cache = [];

    /**
     * @param string $bundle
     *
     * @return $this
     */
    public function setBundle($bundle)
    {
        $this->bundle = $bundle;

        return $this;
    }

    /**
     * @param array $locator
     *
     * @return $this
     */
    public function setLocator(array $locator = [])
    {
        foreach ($locator as $aLocator) {
            $this->addLocator($aLocator);
        }

        return $this;
    }

    /**
     * @param \Spryker\Shared\Kernel\Locator\LocatorInterface $locator
     *
     * @throws \LogicException
     *
     * @return $this
     */
    public function addLocator(LocatorInterface $locator)
    {
        $locatorClass = get_class($locator);
        $matcherClass = $locatorClass . static::LOCATOR_MATCHER_SUFFIX;
        if (!class_exists($matcherClass)) {
            throw new LogicException(sprintf('Could not find a "%s"!', $matcherClass));
        }
        $matcher = new $matcherClass();

        $this->locator[] = $locator;
        $this->locatorMatcher[$locatorClass] = $matcher;

        return $this;
    }

    /**
     * @param string $method
     * @param array $arguments
     *
     * @throws \LogicException
     *
     * @return object
     */
    public function __call($method, $arguments)
    {
        $key = $this->bundle . '-' . $method;
        if (isset(static::$cache[$key])) {
            $locatedClassName = static::$cache[$key];

            return new $locatedClassName();
        }

        foreach ($this->locator as $locator) {
            $matcher = $this->locatorMatcher[get_class($locator)];
            if ($matcher->match($method)) {
                $located = $locator->locate(ucfirst($this->bundle));
                static::$cache[$key] = get_class($located);

                return $located;
            }
        }

        throw new LogicException(sprintf('Could not map method "%s" to a locator!', $method));
    }
}

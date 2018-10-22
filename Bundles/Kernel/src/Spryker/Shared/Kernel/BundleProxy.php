<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel;

use LogicException;
use Spryker\Shared\Kernel\Locator\LocatorInterface;

/**
 * @method \Spryker\Shared\Kernel\KernelConfig getSharedConfig()
 */
class BundleProxy
{
    private const LOCATOR_MATCHER_SUFFIX = 'Matcher';
    private const INSTANCE = 'instance';
    private const CLASS_NAME = 'className';

    use SharedConfigResolverAwareTrait;

    /**
     * @var string
     */
    private $moduleName;

    /**
     * @var \Spryker\Shared\Kernel\Locator\LocatorInterface[]
     */
    private $locator;

    /**
     * @var \Spryker\Shared\Kernel\Locator\LocatorMatcherInterface[]
     */
    private $locatorMatcher;

    /**
     * @var bool|null
     */
    private $isInstanceCacheEnabled;

    /**
     * @var array
     */
    private static $instanceCache = [];

    /**
     * @param string $moduleName
     *
     * @return $this
     */
    public function setBundle($moduleName)
    {
        $this->moduleName = $moduleName;

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
     * @param string $methodName
     * @param array $arguments
     *
     * @throws \LogicException
     *
     * @return object
     */
    public function __call(string $methodName, array $arguments)
    {
        $cacheKey = $this->buildCacheKey($methodName);

        if (isset(static::$instanceCache[$cacheKey])) {
            if ($this->isClassCacheEnabled()) {
                return static::$instanceCache[$cacheKey][static::INSTANCE];
            }

            return new static::$instanceCache[$cacheKey][static::CLASS_NAME]();
        }

        foreach ($this->locator as $locator) {
            $matcher = $this->locatorMatcher[get_class($locator)];
            if ($matcher->match($methodName)) {
                $located = $locator->locate(ucfirst($this->moduleName));

                if (!isset(static::$instanceCache[$cacheKey])) {
                    static::$instanceCache[$cacheKey] = [];
                }

                static::$instanceCache[$cacheKey][static::INSTANCE] = $located;
                static::$instanceCache[$cacheKey][static::CLASS_NAME] = get_class($located);

                return $located;
            }
        }

        throw new LogicException(sprintf('Could not map method "%s" to a locator!', $methodName));
    }

    /**
     * @return bool
     */
    private function isClassCacheEnabled(): bool
    {
        if ($this->isInstanceCacheEnabled === null) {
            $this->isInstanceCacheEnabled = $this->getSharedConfig()->isLocatorInstanceCacheEnabled();
        }

        return $this->isInstanceCacheEnabled;
    }

    /**
     * @param string $methodName
     *
     * @return string
     */
    private function buildCacheKey(string $methodName): string
    {
        return $this->moduleName . '-' . $methodName;
    }
}

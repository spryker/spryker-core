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
    use SharedConfigResolverAwareTrait;

    protected const LOCATOR_MATCHER_SUFFIX = 'Matcher';
    protected const INSTANCE = 'instance';
    protected const CLASS_NAME = 'className';

    /**
     * @var string
     */
    protected $moduleName;

    /**
     * @var \Spryker\Shared\Kernel\Locator\LocatorInterface[]
     */
    protected $locators = [];

    /**
     * @var \Spryker\Shared\Kernel\Locator\LocatorMatcherInterface[]
     */
    protected $locatorMatcherMap = [];

    /**
     * @var bool|null
     */
    protected $isInstanceCacheEnabled;

    /**
     * @var array
     */
    protected static $instanceCache = [];

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
     * @param array $locators
     *
     * @return $this
     */
    public function setLocators(array $locators = [])
    {
        foreach ($locators as $locator) {
            $this->addLocator($locator);
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

        $this->locators[] = $locator;
        $this->locatorMatcherMap[$locatorClass] = $matcher;

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

        foreach ($this->locators as $locator) {
            $matcher = $this->locatorMatcherMap[get_class($locator)];
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
    protected function isClassCacheEnabled(): bool
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
    protected function buildCacheKey(string $methodName): string
    {
        return $this->moduleName . '-' . $methodName;
    }
}

<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Kernel;

use SprykerEngine\Shared\Kernel\Locator\LocatorInterface;
use SprykerEngine\Shared\Kernel\Locator\LocatorMatcherInterface;

class BundleProxy
{

    const LOCATOR_MATCHER_SUFFIX = 'Matcher';

    /**
     * @var string
     */
    private $bundle;

    /**
     * @var LocatorLocatorInterface
     */
    private $locatorLocator;

    /**
     * @var LocatorInterface[]
     */
    private $locator;

    /**
     * @var LocatorMatcherInterface[]
     */
    private $locatorMatcher;

    /**
     * @param LocatorLocatorInterface $locatorLocator
     */
    public function __construct(LocatorLocatorInterface $locatorLocator)
    {
        $this->locatorLocator = $locatorLocator;
    }

    /**
     * @param string $bundle
     *
     * @return $this
     */
    public function setBundle($bundle)
    {
        $this->bundle = ucfirst($bundle);

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
     * @param LocatorInterface $locator
     *
     * @return $this
     */
    public function addLocator(LocatorInterface $locator)
    {
        $locatorClass = get_class($locator);
        $matcherClass = $locatorClass . self::LOCATOR_MATCHER_SUFFIX;
        if (!class_exists($matcherClass)) {
            throw new \LogicException(sprintf('Could not find a "%s"!', $matcherClass));
        }
        $matcher = new $matcherClass();

        $this->locator[] = $locator;
        $this->locatorMatcher[$locatorClass] = $matcher;

        return $this;
    }

    /**
     * TODO Check if performance is good enough here!?
     *
     * @param string $method
     * @param string $arguments
     *
     * @return object
     */
    public function __call($method, $arguments)
    {
        foreach ($this->locator as $locator) {
            $matcher = $this->locatorMatcher[get_class($locator)];
            if ($matcher->match($method)) {
                return $locator->locate($this->bundle, $this->locatorLocator, $matcher->filter($method));
            }
        }

        throw new \LogicException(sprintf('Could not map method "%s" to a locator!', $method));
    }

    /**
     * @return bool
     */
    public function hasFacade()
    {
        return $this->hasItem('facade');
    }

    /**
     * @return bool
     */
    public function hasQueryContainer()
    {
        return $this->hasItem('queryContainer');
    }

    /**
     * TODO Check if performance is good enough here!?
     *
     * @param $method
     *
     * @return bool
     */
    protected function hasItem($method)
    {
        foreach ($this->locator as $locator) {
            $matcher = $this->locatorMatcher[get_class($locator)];
            if ($matcher->match($method)) {
                return $locator->canLocate($this->bundle);
            }
        }

        return false;
    }

}

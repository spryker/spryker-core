<?php

namespace SprykerEngine\Yves\Kernel;

use SprykerEngine\Shared\Kernel\Locator\LocatorMatcherInterface;

/**
 * Class YvesLocatorMatcher
 * @package SprykerEngine\Yves\Kernel\Business
 */
class PluginLocatorMatcher implements LocatorMatcherInterface
{
    const METHOD_PREFIX = 'plugin';

    /**
     * @param string $method
     *
     * @return bool
     */
    public function match($method)
    {
        return strpos($method, self::METHOD_PREFIX) === 0;
    }

    /**
     * @param string $method
     *
     * @return string
     */
    public function filter($method)
    {
        return $method;
    }
}

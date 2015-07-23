<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Kernel\Communication;

use SprykerEngine\Shared\Kernel\Locator\LocatorMatcherInterface;

/**
 * Class YvesLocatorMatcher
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

<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Kernel;

use SprykerEngine\Shared\Kernel\Locator\LocatorMatcherInterface;

abstract class AbstractClientLocatorMatcher implements LocatorMatcherInterface
{

    const METHOD_SUFFIX = 'Client';

    /**
     * @param string $method
     *
     * @return bool
     */
    public function match($method)
    {
        return (strpos(strrev($method), strrev(self::METHOD_SUFFIX)) === 0);
    }

    /**
     * @param string $method
     *
     * @return string
     */
    public function filter($method)
    {
        return strrev(substr(strrev($method), strlen(self::METHOD_SUFFIX)));
    }

}

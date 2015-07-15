<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Shared\Kernel\Fixtures;

use SprykerEngine\Shared\Kernel\Locator\LocatorInterface;
use SprykerEngine\Shared\Kernel\Locator\LocatorMatcherInterface;

class LocatorWithMatcherMatcher implements LocatorMatcherInterface
{

    const NAME = 'locator';

    /**
     * @param string $method
     *
     * @return bool
     */
    public function match($method)
    {
        return (strpos($method, self::NAME) === 0);
    }

    /**
     * @param string $method
     *
     * @return string
     */
    public function filter($method)
    {
        return substr($method, strlen(self::NAME));
    }

}

<?php

namespace SprykerEngine\Zed\Kernel\Persistence\Propel;

use SprykerEngine\Shared\Kernel\Locator\LocatorMatcherInterface;

class SpyityLocatorMatcher implements LocatorMatcherInterface
{

    const METHOD_PREFIX = 'entity';

    /**
     * @param string $method
     *
     * @return bool
     */
    public function match($method)
    {
        return (strpos($method, self::METHOD_PREFIX) === 0);
    }

    /**
     * @param string $method
     *
     * @return string
     */
    public function filter($method)
    {
        return substr($method, strlen(self::METHOD_PREFIX));
    }

}

<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Persistence\Propel;

use SprykerEngine\Shared\Kernel\Locator\LocatorMatcherInterface;

class EntityLocatorMatcher implements LocatorMatcherInterface
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

<?php

namespace SprykerFeature\Client\KvStorage;

use SprykerEngine\Shared\Kernel\Locator\LocatorMatcherInterface;

class StorageLocatorMatcher implements LocatorMatcherInterface
{

    const METHOD_PREFIX = 'storage';

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

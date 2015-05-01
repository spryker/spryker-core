<?php

namespace SprykerEngine\Sdk\Kernel;

use SprykerEngine\Shared\Kernel\Locator\LocatorMatcherInterface;

/**
 * Class SdkLocatorMatcher
 * @package SprykerEngine\Yves\Kernel
 */
class SdkLocatorMatcher implements LocatorMatcherInterface
{

    const METHOD_PREFIX = 'sdk';

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

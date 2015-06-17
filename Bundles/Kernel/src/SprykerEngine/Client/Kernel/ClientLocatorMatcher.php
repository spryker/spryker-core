<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Client\Kernel;

use SprykerEngine\Shared\Kernel\Locator\LocatorMatcherInterface;

/**
 * Class ClientLocatorMatcher
 * @package SprykerEngine\Yves\Kernel
 */
class ClientLocatorMatcher implements LocatorMatcherInterface
{

    const METHOD_PREFIX = 'client';

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

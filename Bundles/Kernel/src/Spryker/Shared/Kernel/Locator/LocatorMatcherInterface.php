<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Kernel\Locator;

interface LocatorMatcherInterface
{

    /**
     * @param string $method
     *
     * @return bool
     */
    public function match($method);

    /**
     * @param string $method
     *
     * @return string
     */
    public function filter($method);

}

<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerTest\Shared\Kernel\Fixtures;

use Spryker\Shared\Kernel\Locator\LocatorInterface;

class LocatorWithoutMatcher implements LocatorInterface
{
    /**
     * @param string $bundle
     *
     * @return object
     */
    public function locate($bundle)
    {
    }
}

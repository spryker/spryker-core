<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Shared\Kernel\Fixtures;

use Spryker\Shared\Kernel\Locator\LocatorInterface;

class LocatorWithoutMatcher implements LocatorInterface
{

    /**
     * @param $bundle
     * @param string|null $className
     *
     * @return object
     */
    public function locate($bundle, $className = null)
    {
    }

}

<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Shared\Kernel\Fixtures;

use Spryker\Shared\Kernel\Locator\LocatorInterface;
use Spryker\Shared\Kernel\LocatorLocatorInterface;

class LocatorWithoutMatcher implements LocatorInterface
{

    /**
     * @param $bundle
     * @param LocatorLocatorInterface $locator
     * @param string|null $className
     *
     * @return object
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null)
    {
    }

    /**
     * @param string $bundle
     *
     * @return bool
     */
    public function canLocate($bundle)
    {
    }

}

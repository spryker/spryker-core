<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Shared\Kernel\Fixtures;

use SprykerEngine\Shared\Kernel\Locator\LocatorInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

class LocatorWithoutMatcher implements LocatorInterface
{

    /**
     * @param $bundle
     * @param LocatorLocatorInterface $locator
     * @param null|string $className
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

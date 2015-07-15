<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Shared\Kernel\Fixtures;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Shared\Kernel\AbstractLocator;

class MissingPropertyLocator extends AbstractLocator
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

}

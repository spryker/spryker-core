<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Kernel\Locator;

use Spryker\Shared\Kernel\LocatorLocatorInterface;

interface LocatorInterface
{

    /**
     * @param string $bundle
     * @param LocatorLocatorInterface $locator
     * @param string|null $className
     *
     * @return object
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null);

    /**
     * @param string $bundle
     *
     * @return bool
     */
    public function canLocate($bundle);

}

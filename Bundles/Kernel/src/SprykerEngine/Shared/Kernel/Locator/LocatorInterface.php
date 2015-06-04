<?php

namespace SprykerEngine\Shared\Kernel\Locator;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

interface LocatorInterface
{

    /**
     * @param string $bundle
     * @param LocatorLocatorInterface $locator
     * @param string $className
     *
     * @return object
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null);

}

<?php

namespace SprykerEngine\Shared\Kernel\Locator;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

interface LocatorInterface
{

    /**
     * @param $bundle
     * @param LocatorLocatorInterface $locator
     * @param null $className
     *
     * @return object
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null);

}

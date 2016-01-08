<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Kernel\Locator;

interface LocatorInterface
{

    /**
     * @param string $bundle
     * @param string|null $className
     *
     * @return object
     */
    public function locate($bundle, $className = null);

}

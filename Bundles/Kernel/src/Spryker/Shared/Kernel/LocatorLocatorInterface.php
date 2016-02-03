<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Kernel;

interface LocatorLocatorInterface
{

    /**
     * @param string $bundle
     * @param array $arguments
     *
     * @return \Spryker\Shared\Kernel\BundleProxy
     */
    public function __call($bundle, array $arguments = null);

}

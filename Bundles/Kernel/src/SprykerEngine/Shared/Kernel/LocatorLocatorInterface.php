<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Kernel;

interface LocatorLocatorInterface
{

    /**
     * @param string $bundle
     * @param array  $arguments
     *
     * @return BundleProxy
     */
    public function __call($bundle, array $arguments = null);

}

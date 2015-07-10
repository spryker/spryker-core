<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business\InstalledPackages;

use Generated\Shared\Maintenance\InstalledPackagesInterface;

interface InstalledPackageFinderInterface
{

    /**
     * @return InstalledPackagesInterface
     */
    public function findInstalledPackages();

}

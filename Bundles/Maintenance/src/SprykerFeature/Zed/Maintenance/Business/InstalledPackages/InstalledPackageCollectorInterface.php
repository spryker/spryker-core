<?php

namespace SprykerFeature\Zed\Maintenance\Business\InstalledPackages;

use Generated\Shared\Maintenance\InstalledPackagesInterface;

interface InstalledPackageCollectorInterface
{

    /**
     * @return InstalledPackagesInterface
     */
    public function getInstalledPackages();

}

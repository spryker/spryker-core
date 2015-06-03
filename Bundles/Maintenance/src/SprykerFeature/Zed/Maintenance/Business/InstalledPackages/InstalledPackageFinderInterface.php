<?php

namespace SprykerFeature\Zed\Maintenance\Business\InstalledPackages;

interface InstalledPackageFinderInterface
{

    /**
     * @return InstalledPackageCollectionInterface
     */
    public function findInstalledPackages();

}

<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business\InstalledPackages;

use Generated\Shared\Transfer\InstalledPackagesTransfer;

interface InstalledPackageFinderInterface
{

    /**
     * @return InstalledPackagesTransfer
     */
    public function findInstalledPackages();

}

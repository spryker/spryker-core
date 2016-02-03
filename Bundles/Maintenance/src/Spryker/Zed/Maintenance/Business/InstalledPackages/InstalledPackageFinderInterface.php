<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\InstalledPackages;

interface InstalledPackageFinderInterface
{

    /**
     * @return \Generated\Shared\Transfer\InstalledPackagesTransfer
     */
    public function findInstalledPackages();

}

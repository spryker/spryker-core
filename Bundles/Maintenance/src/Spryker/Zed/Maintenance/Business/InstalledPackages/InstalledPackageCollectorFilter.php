<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Maintenance\Business\InstalledPackages;

use Generated\Shared\Transfer\InstalledPackagesTransfer;
use Generated\Shared\Transfer\InstalledPackageTransfer;

class InstalledPackageCollectorFilter implements InstalledPackageCollectorInterface
{

    /**
     * @var \Spryker\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollectorInterface
     */
    private $installedPackageCollector;

    /**
     * @param \Spryker\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollectorInterface $installedPackageCollector
     */
    public function __construct(InstalledPackageCollectorInterface $installedPackageCollector)
    {
        $this->installedPackageCollector = $installedPackageCollector;
    }

    /**
     * @return \Generated\Shared\Transfer\InstalledPackagesTransfer
     */
    public function getInstalledPackages()
    {
        $packages = $this->installedPackageCollector->getInstalledPackages()->getPackages();
        $packageList = [];
        $filteredPackages = new InstalledPackagesTransfer();
        foreach ($packages as $package) {
            $key = $this->getPackageKey($package);
            if (!in_array($key, $packageList)) {
                $packageList[] = $key;
                $filteredPackages->addPackage($package);
            }
        }

        return $filteredPackages;
    }

    /**
     * @param \Generated\Shared\Transfer\InstalledPackageTransfer $package
     *
     * @return string
     */
    private function getPackageKey(InstalledPackageTransfer $package)
    {
        return $package->getName() . $package->getVersion() . implode('', $package->getLicense());
    }

}

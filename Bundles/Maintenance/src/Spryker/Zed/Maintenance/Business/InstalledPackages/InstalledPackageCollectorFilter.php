<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\InstalledPackages;

use Generated\Shared\Transfer\InstalledPackageTransfer;
use Generated\Shared\Transfer\InstalledPackagesTransfer;

class InstalledPackageCollectorFilter implements InstalledPackageCollectorInterface
{

    /**
     * @var InstalledPackageCollectorInterface
     */
    private $installedPackageCollector;

    /**
     * @param InstalledPackageCollectorInterface $installedPackageCollector
     */
    public function __construct(InstalledPackageCollectorInterface $installedPackageCollector)
    {
        $this->installedPackageCollector = $installedPackageCollector;
    }

    /**
     * @return InstalledPackagesTransfer
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
     * @param InstalledPackageTransfer $package
     *
     * @return string
     */
    private function getPackageKey(InstalledPackageTransfer $package)
    {
        return $package->getName() . $package->getVersion() . implode('', $package->getLicense());
    }

}

<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business\InstalledPackages;

use Generated\Shared\Maintenance\InstalledPackageInterface;
use Generated\Shared\Maintenance\InstalledPackagesInterface;
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
     * @return InstalledPackagesInterface
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
     * @param InstalledPackageInterface $package
     *
     * @return string
     */
    private function getPackageKey(InstalledPackageInterface $package)
    {
        return $package->getName() . $package->getVersion() . implode('', $package->getLicense());
    }

}

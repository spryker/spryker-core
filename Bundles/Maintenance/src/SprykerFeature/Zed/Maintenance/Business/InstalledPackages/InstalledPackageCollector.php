<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business\InstalledPackages;

use Generated\Shared\Transfer\InstalledPackagesTransfer;

class InstalledPackageCollector implements InstalledPackageCollectorInterface
{

    /**
     * @var InstalledPackageFinderInterface[]
     */
    private $installedPackageFinder;

    /**
     * @var InstalledPackagesTransfer
     */
    private $installedPackageCollection;

    /**
     * @param InstalledPackagesTransfer $installedPackageCollection
     * @param array $installedPackageFinder
     */
    public function __construct(InstalledPackagesTransfer $installedPackageCollection, array $installedPackageFinder)
    {
        $this->installedPackageCollection = $installedPackageCollection;
        $this->installedPackageFinder = $installedPackageFinder;
    }

    /**
     * @return InstalledPackagesTransfer
     */
    public function getInstalledPackages()
    {
        $this->executeFinder();

        return $this->installedPackageCollection;
    }

    private function executeFinder()
    {
        foreach ($this->installedPackageFinder as $finder) {
            $finder->findInstalledPackages();
        }
    }

}

<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business\InstalledPackages;

use Generated\Shared\Maintenance\InstalledPackagesInterface;

class InstalledPackageCollector implements InstalledPackageCollectorInterface
{

    /**
     * @var InstalledPackageFinderInterface[]
     */
    private $installedPackageFinder;

    /**
     * @var InstalledPackagesInterface
     */
    private $installedPackageCollection;

    /**
     * @param InstalledPackagesInterface $installedPackageCollection
     * @param array $installedPackageFinder
     */
    public function __construct(InstalledPackagesInterface $installedPackageCollection, array $installedPackageFinder)
    {
        $this->installedPackageCollection = $installedPackageCollection;
        $this->installedPackageFinder = $installedPackageFinder;
    }

    /**
     * @return InstalledPackagesInterface
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

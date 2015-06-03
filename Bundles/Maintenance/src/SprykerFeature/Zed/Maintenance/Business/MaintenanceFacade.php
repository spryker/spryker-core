<?php

namespace SprykerFeature\Zed\Maintenance\Business;

use Generated\Shared\Maintenance\InstalledPackagesInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method MaintenanceDependencyContainer getDependencyContainer()
 */
class MaintenanceFacade extends AbstractFacade
{

    /**
     * @return InstalledPackagesInterface
     */
    public function getInstalledPackages()
    {
        return $this->getDependencyContainer()->createPackageCollector()->getInstalledPackages();
    }

    /**
     * @param InstalledPackagesInterface $installedPackages
     */
    public function writeInstalledPackagesToMarkDownFile(InstalledPackagesInterface $installedPackages)
    {
        $this->getDependencyContainer()->createMarkDownWriter($installedPackages)->write();
    }
    
}

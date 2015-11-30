<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business;

use Generated\Shared\Transfer\InstalledPackagesTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method MaintenanceDependencyContainer getDependencyContainer()
 */
class MaintenanceFacade extends AbstractFacade
{

    /**
     * @return InstalledPackagesTransfer
     */
    public function getInstalledPackages()
    {
        return $this->getDependencyContainer()->createPackageCollector()->getInstalledPackages();
    }

    /**
     * @param InstalledPackagesTransfer $installedPackages
     */
    public function writeInstalledPackagesToMarkDownFile(InstalledPackagesTransfer $installedPackages)
    {
        $this->getDependencyContainer()->createMarkDownWriter($installedPackages)->write();
    }

    /**
     * @param string $bundleName
     *
     * @return array
     */
    public function showOutgoingDependenciesForBundle($bundleName)
    {
        return $this->getDependencyContainer()->createDependencyBundleParser()->parseOutgoingDependencies($bundleName);
    }

    /**
     * @param string $bundleName
     *
     * @return array
     */
    public function showIncomingDependenciesForBundle($bundleName)
    {
        return $this->getDependencyContainer()->createDependencyManager()->parseIncomingDependencies($bundleName);
    }

    public function drawDependencyGraph($bundleName)
    {
        return $this->getDependencyContainer()->createDependencyGraph()->draw($bundleName);
    }

    /**
     * @todo move this to propel bundle
     *
     * @return bool
     */
    public function cleanPropelMigration()
    {
        return $this->getDependencyContainer()->createPropelMigrationCleaner()->clean();
    }

    /**
     * @return array
     */
    public function getAllBundles()
    {
        return $this->getDependencyContainer()->createDependencyManager()->collectAllBundles();
    }

}

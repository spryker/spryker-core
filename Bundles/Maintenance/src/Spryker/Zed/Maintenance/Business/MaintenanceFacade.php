<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business;

use Generated\Shared\Transfer\InstalledPackagesTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method MaintenanceDependencyContainer getBusinessFactory()
 */
class MaintenanceFacade extends AbstractFacade
{

    /**
     * @return InstalledPackagesTransfer
     */
    public function getInstalledPackages()
    {
        return $this->getBusinessFactory()->createPackageCollector()->getInstalledPackages();
    }

    /**
     * @param InstalledPackagesTransfer $installedPackages
     *
     * @return void
     */
    public function writeInstalledPackagesToMarkDownFile(InstalledPackagesTransfer $installedPackages)
    {
        $this->getBusinessFactory()->createMarkDownWriter($installedPackages)->write();
    }

    /**
     * @param string $bundleName
     *
     * @return array
     */
    public function showOutgoingDependenciesForBundle($bundleName)
    {
        return $this->getBusinessFactory()->createDependencyBundleParser()->parseOutgoingDependencies($bundleName);
    }

    /**
     * @param string $bundleName
     *
     * @return array
     */
    public function showIncomingDependenciesForBundle($bundleName)
    {
        return $this->getBusinessFactory()->createDependencyManager()->parseIncomingDependencies($bundleName);
    }

    public function drawDependencyGraph($bundleName)
    {
        return $this->getBusinessFactory()->createDependencyGraph()->draw($bundleName);
    }

    /**
     * @todo move this to propel bundle
     *
     * @return bool
     */
    public function cleanPropelMigration()
    {
        return $this->getBusinessFactory()->createPropelMigrationCleaner()->clean();
    }

    /**
     * @return array
     */
    public function getAllBundles()
    {
        return $this->getBusinessFactory()->createDependencyManager()->collectAllBundles();
    }

}

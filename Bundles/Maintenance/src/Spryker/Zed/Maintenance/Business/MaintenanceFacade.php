<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business;

use Generated\Shared\Transfer\InstalledPackagesTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method MaintenanceBusinessFactory getFactory()
 */
class MaintenanceFacade extends AbstractFacade
{

    /**
     * @return InstalledPackagesTransfer
     */
    public function getInstalledPackages()
    {
        return $this->getFactory()->createPackageCollector()->getInstalledPackages();
    }

    /**
     * @param InstalledPackagesTransfer $installedPackages
     *
     * @return void
     */
    public function writeInstalledPackagesToMarkDownFile(InstalledPackagesTransfer $installedPackages)
    {
        $this->getFactory()->createMarkDownWriter($installedPackages)->write();
    }

    /**
     * @param string $bundleName
     *
     * @return array
     */
    public function showOutgoingDependenciesForBundle($bundleName)
    {
        return $this->getFactory()->createDependencyBundleParser()->parseOutgoingDependencies($bundleName);
    }

    /**
     * @param string $bundleName
     *
     * @return array
     */
    public function showIncomingDependenciesForBundle($bundleName)
    {
        return $this->getFactory()->createDependencyManager()->parseIncomingDependencies($bundleName);
    }

    public function drawDependencyGraph($bundleName)
    {
        return $this->getFactory()->createDependencyGraph()->draw($bundleName);
    }

    /**
     * @todo move this to propel bundle
     *
     * @return bool
     */
    public function cleanPropelMigration()
    {
        return $this->getFactory()->createPropelMigrationCleaner()->clean();
    }

    /**
     * @return array
     */
    public function getAllBundles()
    {
        return $this->getFactory()->createDependencyManager()->collectAllBundles();
    }

}

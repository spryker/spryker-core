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
     * @return \Generated\Shared\Transfer\InstalledPackagesTransfer
     */
    public function getInstalledPackages()
    {
        return $this->getFactory()->createPackageCollector()->getInstalledPackages();
    }

    /**
     * @param \Generated\Shared\Transfer\InstalledPackagesTransfer $installedPackages
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

    /**
     * @param string $application
     * @param string $bundle
     * @param string $layer
     *
     * @return void
     */
    public function buildDependencyTree($application, $bundle, $layer)
    {
        $this->getFactory()->createDependencyTreeBuilder($application, $bundle, $layer)->buildDependencyTree();
    }

    /**
     * @param string|bool $bundleToView
     *
     * @return bool
     */
    public function drawDetailedDependencyTreeGraph($bundleToView)
    {
        return $this->getFactory()->createDetailedDependencyGraphBuilder($bundleToView)->build();
    }

    /**
     * @param string|bool $bundleToView
     *
     * @return bool
     */
    public function drawSimpleDependencyTreeGraph($bundleToView)
    {
        return $this->getFactory()->createSimpleDependencyGraphBuilder($bundleToView)->build();
    }

    /**
     * @return bool
     */
    public function getAdjacencyMatrixData()
    {
        return $this->getFactory()->createAdjacencyMatrixBuilder()->build();
    }

    /**
     * @return array
     */
    public function getDependencyViolations()
    {
        return $this->getFactory()->createDependencyViolationChecker()->getDependencyViolations();
    }

    /**
     * @return array
     */
    public function getEngineBundleList()
    {
        return $this->getFactory()->createEngineBundleList();
    }

    /**
     * @return void
     */
    public function updateComposerJsonInBundles()
    {
        $this->getFactory()->createComposerJsonUpdater()->update();
    }

}

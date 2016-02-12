<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business;

use Generated\Shared\Transfer\InstalledPackagesTransfer;

interface MaintenanceFacadeInterface
{

    /**
     * @return \Generated\Shared\Transfer\InstalledPackagesTransfer
     */
    public function getInstalledPackages();

    /**
     * @param \Generated\Shared\Transfer\InstalledPackagesTransfer $installedPackages
     *
     * @return void
     */
    public function writeInstalledPackagesToMarkDownFile(InstalledPackagesTransfer $installedPackages);

    /**
     * @param string $bundleName
     *
     * @return array
     */
    public function showOutgoingDependenciesForBundle($bundleName);

    /**
     * @param string $bundleName
     *
     * @return array
     */
    public function showIncomingDependenciesForBundle($bundleName);

    public function drawDependencyGraph($bundleName);

    /**
     * @todo move this to propel bundle
     *
     * @return bool
     */
    public function cleanPropelMigration();

    /**
     * @return array
     */
    public function getAllBundles();

    /**
     * @param string $application
     * @param string $bundle
     * @param string $layer
     *
     * @return void
     */
    public function buildDependencyTree($application, $bundle, $layer);

    /**
     * @param string|bool $bundleToView
     *
     * @return bool
     */
    public function drawDetailedDependencyTreeGraph($bundleToView);

    /**
     * @param string|bool $bundleToView
     *
     * @return bool
     */
    public function drawSimpleDependencyTreeGraph($bundleToView);

    /**
     * @return bool
     */
    public function getAdjacencyMatrixData();

    /**
     * @return array
     */
    public function getDependencyViolations();

    /**
     * @return array
     */
    public function getEngineBundleList();

    /**
     * @return void
     */
    public function updateComposerJsonInBundles();

}

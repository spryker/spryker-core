<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Maintenance\Business;

use Generated\Shared\Transfer\InstalledPackagesTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Maintenance\Business\MaintenanceBusinessFactory getFactory()
 */
class MaintenanceFacade extends AbstractFacade implements MaintenanceFacadeInterface
{

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\InstalledPackagesTransfer
     */
    public function getInstalledPackages()
    {
        return $this->getFactory()->createPackageCollector()->getInstalledPackages();
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\InstalledPackagesTransfer $installedPackages
     *
     * @return void
     */
    public function writeInstalledPackagesToMarkDownFile(InstalledPackagesTransfer $installedPackages)
    {
        $this->getFactory()->createMarkDownWriter($installedPackages)->write();
    }

    /**
     * @api
     *
     * @param string $bundleName
     *
     * @return array
     */
    public function showOutgoingDependenciesForBundle($bundleName)
    {
        return $this->getFactory()->createDependencyBundleParser()->parseOutgoingDependencies($bundleName);
    }

    /**
     * @api
     *
     * @param string $bundleName
     *
     * @return array
     */
    public function showIncomingDependenciesForBundle($bundleName)
    {
        return $this->getFactory()->createDependencyManager()->parseIncomingDependencies($bundleName);
    }

    /**
     * @api
     *
     * @return array
     */
    public function getAllBundles()
    {
        return $this->getFactory()->createDependencyManager()->collectAllBundles();
    }

    /**
     * @api
     *
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
     * @api
     *
     * @param string|bool $bundleToView
     *
     * @return bool
     */
    public function drawDetailedDependencyTreeGraph($bundleToView)
    {
        return $this->getFactory()->createDetailedDependencyGraphBuilder($bundleToView)->build();
    }

    /**
     * @api
     *
     * @param string|bool $bundleToView
     *
     * @return bool
     */
    public function drawSimpleDependencyTreeGraph($bundleToView)
    {
        return $this->getFactory()->createSimpleDependencyGraphBuilder($bundleToView)->build();
    }

    /**
     * @api
     *
     * @param string|bool $bundleToView
     *
     * @return bool
     */
    public function drawExternalDependencyTreeGraph($bundleToView)
    {
        return $this->getFactory()->createExternalDependencyGraphBuilder($bundleToView)->build();
    }

    /**
     * @api
     *
     * @return bool
     */
    public function getAdjacencyMatrixData()
    {
        return $this->getFactory()->createAdjacencyMatrixBuilder()->build();
    }

    /**
     * @api
     *
     * @return array
     */
    public function getDependencyViolations()
    {
        return $this->getFactory()->createDependencyViolationChecker()->getDependencyViolations();
    }

    /**
     * @api
     *
     * @return array
     */
    public function getEngineBundleList()
    {
        return $this->getFactory()->getEngineBundleList();
    }

    /**
     * @api
     *
     * @return void
     */
    public function updateComposerJsonInBundles()
    {
        $this->getFactory()->createComposerJsonUpdater()->update();
    }

    /**
     * @api
     *
     * @return array
     */
    public function getExternalDependencyTree()
    {
        return $this->getFactory()->createExternalDependencyTree();
    }

}

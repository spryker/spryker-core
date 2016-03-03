<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentBusinessFactory getFactory()
 */
class DevelopmentFacade extends AbstractFacade implements DevelopmentFacadeInterface
{

    /**
     * @api
     *
     * @param string|null $bundle
     * @param array $options
     *
     * @return int Exit code
     */
    public function checkCodeStyle($bundle = null, array $options = [])
    {
        return $this->getFactory()->createCodeStyleSniffer()->checkCodeStyle($bundle, $options);
    }

    /**
     * @api
     *
     * @param string|null $bundle
     * @param array $options
     *
     * @return void
     */
    public function runTest($bundle, array $options = [])
    {
        $this->getFactory()->createCodeTester()->runTest($bundle, $options);
    }

    /**
     * @api
     *
     * @param string|null $bundle
     *
     * @return int Exit code
     */
    public function runPhpMd($bundle)
    {
        return $this->getFactory()->createPhpMdRunner()->run($bundle);
    }

    /**
     * @api
     *
     * @param string $bundle
     * @param string $toBundle
     *
     * @return void
     */
    public function createBridge($bundle, $toBundle)
    {
        $this->getFactory()->createBridgeBuilder()->build($bundle, $toBundle);
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
     * @return array
     */
    public function getExternalDependencyTree()
    {
        return $this->getFactory()->createExternalDependencyTree();
    }

}

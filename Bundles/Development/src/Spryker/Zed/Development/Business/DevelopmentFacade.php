<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business;

use Generated\Shared\Transfer\BundleDependencyCollectionTransfer;
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
     * @param array $options
     *
     * @return int Exit code
     */
    public function runPhpMd($bundle, array $options = [])
    {
        return $this->getFactory()->createPhpMdRunner()->run($bundle, $options);
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
     * @param string $module
     * @param array $options
     *
     * @return void
     */
    public function createModule($module, $options)
    {
        $this->getFactory()->createModuleBuilder()->build($module, $options);
    }

    /**
     * @api
     *
     * @param array $bundles
     * @param bool $dryRun
     *
     * @return array
     */
    public function updateComposerJsonInBundles(array $bundles, $dryRun = false)
    {
        return $this->getFactory()->createComposerJsonUpdater()->update($bundles, $dryRun);
    }

    /**
     * @api
     *
     * @param string $bundleName
     *
     * @return \Generated\Shared\Transfer\BundleDependencyCollectionTransfer
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
     * @return array
     */
    public function calculateStability()
    {
        return $this->getFactory()->createStabilityCalculator()->calculateStability();
    }

    /**
     * @api
     *
     * @param string|bool $bundleToView
     * @param array $excludedBundles
     * @param bool $showIncomingDependencies
     *
     * @return string
     */
    public function drawOutgoingDependencyTreeGraph($bundleToView, array $excludedBundles = [], $showIncomingDependencies = false)
    {
        return $this->getFactory()->createOutgoingDependencyGraphBuilder($bundleToView, $excludedBundles)->build($showIncomingDependencies);
    }

    /**
     * @api
     *
     * @param string|bool $bundleToView
     *
     * @return string
     */
    public function drawDetailedDependencyTreeGraph($bundleToView)
    {
        return $this->getFactory()->createDetailedDependencyGraphBuilder($bundleToView)->build();
    }

    /**
     * @api
     *
     * @param bool $showEngineBundle
     * @param string|bool $bundleToView
     *
     * @return string
     */
    public function drawSimpleDependencyTreeGraph($showEngineBundle, $bundleToView)
    {
        return $this->getFactory()->createSimpleDependencyGraphBuilder($showEngineBundle, $bundleToView)->build();
    }

    /**
     * @api
     *
     * @param string $bundleToView
     *
     * @return string
     */
    public function drawExternalDependencyTreeGraph($bundleToView)
    {
        return $this->getFactory()->createExternalDependencyGraphBuilder($bundleToView)->build();
    }

    /**
     * @api
     *
     * @return array
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

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\BundleDependencyCollectionTransfer $bundleDependencyCollectionTransfer
     *
     * @return array
     */
    public function getComposerDependencyComparison(BundleDependencyCollectionTransfer $bundleDependencyCollectionTransfer)
    {
        return $this->getFactory()->createComposerDependencyParser()->getComposerDependencyComparison($bundleDependencyCollectionTransfer);
    }

    /**
     * @api
     *
     * @return void
     */
    public function generateYvesIdeAutoCompletion()
    {
        $this->getFactory()->createYvesIdeAutoCompletionWriter()->writeCompletionFiles();
    }

    /**
     * @api
     *
     * @return void
     */
    public function generateZedIdeAutoCompletion()
    {
        $this->getFactory()->createZedIdeAutoCompletionWriter()->writeCompletionFiles();
    }

    /**
     * @api
     *
     * @return void
     */
    public function generateClientIdeAutoCompletion()
    {
        $this->getFactory()->createClientIdeAutoCompletionWriter()->writeCompletionFiles();
    }

    /**
     * @api
     *
     * @return void
     */
    public function generateServiceIdeAutoCompletion()
    {
        $this->getFactory()->createServiceIdeAutoCompletionWriter()->writeCompletionFiles();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $directory
     * @param array $options
     *
     * @return array
     */
    public function runArchitectureSniffer($directory, array $options = [])
    {
        return $this->getFactory()->createArchitectureSniffer()->run($directory, $options);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return array
     */
    public function listAllBundles()
    {
        return $this->getFactory()->createArchitectureBundleFinder()->find();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return array
     */
    public function getArchitectureRules()
    {
        return $this->getFactory()->createArchitectureSniffer()->getRules();
    }
}

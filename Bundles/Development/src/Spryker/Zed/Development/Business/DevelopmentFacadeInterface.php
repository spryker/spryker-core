<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business;

use Generated\Shared\Transfer\BundleDependencyCollectionTransfer;

interface DevelopmentFacadeInterface
{

    /**
     * @api
     *
     * @param string|null $bundle
     * @param array $options
     *
     * @return void
     */
    public function checkCodeStyle($bundle = null, array $options = []);

    /**
     * @api
     *
     * @param string|null $bundle
     * @param array $options
     *
     * @return void
     */
    public function runTest($bundle, array $options = []);

    /**
     * @api
     *
     * @param string|null $bundle
     *
     * @return void
     */
    public function runPhpMd($bundle);

    /**
     * @api
     *
     * @param string $bundle
     * @param string $toBundle
     *
     * @return void
     */
    public function createBridge($bundle, $toBundle);

    /**
     * @api
     *
     * @param string $bundle
     * @param array $options
     *
     * @return void
     */
    public function createBundle($bundle, $options);

    /**
     * @api
     *
     * @param string $bundleName
     *
     * @return array
     */
    public function showOutgoingDependenciesForBundle($bundleName);

    /**
     * @api
     *
     * @param string $bundleName
     *
     * @return array
     */
    public function showIncomingDependenciesForBundle($bundleName);

    /**
     * @api
     *
     * @return array
     */
    public function getAllBundles();

    /**
     * @api
     *
     * @param string $application
     * @param string $bundle
     * @param string $layer
     *
     * @return void
     */
    public function buildDependencyTree($application, $bundle, $layer);

    /**
     * Specification:
     * - Calculates the stability of each bundle.

     * @api
     *
     * @return array
     */
    public function calculateStability();

    /**
     * @api
     *
     * @param string|bool $bundleToView
     * @param array $excludedBundles
     * @param bool $showIncomingDependencies
     *
     * @return string
     */
    public function drawOutgoingDependencyTreeGraph($bundleToView, array $excludedBundles = [], $showIncomingDependencies = false);

    /**
     * @api
     *
     * @param string|bool $bundleToView
     *
     * @return string
     */
    public function drawDetailedDependencyTreeGraph($bundleToView);

    /**
     * @api
     *
     * @param bool $showEngineBundle
     * @param string|bool $bundleToView
     *
     * @return string
     */
    public function drawSimpleDependencyTreeGraph($showEngineBundle, $bundleToView);

    /**
     * @api
     *
     * @param string $bundleToView
     *
     * @return string
     */
    public function drawExternalDependencyTreeGraph($bundleToView);

    /**
     * @api
     *
     * @return bool
     */
    public function getAdjacencyMatrixData();

    /**
     * @api
     *
     * @return array
     */
    public function getDependencyViolations();

    /**
     * @api
     *
     * @return array
     */
    public function getEngineBundleList();

    /**
     * @api
     *
     * @param array $bundles
     *
     * @return void
     */
    public function updateComposerJsonInBundles(array $bundles);

    /**
     * @api
     *
     * @return array
     */
    public function getExternalDependencyTree();

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\BundleDependencyCollectionTransfer $bundleDependencyCollectionTransfer
     *
     * @return array
     */
    public function getComposerDependencyComparison(BundleDependencyCollectionTransfer $bundleDependencyCollectionTransfer);

    /**
     * @api
     *
     * @return void
     */
    public function generateYvesIdeAutoCompletion();

    /**
     * @api
     *
     * @return void
     */
    public function generateZedIdeAutoCompletion();

    /**
     * @api
     *
     * @return void
     */
    public function generateClientIdeAutoCompletion();

    /**
     * @api
     *
     * @return void
     */
    public function generateServiceIdeAutoCompletion();

    /**
     * Run the architecture sniffer against the given bundle and returns the violations
     *
     * @api
     *
     * @param string $directory
     *
     * @return array
     */
    public function runArchitectureSniffer($directory);

    /**
     * Returns a list of all bundles in project and core namespaces
     *
     * @api
     *
     * @return array
     */
    public function listAllBundles();

    /**
     * Returns all architecture rules
     *
     * @api
     *
     * @return array
     */
    public function getArchitectureRules();

}

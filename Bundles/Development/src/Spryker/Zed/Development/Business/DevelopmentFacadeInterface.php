<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business;

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
     * @param string $bundleName
     * @param array $dependencies
     *
     * @return array
     */
    public function getComposerDependencyComparison($bundleName, array $dependencies);

}

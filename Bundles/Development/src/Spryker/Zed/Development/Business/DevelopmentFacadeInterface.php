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
     * @param string|null $module
     * @param array $options
     *
     * @return void
     */
    public function checkCodeStyle($module = null, array $options = []);

    /**
     * @api
     *
     * @param string|null $module
     * @param array $options
     *
     * @return void
     */
    public function runTest($module, array $options = []);

    /**
     * @api
     *
     * @param string|null $module
     *
     * @return void
     */
    public function runPhpMd($module);

    /**
     * @api
     *
     * @param string $module
     * @param string $toModule
     *
     * @return void
     */
    public function createBridge($module, $toModule);

    /**
     * @api
     *
     * @param string $module
     * @param array $options
     *
     * @return void
     */
    public function createModule($module, $options);

    /**
     * @api
     *
     * @param string $moduleName
     *
     * @return \Generated\Shared\Transfer\BundleDependencyCollectionTransfer
     */
    public function showOutgoingDependenciesForModule($moduleName);

    /**
     * @api
     *
     * @param string $moduleName
     *
     * @return array
     */
    public function showIncomingDependenciesForModule($moduleName);

    /**
     * @api
     *
     * @return array
     */
    public function getAllModules();

    /**
     * Specification:
     * - Builds the dependency tree for all modules if * is used as $module.
     * - Builds the dependency tree for specific module if $module is name of a module.
     *
     * @api
     *
     * @param string $module
     *
     * @return void
     */
    public function buildDependencyTree(string $module);

    /**
     * Specification:
     * - Calculates the stability of each module.

     * @api
     *
     * @return array
     */
    public function calculateStability();

    /**
     * @api
     *
     * @param string|bool $moduleToView
     * @param array $excludedModules
     * @param bool $showIncomingDependencies
     *
     * @return string
     */
    public function drawOutgoingDependencyTreeGraph($moduleToView, array $excludedModules = [], $showIncomingDependencies = false);

    /**
     * @api
     *
     * @param string|bool $moduleToView
     *
     * @return string
     */
    public function drawDetailedDependencyTreeGraph($moduleToView);

    /**
     * @api
     *
     * @param bool $showEngineModule
     * @param string|bool $moduleToView
     *
     * @return string
     */
    public function drawSimpleDependencyTreeGraph($showEngineModule, $moduleToView);

    /**
     * @api
     *
     * @param string $moduleToView
     *
     * @return string
     */
    public function drawExternalDependencyTreeGraph($moduleToView);

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
     * @param array $modules
     * @param bool $dryRun
     *
     * @return array
     */
    public function updateComposerJsonInModules(array $modules, $dryRun = false);

    /**
     * @api
     *
     * @return array
     */
    public function getExternalDependencyTree();

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\BundleDependencyCollectionTransfer $moduleDependencyCollectionTransfer
     *
     * @return array
     */
    public function getComposerDependencyComparison(BundleDependencyCollectionTransfer $moduleDependencyCollectionTransfer);

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

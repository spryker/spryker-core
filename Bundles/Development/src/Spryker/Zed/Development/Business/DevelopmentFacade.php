<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business;

use Generated\Shared\Transfer\BundleDependencyCollectionTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentBusinessFactory getFactory()
 */
class DevelopmentFacade extends AbstractFacade implements DevelopmentFacadeInterface
{
    /**
     * @api
     *
     * @param string|null $module
     * @param array $options
     *
     * @return int Exit code
     */
    public function checkCodeStyle($module = null, array $options = [])
    {
        return $this->getFactory()->createCodeStyleSniffer()->checkCodeStyle($module, $options);
    }

    /**
     * @api
     *
     * @param string|null $module
     * @param array $options
     *
     * @return void
     */
    public function runTest($module, array $options = [])
    {
        $this->getFactory()->createCodeTester()->runTest($module, $options);
    }

    /**
     * @api
     *
     * @param string|null $module
     * @param array $options
     *
     * @return int Exit code
     */
    public function runPhpMd($module, array $options = [])
    {
        return $this->getFactory()->createPhpMdRunner()->run($module, $options);
    }

    /**
     * @api
     *
     * @param string $module
     * @param string $toModule
     * @param array $methods
     *
     * @return void
     */
    public function createBridge($module, $toModule, array $methods)
    {
        $this->getFactory()->createBridgeBuilder()->build($module, $toModule, $methods);
    }

    /**
     * @api
     *
     * @param string $module
     * @param array $options
     *
     * @return void
     */
    public function createModule($module, array $options)
    {
        $this->getFactory()->createModuleBuilder()->build($module, $options);
    }

    /**
     * @api
     *
     * @param array $modules
     * @param bool $dryRun
     *
     * @return array
     */
    public function updateComposerJsonInModules(array $modules, $dryRun = false)
    {
        return $this->getFactory()->createComposerJsonUpdater()->update($modules, $dryRun);
    }

    /**
     * @api
     *
     * @param string $moduleName
     *
     * @return \Generated\Shared\Transfer\BundleDependencyCollectionTransfer
     */
    public function showOutgoingDependenciesForModule($moduleName)
    {
        return $this->getFactory()->createDependencyBundleParser()->parseOutgoingDependencies($moduleName);
    }

    /**
     * @api
     *
     * @param string $moduleName
     *
     * @return array
     */
    public function showIncomingDependenciesForModule($moduleName)
    {
        return $this->getFactory()->createDependencyManager()->parseIncomingDependencies($moduleName);
    }

    /**
     * @api
     *
     * @return array
     */
    public function getAllModules()
    {
        return $this->getFactory()->createDependencyManager()->collectAllModules();
    }

    /**
     * @api
     *
     * @deprecated Use `getAllModules()` instead.
     *
     * @return array
     */
    public function getAllBundles()
    {
        return $this->getAllModules();
    }

    /**
     * @api
     *
     * @param string $module
     *
     * @return void
     */
    public function buildDependencyTree(string $module)
    {
        $this->getFactory()->createDependencyTreeBuilder()->buildDependencyTree($module);
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
     * @param string|bool $moduleToView
     * @param array $excludedModules
     * @param bool $showIncomingDependencies
     *
     * @return string
     */
    public function drawOutgoingDependencyTreeGraph($moduleToView, array $excludedModules = [], $showIncomingDependencies = false)
    {
        return $this->getFactory()->createOutgoingDependencyGraphBuilder($moduleToView, $excludedModules)->build($showIncomingDependencies);
    }

    /**
     * @api
     *
     * @param string|bool $moduleToView
     *
     * @return string
     */
    public function drawDetailedDependencyTreeGraph($moduleToView)
    {
        return $this->getFactory()->createDetailedDependencyGraphBuilder($moduleToView)->build();
    }

    /**
     * @api
     *
     * @param bool $showEngineModule
     * @param string|bool $moduleToView
     *
     * @return string
     */
    public function drawSimpleDependencyTreeGraph($showEngineModule, $moduleToView)
    {
        return $this->getFactory()->createSimpleDependencyGraphBuilder($showEngineModule, $moduleToView)->build();
    }

    /**
     * @api
     *
     * @param string $moduleToView
     *
     * @return string
     */
    public function drawExternalDependencyTreeGraph($moduleToView)
    {
        return $this->getFactory()->createExternalDependencyGraphBuilder($moduleToView)->build();
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
    public function getEngineModuleList()
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
     * @param \Generated\Shared\Transfer\BundleDependencyCollectionTransfer $moduleDependencyCollectionTransfer
     *
     * @return array
     */
    public function getComposerDependencyComparison(BundleDependencyCollectionTransfer $moduleDependencyCollectionTransfer)
    {
        return $this->getFactory()->createComposerDependencyParser()->getComposerDependencyComparison($moduleDependencyCollectionTransfer);
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
    public function listAllModules()
    {
        return $this->getFactory()->createArchitectureBundleFinder()->find();
    }

    /**
     * @api
     *
     * @deprecated Use `listAllModules` instead.
     *
     * @return array
     */
    public function listAllBundles()
    {
        return $this->listAllModules();
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

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function runPhpstan(InputInterface $input, OutputInterface $output)
    {
        return $this->getFactory()->createPhpstanRunner()->run($input, $output);
    }
}

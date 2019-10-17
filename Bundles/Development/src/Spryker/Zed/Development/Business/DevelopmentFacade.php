<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business;

use Generated\Shared\Transfer\ComposerJsonValidationRequestTransfer;
use Generated\Shared\Transfer\ComposerJsonValidationResponseTransfer;
use Generated\Shared\Transfer\DependencyCollectionTransfer;
use Generated\Shared\Transfer\DependencyProviderCollectionTransfer;
use Generated\Shared\Transfer\DependencyValidationRequestTransfer;
use Generated\Shared\Transfer\DependencyValidationResponseTransfer;
use Generated\Shared\Transfer\ModuleFilterTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
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
     * @return void
     */
    public function runFixtures($module, array $options = [])
    {
        $this->getFactory()->createCodeTester()->runFixtures($module, $options);
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
     * @deprecated Use Spryk tool instead.
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
     * @internal
     *
     * @param \Generated\Shared\Transfer\ModuleTransfer[] $modules
     * @param bool $dryRun
     *
     * @return array
     */
    public function updateComposerJsonInModules(array $modules, $dryRun = false): array
    {
        return $this->getFactory()->createComposerJsonUpdater()->update($modules, $dryRun);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @internal
     *
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     * @param string|null $dependencyType
     *
     * @return \Generated\Shared\Transfer\DependencyCollectionTransfer
     */
    public function showOutgoingDependenciesForModule(ModuleTransfer $moduleTransfer, ?string $dependencyType = null): DependencyCollectionTransfer
    {
        return $this->getFactory()->createModuleDependencyParser()->parseOutgoingDependencies($moduleTransfer, $dependencyType);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @internal
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
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Please use `getModules()` instead.
     *
     * @return array
     */
    public function getAllModules()
    {
        return $this->getFactory()->createDependencyManager()->collectAllModules();
    }

    /**
     * {@inheritDoc}
     *
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
     * @param string $moduleToView
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
     * @deprecated This method is not used anymore.
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
     * @deprecated This method is not used anymore.
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
     * @param \Generated\Shared\Transfer\DependencyCollectionTransfer $dependencyCollectionTransfer
     *
     * @return array
     */
    public function getComposerDependencyComparison(DependencyCollectionTransfer $dependencyCollectionTransfer)
    {
        return $this->getFactory()->createComposerDependencyParser()->getComposerDependencyComparison($dependencyCollectionTransfer);
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
     * @api
     *
     * @return void
     */
    public function generateGlueIdeAutoCompletion()
    {
        $this->getFactory()->createGlueIdeAutoCompletionWriter()->writeCompletionFiles();
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function listAllModules()
    {
        return $this->getFactory()->createAllModuleFinder()->find();
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string|null $module
     *
     * @return bool
     */
    public function runPropelAbstractValidation(OutputInterface $output, ?string $module): bool
    {
        return $this->getFactory()->createPropelAbstractValidator()->validate($output, $module);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DependencyValidationRequestTransfer $dependencyValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DependencyValidationResponseTransfer
     */
    public function validateModuleDependencies(DependencyValidationRequestTransfer $dependencyValidationRequestTransfer): DependencyValidationResponseTransfer
    {
        return $this->getFactory()->createDependencyValidator()->validate($dependencyValidationRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ComposerJsonValidationRequestTransfer $composerJsonValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ComposerJsonValidationResponseTransfer
     */
    public function validateComposerJson(ComposerJsonValidationRequestTransfer $composerJsonValidationRequestTransfer): ComposerJsonValidationResponseTransfer
    {
        return $this->getFactory()->createComposerJsonValidator()->validate($composerJsonValidationRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer|null $moduleFilterTransfer
     *
     * @return \Generated\Shared\Transfer\DependencyProviderCollectionTransfer
     */
    public function getInProjectDependencyProviderUsedPlugins(?ModuleFilterTransfer $moduleFilterTransfer = null): DependencyProviderCollectionTransfer
    {
        return $this->getFactory()->createDependencyProviderUsedPluginFinder()->getUsedPlugins($moduleFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer|null $moduleFilterTransfer
     *
     * @return array
     */
    public function getProjectModules(?ModuleFilterTransfer $moduleFilterTransfer = null): array
    {
        return $this->getFactory()->getModuleFinderFacade()->getProjectModules($moduleFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer|null $moduleFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer[]
     */
    public function getModules(?ModuleFilterTransfer $moduleFilterTransfer = null): array
    {
        return $this->getFactory()->getModuleFinderFacade()->getModules($moduleFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @internal
     *
     * @return \Generated\Shared\Transfer\PackageTransfer[]
     */
    public function getPackages(): array
    {
        return $this->getFactory()->getModuleFinderFacade()->getPackages();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ModuleOverviewTransfer[]
     */
    public function getModuleOverview(): array
    {
        return $this->getFactory()->createModuleOverview()->getOverview();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $moduleName
     *
     * @return string|null
     */
    public function findComposerNameByModuleName(string $moduleName): ?string
    {
        return $this->getFactory()->createComposerNameFinder()->findComposerNameByModuleName($moduleName);
    }
}

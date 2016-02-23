<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Maintenance\Business;

use Generated\Shared\Transfer\InstalledPackagesTransfer;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Maintenance\Business\Composer\ComposerJsonFinder;
use Spryker\Zed\Maintenance\Business\Composer\ComposerJsonUpdater;
use Spryker\Zed\Maintenance\Business\Composer\Updater\BranchAliasUpdater;
use Spryker\Zed\Maintenance\Business\Composer\Updater\ComposerUpdaterComposite;
use Spryker\Zed\Maintenance\Business\Composer\Updater\DescriptionUpdater;
use Spryker\Zed\Maintenance\Business\Composer\Updater\LicenseUpdater;
use Spryker\Zed\Maintenance\Business\Composer\Updater\RequireExternalUpdater;
use Spryker\Zed\Maintenance\Business\Composer\Updater\RequireUpdater;
use Spryker\Zed\Maintenance\Business\Composer\Updater\StabilityUpdater;
use Spryker\Zed\Maintenance\Business\DependencyTree\AdjacencyMatrixBuilder;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\BundleToViewFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\ClassNameFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\ConstantsToForeignConstantsFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\DependencyFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\EngineBundleFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\ExternalDependencyFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\ForeignEngineBundleFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\InternalDependencyFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\InvalidForeignBundleFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\InvalidForeignClassNameFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\TreeFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\ExternalDependency;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\LocatorClient;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\LocatorFacade;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\LocatorQueryContainer;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\UseStatement;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraphBuilder;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraph\DetailedGraphBuilder;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraph\ExternalGraphBuilder;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraph\SimpleGraphBuilder;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyHydrator\DependencyHydrator;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyHydrator\PackageNameHydrator;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyHydrator\PackageVersionHydrator;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeBuilder;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeReader\JsonDependencyTreeReader;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeWriter\JsonDependencyTreeWriter;
use Spryker\Zed\Maintenance\Business\DependencyTree\FileInfoExtractor;
use Spryker\Zed\Maintenance\Business\DependencyTree\Finder;
use Spryker\Zed\Maintenance\Business\DependencyTree\ViolationChecker\DependencyViolationChecker;
use Spryker\Zed\Maintenance\Business\DependencyTree\ViolationFinder\BundleUsesConnector;
use Spryker\Zed\Maintenance\Business\DependencyTree\ViolationFinder\UseForeignConstants;
use Spryker\Zed\Maintenance\Business\DependencyTree\ViolationFinder\UseForeignException;
use Spryker\Zed\Maintenance\Business\DependencyTree\ViolationFinder\ViolationFinder;
use Spryker\Zed\Maintenance\Business\Dependency\BundleParser;
use Spryker\Zed\Maintenance\Business\Dependency\Graph as DependencyGraph;
use Spryker\Zed\Maintenance\Business\Dependency\Manager;
use Spryker\Zed\Maintenance\Business\InstalledPackages\Composer\InstalledPackageFinder as ComposerInstalledPackageFinder;
use Spryker\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollector;
use Spryker\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollectorFilter;
use Spryker\Zed\Maintenance\Business\InstalledPackages\MarkDownWriter;
use Spryker\Zed\Maintenance\Business\InstalledPackages\NodePackageManager\InstalledPackageFinder;
use Spryker\Zed\Maintenance\Business\Model\PropelMigrationCleaner;
use Spryker\Zed\Maintenance\MaintenanceDependencyProvider;
use Symfony\Component\Finder\Finder as SfFinder;
use Symfony\Component\Process\Process;

/**
 * @method \Spryker\Zed\Maintenance\MaintenanceConfig getConfig()
 */
class MaintenanceBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollectorInterface
     */
    public function createPackageCollector()
    {
        $collection = $this->createInstalledPackageTransfer();
        $finder = [];
        $finder[] = $this->createComposerInstalledPackageFinder($collection);
        $finder[] = $this->createNodePackageManagerInstalledPackageFinder(
            $collection,
            $this->getConfig()->getPathToRoot()
        );
        $finder[] = $this->createComposerInstalledPackageFinder($collection);

        $collector = $this->createInstalledPackageCollector($collection, $finder);
        $collector = $this->createFilteredInstalledPackageCollector($collector);

        return $collector;
    }

    /**
     * @param \Generated\Shared\Transfer\InstalledPackagesTransfer $collection
     *
     * @return \Spryker\Zed\Maintenance\Business\InstalledPackages\Composer\InstalledPackageFinder
     */
    protected function createComposerInstalledPackageFinder(InstalledPackagesTransfer $collection)
    {
        return new ComposerInstalledPackageFinder(
            $collection,
            $this->getConfig()->getPathToComposerLock()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\InstalledPackagesTransfer $collection
     * @param string $path
     *
     * @return \Spryker\Zed\Maintenance\Business\InstalledPackages\NodePackageManager\InstalledPackageFinder
     */
    protected function createNodePackageManagerInstalledPackageFinder(InstalledPackagesTransfer $collection, $path)
    {
        return new InstalledPackageFinder(
            $collection,
            $this->createNpmListProcess(),
            $path
        );
    }

    /**
     * @return \Symfony\Component\Process\Process
     */
    protected function createNpmListProcess()
    {
        return new Process('npm list -json -long');
    }

    /**
     * @param \Generated\Shared\Transfer\InstalledPackagesTransfer $installedPackages
     *
     * @return \Spryker\Zed\Maintenance\Business\InstalledPackages\MarkDownWriter
     */
    public function createMarkDownWriter(InstalledPackagesTransfer $installedPackages)
    {
        return new MarkDownWriter(
            $installedPackages,
            $this->getConfig()->getPathToFossFile()
        );
    }

    /**
     * @return \Spryker\Shared\Graph\Graph
     */
    public function createDependencyGraph()
    {
        $bundleParser = $this->createDependencyBundleParser();
        $manager = $this->createDependencyManager();

        return new DependencyGraph($bundleParser, $manager, $this->getGraph()->init('Dependency Tree'));
    }

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return \Spryker\Zed\Graph\Communication\Plugin\GraphPlugin
     */
    protected function getGraph()
    {
        return $this->getProvidedDependency(MaintenanceDependencyProvider::PLUGIN_GRAPH);
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\Dependency\BundleParser
     */
    public function createDependencyBundleParser()
    {
        $config = $this->getConfig();

        return new BundleParser($config);
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\Dependency\Manager
     */
    public function createDependencyManager()
    {
        $bundleParser = $this->createDependencyBundleParser();

        return new Manager($bundleParser, $this->getConfig()->getBundleDirectory());
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\Model\PropelMigrationCleanerInterface
     */
    public function createPropelMigrationCleaner()
    {
        return new PropelMigrationCleaner(
            $this->createPropelBaseFolderFinder()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\InstalledPackagesTransfer
     */
    protected function createInstalledPackageTransfer()
    {
        $collection = new InstalledPackagesTransfer();

        return $collection;
    }

    /**
     * @param \Generated\Shared\Transfer\InstalledPackagesTransfer $collection
     * @param array $finder
     *
     * @return \Spryker\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollector
     */
    protected function createInstalledPackageCollector($collection, array $finder)
    {
        $collector = new InstalledPackageCollector(
            $collection,
            $finder
        );

        return $collector;
    }

    /**
     * @param \Spryker\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollectorInterface $collector
     *
     * @return \Spryker\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollectorFilter
     */
    protected function createFilteredInstalledPackageCollector($collector)
    {
        $collector = new InstalledPackageCollectorFilter($collector);

        return $collector;
    }

    /**
     * @param string $application
     * @param string $bundle
     * @param string $layer
     *
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeBuilder
     */
    public function createDependencyTreeBuilder($application, $bundle, $layer)
    {
        $finder = $this->createDependencyTreeFinder($application, $bundle, $layer);
        $report = $this->createDependencyTree();
        $writer = $this->createDependencyTreeWriter();

        $dependencyTreeBuilder = new DependencyTreeBuilder($finder, $report, $writer);
        $dependencyTreeBuilder->addDependencyChecker($this->createDependencyTreeChecker());

        return $dependencyTreeBuilder;
    }

    /**
     * @param string $application
     * @param string $bundle
     * @param string $layer
     *
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\Finder
     */
    protected function createDependencyTreeFinder($application, $bundle, $layer)
    {
        $finder = new Finder(
            $this->getConfig()->getBundleDirectory(),
            $application,
            $bundle,
            $layer
        );

        return $finder;
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree
     */
    protected function createDependencyTree()
    {
        $fileInfoExtractor = $this->createDependencyTreeFileInfoExtractor();
        $engineBundleList = $this->getEngineBundleList();

        return new DependencyTree($fileInfoExtractor, $engineBundleList);
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\FileInfoExtractor
     */
    protected function createDependencyTreeFileInfoExtractor()
    {
        return new FileInfoExtractor();
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeWriter\JsonDependencyTreeWriter
     */
    protected function createDependencyTreeWriter()
    {
        return new JsonDependencyTreeWriter($this->getConfig()->getPathToJsonDependencyTree());
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeReader\JsonDependencyTreeReader
     */
    public function createDependencyTreeReader()
    {
        return new JsonDependencyTreeReader($this->getConfig()->getPathToJsonDependencyTree());
    }

    /**
     * @return array
     */
    protected function createDependencyTreeChecker()
    {
        return [
            $this->createUseStatementChecker(),
            $this->createLocatorFacadeChecker(),
            $this->createLocatorQueryContainerChecker(),
            $this->createLocatorClientChecker(),
            $this->createExternalDependencyChecker(),
        ];
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\UseStatement
     */
    protected function createUseStatementChecker()
    {
        return new UseStatement();
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\LocatorFacade
     */
    protected function createLocatorFacadeChecker()
    {
        return new LocatorFacade();
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\LocatorQueryContainer
     */
    protected function createLocatorQueryContainerChecker()
    {
        return new LocatorQueryContainer();
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\LocatorClient
     */
    protected function createLocatorClientChecker()
    {
        return new LocatorClient();
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\ExternalDependency
     */
    protected function createExternalDependencyChecker()
    {
        return new ExternalDependency();
    }

    /**
     * @param string|bool $bundleToView
     *
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraphBuilder
     */
    public function createDetailedDependencyGraphBuilder($bundleToView)
    {
        $dependencyTreeFilter = $this->createDetailedDependencyTreeFilter($bundleToView);
        $dependencyTreeReader = $this->createDependencyTreeReader();

        $dependencyGraphBuilder = new DependencyGraphBuilder(
            $this->createDetailedGraphBuilder(),
            $dependencyTreeFilter->filter($dependencyTreeReader->read())
        );

        return $dependencyGraphBuilder;
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraph\DetailedGraphBuilder
     */
    protected function createDetailedGraphBuilder()
    {
        return new DetailedGraphBuilder($this->getGraph()->init('Dependency Tree'));
    }

    /**
     * @param string|bool $bundleToView
     *
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\TreeFilter
     */
    protected function createDetailedDependencyTreeFilter($bundleToView)
    {
        $treeFilter = $this->createDependencyTreeFilter();
        $treeFilter
            ->addFilter($this->createDependencyTreeForeignEngineBundleFilter())
            ->addFilter($this->createDependencyTreeClassNameFilter('/\\Dependency\\\(.*?)Interface/'));

        if (is_string($bundleToView)) {
            $treeFilter->addFilter($this->createDependencyTreeBundleToViewFilter($bundleToView));
        }

        return $treeFilter;
    }

    /**
     * @param string|bool $bundleToView
     *
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraphBuilder
     */
    public function createSimpleDependencyGraphBuilder($bundleToView)
    {
        $dependencyTreeFilter = $this->createSimpleGraphDependencyTreeFilter($bundleToView);
        $dependencyTreeReader = $this->createDependencyTreeReader();

        $dependencyGraphBuilder = new DependencyGraphBuilder(
            $this->createSimpleGraphBuilder(),
            $dependencyTreeFilter->filter($dependencyTreeReader->read())
        );

        return $dependencyGraphBuilder;
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraph\DetailedGraphBuilder
     */
    protected function createSimpleGraphBuilder()
    {
        return new SimpleGraphBuilder($this->getGraph()->init('Dependency Tree'));
    }

    /**
     * @param string|bool $bundleToView
     *
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\TreeFilter
     */
    protected function createSimpleGraphDependencyTreeFilter($bundleToView)
    {
        $treeFilter = $this->createDependencyTreeFilter();
        $treeFilter
            ->addFilter($this->createDependencyTreeForeignEngineBundleFilter())
            ->addFilter($this->createDependencyTreeEngineBundleFilter())
            ->addFilter($this->createDependencyTreeInvalidForeignBundleFilter())
            ->addFilter($this->createDependencyTreeExternalDependencyFilter())
            ->addFilter($this->createDependencyTreeClassNameFilter('/\\Dependency\\\(.*?)Interface/'));

        if (is_string($bundleToView)) {
            $treeFilter->addFilter($this->createDependencyTreeBundleToViewFilter($bundleToView));
        }

        return $treeFilter;
    }

    /**
     * @param string|null $bundleToView
     *
     * @return array
     */
    public function createExternalDependencyTree($bundleToView = null)
    {
        $treeFilter = $this->createDependencyTreeFilter();
        $treeFilter
            ->addFilter($this->createDependencyTreeInternalDependencyFilter());

        if (is_string($bundleToView)) {
            $treeFilter->addFilter($this->createDependencyTreeBundleToViewFilter($bundleToView));
        }

        $composerLock = json_decode(file_get_contents(APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'composer.lock'), true);
        $packageVersionHydrator = new PackageVersionHydrator(array_merge($composerLock['packages'], $composerLock['packages-dev']));

        $treeHydrator = new DependencyHydrator();
        $treeHydrator->addHydrator(new PackageNameHydrator());
        $treeHydrator->addHydrator($packageVersionHydrator);

        $dependencyTreeReader = $this->createDependencyTreeReader();

        $dependencyTree = $treeFilter->filter($dependencyTreeReader->read());

        return $treeHydrator->hydrate($dependencyTree);
    }

    /**
     * @param string $bundleToView
     *
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraphBuilder
     */
    public function createExternalDependencyGraphBuilder($bundleToView)
    {
        $dependencyGraphBuilder = new DependencyGraphBuilder(
            $this->createExternalGraphBuilder(),
            $this->createExternalDependencyTree($bundleToView)
        );

        return $dependencyGraphBuilder;
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraph\DetailedGraphBuilder
     */
    protected function createExternalGraphBuilder()
    {
        return new ExternalGraphBuilder($this->getGraph()->init('Dependency Tree'));
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\AdjacencyMatrixBuilder
     */
    public function createAdjacencyMatrixBuilder()
    {
        $adjacencyMatrixBuilder = new AdjacencyMatrixBuilder(
            $this->createDependencyManager()->collectAllBundles(),
            $this->createDependencyTreeReader(),
            $this->createAdjacencyMatrixDependencyTreeFilter(),
            $this->getEngineBundleList()
        );

        return $adjacencyMatrixBuilder;
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\TreeFilter
     */
    protected function createAdjacencyMatrixDependencyTreeFilter()
    {
        $treeFilter = $this->createDependencyTreeFilter();
        $treeFilter
            ->addFilter($this->createDependencyTreeInvalidForeignBundleFilter());

        return $treeFilter;
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\ViolationChecker\DependencyViolationChecker
     */
    public function createDependencyViolationChecker()
    {
        return new DependencyViolationChecker(
            $this->createDependencyTreeReader(),
            $this->createViolationFinder(),
            $this->createDependencyViolationFilter()
        );
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\ViolationFinder\ViolationFinder
     */
    protected function createViolationFinder()
    {
        $violationFinder = new ViolationFinder();
        $violationFinder
            ->addViolationFinder($this->createViolationFinderUseForeignConstants())
            ->addViolationFinder($this->createViolationFinderUseForeignException())
            ->addViolationFinder($this->createViolationFinderBundleUsesConnector());

        return $violationFinder;
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\DependencyFilter
     */
    protected function createDependencyViolationFilter()
    {
        $dependencyFilter = new DependencyFilter();
        $dependencyFilter
            ->addFilter($this->createDependencyTreeConstantsToForeignConstantsFilter())
            ->addFilter($this->createDependencyTreeForeignEngineBundleFilter());

        return $dependencyFilter;
    }

    /**
     * @param string $pattern
     *
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\ClassNameFilter
     */
    protected function createDependencyTreeClassNameFilter($pattern)
    {
        return new ClassNameFilter($pattern);
    }

    /**
     * @param string $bundleToView
     *
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\BundleToViewFilter
     */
    protected function createDependencyTreeBundleToViewFilter($bundleToView)
    {
        return new BundleToViewFilter($bundleToView);
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\ViolationFinder\UseForeignConstants
     */
    protected function createViolationFinderUseForeignConstants()
    {
        return new UseForeignConstants();
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\ViolationFinder\UseForeignException
     */
    protected function createViolationFinderUseForeignException()
    {
        return new UseForeignException();
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\ViolationFinder\BundleUsesConnector
     */
    protected function createViolationFinderBundleUsesConnector()
    {
        return new BundleUsesConnector();
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\ConstantsToForeignConstantsFilter
     */
    protected function createDependencyTreeConstantsToForeignConstantsFilter()
    {
        return new ConstantsToForeignConstantsFilter();
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\ForeignEngineBundleFilter
     */
    protected function createDependencyTreeForeignEngineBundleFilter()
    {
        return new ForeignEngineBundleFilter(
            $this->getConfig()->getPathToBundleConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\EngineBundleFilter
     */
    protected function createDependencyTreeEngineBundleFilter()
    {
        return new EngineBundleFilter(
            $this->getConfig()->getPathToBundleConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\InvalidForeignBundleFilter
     */
    protected function createDependencyTreeInvalidForeignBundleFilter()
    {
        return new InvalidForeignBundleFilter(
            $this->createDependencyManager()->collectAllBundles()
        );
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\ExternalDependencyFilter
     */
    protected function createDependencyTreeExternalDependencyFilter()
    {
        return new ExternalDependencyFilter();
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\InternalDependencyFilter
     */
    protected function createDependencyTreeInternalDependencyFilter()
    {
        return new InternalDependencyFilter();
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\TreeFilter
     */
    protected function createDependencyTreeFilter()
    {
        return new TreeFilter();
    }

    /**
     * @deprecated 1.0.0 Will be removed in the next major version.
     *
     * @return array
     */
    public function createEngineBundleList()
    {
        return $this->getEngineBundleList();
    }

    /**
     * @return array
     */
    public function getEngineBundleList()
    {
        $bundleList = json_decode(file_get_contents($this->getConfig()->getPathToBundleConfig()), true);

        return array_keys($bundleList);
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\Composer\ComposerJsonUpdater
     */
    public function createComposerJsonUpdater()
    {
        return new ComposerJsonUpdater(
            $this->createComposerJsonFinder(),
            $this->createComposerJsonUpdaterComposite()
        );
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\Composer\ComposerJsonFinder
     */
    protected function createComposerJsonFinder()
    {
        $composerJsonFinder = new ComposerJsonFinder(
            $this->createFinder(),
            $this->getConfig()->getBundleDirectory()
        );

        return $composerJsonFinder;
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\Composer\Updater\ComposerUpdaterComposite
     */
    protected function createComposerJsonUpdaterComposite()
    {
        $updaterComposite = new ComposerUpdaterComposite();
        $updaterComposite
            ->addUpdater($this->createComposerJsonDescriptionUpdater())
            ->addUpdater($this->createComposerJsonLicenseUpdater())
            ->addUpdater($this->createComposerJsonRequireUpdater())
            ->addUpdater($this->createComposerJsonRequireExternalUpdater())
            ->addUpdater($this->createComposerJsonStabilityUpdater())
            ->addUpdater($this->createComposerJsonBranchAliasUpdater());

        return $updaterComposite;
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function createFinder()
    {
        return new SfFinder();
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\Composer\Updater\DescriptionUpdater
     */
    protected function createComposerJsonDescriptionUpdater()
    {
        return new DescriptionUpdater();
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\Composer\Updater\LicenseUpdater
     */
    protected function createComposerJsonLicenseUpdater()
    {
        return new LicenseUpdater('proprietary');
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\Composer\Updater\RequireUpdater
     */
    protected function createComposerJsonRequireUpdater()
    {
        return new RequireUpdater(
            $this->createDependencyTreeReader(),
            $this->createComposerJsonRequireUpdaterTreeFilter()
        );
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\Composer\Updater\RequireUpdater
     */
    protected function createComposerJsonRequireExternalUpdater()
    {
        return new RequireExternalUpdater(
            $this->createExternalDependencyTree(),
            $this->getConfig()->getExternalToInternalMap(),
            $this->getConfig()->getIgnorableDependencies()
        );
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\Composer\Updater\StabilityUpdater
     */
    protected function createComposerJsonStabilityUpdater()
    {
        return new StabilityUpdater('dev');
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\Composer\Updater\BranchAliasUpdater
     */
    protected function createComposerJsonBranchAliasUpdater()
    {
        return new BranchAliasUpdater('1.0.x-dev');
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\TreeFilter
     */
    protected function createComposerJsonRequireUpdaterTreeFilter()
    {
        $treeFilter = new TreeFilter();
        $treeFilter
            ->addFilter($this->createDependencyTreeClassNameFilter('/\\Dependency\\\(.*?)Interface/'))
            ->addFilter($this->createDependencyTreeInvalidForeignBundleFilter());

        return $treeFilter;
    }

}

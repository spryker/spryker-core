<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business;

use Spryker\Zed\Maintenance\Business\Composer\ComposerJsonUpdater;
use Spryker\Zed\Maintenance\Business\DependencyTree\AdjacencyMatrixBuilder;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\BundleToViewFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\ClassNameFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\ConstantsToForeignConstantsFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\DependencyFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\EngineBundleFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\ForeignEngineBundleFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\InvalidForeignBundleFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\TreeFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\LocatorClient;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\LocatorFacade;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\LocatorQueryContainer;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\UseStatement;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraph\DetailedGraphBuilder;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraph\SimpleGraphBuilder;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraphBuilder;
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
use Spryker\Zed\Maintenance\Business\InstalledPackages\Composer\JsonUpdater;
use Spryker\Zed\Maintenance\Business\Model\PropelMigrationCleaner;
use Spryker\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollectorFilter;
use Spryker\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollector;
use Generated\Shared\Transfer\InstalledPackagesTransfer;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Maintenance\Business\Dependency\BundleParser;
use Spryker\Zed\Maintenance\Business\Dependency\Graph;
use Spryker\Zed\Maintenance\Business\Dependency\Manager;
use Spryker\Zed\Maintenance\Business\InstalledPackages\Composer\InstalledPackageFinder as ComposerInstalledPackageFinder;
use Spryker\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollectorInterface;
use Spryker\Zed\Maintenance\Business\InstalledPackages\MarkDownWriter;
use Spryker\Zed\Maintenance\Business\InstalledPackages\NodePackageManager\InstalledPackageFinder;
use Spryker\Zed\Maintenance\Business\Model\PropelBaseFolderFinder;
use Spryker\Zed\Maintenance\Business\Model\PropelMigrationCleanerInterface;
use Spryker\Zed\Maintenance\MaintenanceConfig;
use Symfony\Component\Process\Process;

/**
 * @method MaintenanceConfig getConfig()
 */
class MaintenanceBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return InstalledPackageCollectorInterface
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
        $finder[] = $this->createNodePackageManagerInstalledPackageFinder(
            $collection,
            $this->getConfig()->getPathToSpryker()
        );

        $collector = $this->createInstalledPackageCollector($collection, $finder);
        $collector = $this->createFilteredInstalledPackageCollector($collector);

        return $collector;
    }

    /**
     * @param InstalledPackagesTransfer $collection
     *
     * @return ComposerInstalledPackageFinder
     */
    protected function createComposerInstalledPackageFinder(InstalledPackagesTransfer $collection)
    {
        return new ComposerInstalledPackageFinder(
            $collection,
            $this->getConfig()->getPathToComposerLock()
        );
    }

    /**
     * @param InstalledPackagesTransfer $collection
     * @param string $path
     *
     * @return InstalledPackageFinder
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
     * @return Process
     */
    protected function createNpmListProcess()
    {
        return new Process('npm list -json -long');
    }

    /**
     * @param InstalledPackagesTransfer $installedPackages
     *
     * @return MarkDownWriter
     */
    public function createMarkDownWriter(InstalledPackagesTransfer $installedPackages)
    {
        return new MarkDownWriter(
            $installedPackages,
            $this->getConfig()->getPathToFossFile()
        );
    }

    /**
     * @return Graph
     */
    public function createDependencyGraph()
    {
        $bundleParser = $this->createDependencyBundleParser();
        $manager = $this->createDependencyManager();

        return new Graph($bundleParser, $manager);
    }

    /**
     * @return BundleParser
     */
    public function createDependencyBundleParser()
    {
        $config = $this->getConfig();

        return new BundleParser($config);
    }

    /**
     * @return Manager
     */
    public function createDependencyManager()
    {
        $bundleParser = $this->createDependencyBundleParser();

        return new Manager($bundleParser);
    }

    /**
     * @return PropelMigrationCleanerInterface
     */
    public function createPropelMigrationCleaner()
    {
        return new PropelMigrationCleaner(
            $this->createPropelBaseFolderFinder()
        );
    }

    /**
     * @return PropelBaseFolderFinder
     */
    public function createPropelBaseFolderFinder()
    {
        return new PropelBaseFolderFinder($this->getConfig()->getPathToSpryker());
    }

    /**
     * @return InstalledPackagesTransfer
     */
    protected function createInstalledPackageTransfer()
    {
        $collection = new InstalledPackagesTransfer();

        return $collection;
    }

    /**
     * @param $collection
     * @param $finder
     *
     * @return InstalledPackageCollector
     */
    protected function createInstalledPackageCollector($collection, $finder)
    {
        $collector = new InstalledPackageCollector(
            $collection,
            $finder
        );

        return $collector;
    }

    /**
     * @param $collector
     *
     * @return InstalledPackageCollectorFilter
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
     * @return DependencyTreeBuilder
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
     * @return Finder
     */
    protected function createDependencyTreeFinder($application, $bundle, $layer)
    {
        $finder = new Finder($application, $bundle, $layer);

        return $finder;
    }

    /**
     * @return DependencyTree
     */
    protected function createDependencyTree()
    {
        $fileInfoExtractor = $this->createDependencyTreeFileInfoExtractor();
        $engineBundleList = $this->createEngineBundleList();

        return new DependencyTree($fileInfoExtractor, $engineBundleList);
    }

    /**
     * @return FileInfoExtractor
     */
    protected function createDependencyTreeFileInfoExtractor()
    {
        return new FileInfoExtractor();
    }

    /**
     * @return JsonDependencyTreeWriter
     */
    protected function createDependencyTreeWriter()
    {
        return new JsonDependencyTreeWriter($this->getConfig()->getPathToJsonDependencyTree());
    }

    /**
     * @return JsonDependencyTreeReader
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
        ];
    }

    /**
     * @return UseStatement
     */
    protected function createUseStatementChecker()
    {
        return new UseStatement();
    }

    /**
     * @return LocatorFacade
     */
    protected function createLocatorFacadeChecker()
    {
        return new LocatorFacade();
    }

    /**
     * @return LocatorQueryContainer
     */
    protected function createLocatorQueryContainerChecker()
    {
        return new LocatorQueryContainer();
    }

    /**
     * @return LocatorClient
     */
    protected function createLocatorClientChecker()
    {
        return new LocatorClient();
    }

    /**
     * @param string|bool $bundleToView
     *
     * @return DependencyGraphBuilder
     */
    public function createDetailedDependencyGraphBuilder($bundleToView)
    {
        $dependencyGraphBuilder = new DependencyGraphBuilder(
            $this->createDetailedGraphBuilder(),
            $this->createDependencyTreeReader(),
            $this->createDetailedDependencyTreeFilter($bundleToView)
        );

        return $dependencyGraphBuilder;
    }

    /**
     * @return DetailedGraphBuilder
     */
    protected function createDetailedGraphBuilder()
    {
        return new DetailedGraphBuilder();
    }

    /**
     * @param string|bool $bundleToView
     *
     * @return TreeFilter
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
     * @return DependencyGraphBuilder
     */
    public function createSimpleDependencyGraphBuilder($bundleToView)
    {
        $dependencyGraphBuilder = new DependencyGraphBuilder(
            $this->createSimpleGraphBuilder(),
            $this->createDependencyTreeReader(),
            $this->createSimpleGraphDependencyTreeFilter($bundleToView)
        );

        return $dependencyGraphBuilder;
    }

    /**
     * @return DetailedGraphBuilder
     */
    protected function createSimpleGraphBuilder()
    {
        return new SimpleGraphBuilder();
    }

    /**
     * @param string|bool $bundleToView
     *
     * @return TreeFilter
     */
    protected function createSimpleGraphDependencyTreeFilter($bundleToView)
    {
        $treeFilter = $this->createDependencyTreeFilter();
        $treeFilter
            ->addFilter($this->createDependencyTreeForeignEngineBundleFilter())
            ->addFilter($this->createDependencyTreeEngineBundleFilter())
            ->addFilter($this->createDependencyTreeInvalidForeignBundleFilter())
            ->addFilter($this->createDependencyTreeClassNameFilter('/\\Dependency\\\(.*?)Interface/'))
        ;

        if (is_string($bundleToView)) {
            $treeFilter->addFilter($this->createDependencyTreeBundleToViewFilter($bundleToView));
        }

        return $treeFilter;
    }

    /**
     * @return AdjacencyMatrixBuilder
     */
    public function createAdjacencyMatrixBuilder()
    {
        $adjacencyMatrixBuilder = new AdjacencyMatrixBuilder(
            $this->createDependencyManager()->collectAllBundles(),
            $this->createDependencyTreeReader(),
            $this->createAdjacencyMatrixDependencyTreeFilter(),
            $this->createEngineBundleList()
        );

        return $adjacencyMatrixBuilder;
    }

    /**
     * @return TreeFilter
     */
    protected function createAdjacencyMatrixDependencyTreeFilter()
    {
        $treeFilter = $this->createDependencyTreeFilter();
        $treeFilter
            ->addFilter($this->createDependencyTreeInvalidForeignBundleFilter())
        ;

        return $treeFilter;
    }

    /**
     * @return DependencyViolationChecker
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
     * @return ViolationFinder
     */
    protected function createViolationFinder()
    {
        $violationFinder = new ViolationFinder();
        $violationFinder
            ->addViolationFinder($this->createViolationFinderUseForeignConstants())
            ->addViolationFinder($this->createViolationFinderUseForeignException())
            ->addViolationFinder($this->createViolationFinderBundleUsesConnector())
        ;

        return $violationFinder;
    }

    /**
     * @return DependencyFilter
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
     * @return ClassNameFilter
     */
    protected function createDependencyTreeClassNameFilter($pattern)
    {
        return new ClassNameFilter($pattern);
    }

    /**
     * @param string $bundleToView
     *
     * @return BundleToViewFilter
     */
    protected function createDependencyTreeBundleToViewFilter($bundleToView)
    {
        return new BundleToViewFilter($bundleToView);
    }

    /**
     * @return UseForeignConstants
     */
    protected function createViolationFinderUseForeignConstants()
    {
        return new UseForeignConstants();
    }

    /**
     * @return UseForeignException
     */
    protected function createViolationFinderUseForeignException()
    {
        return new UseForeignException();
    }

    /**
     * @return BundleUsesConnector
     */
    protected function createViolationFinderBundleUsesConnector()
    {
        return new BundleUsesConnector();
    }

    /**
     * @return ConstantsToForeignConstantsFilter
     */
    protected function createDependencyTreeConstantsToForeignConstantsFilter()
    {
        return new ConstantsToForeignConstantsFilter();
    }

    /**
     * @return ForeignEngineBundleFilter
     */
    protected function createDependencyTreeForeignEngineBundleFilter()
    {
        return new ForeignEngineBundleFilter();
    }

    /**
     * @return EngineBundleFilter
     */
    protected function createDependencyTreeEngineBundleFilter()
    {
        return new EngineBundleFilter();
    }

    /**
     * @return InvalidForeignBundleFilter
     */
    protected function createDependencyTreeInvalidForeignBundleFilter()
    {
        return new InvalidForeignBundleFilter(
            $this->createDependencyManager()->collectAllBundles()
        );
    }

    /**
     * @return TreeFilter
     */
    protected function createDependencyTreeFilter()
    {
        return new TreeFilter();
    }

    /**
     * @return array
     */
    public function createEngineBundleList()
    {
        $bundleList = json_decode(file_get_contents($this->getConfig()->getPathToBundleConfig()), true);

        return array_keys($bundleList);
    }

    /**
     * @return ComposerJsonUpdater
     */
    public function createComposerJsonUpdater()
    {
        return new ComposerJsonUpdater(
            $this->createDependencyTreeReader(),
            new \Symfony\Component\Finder\Finder(),
            $this->getConfig()->getBundleDirectory()
        );
    }

}

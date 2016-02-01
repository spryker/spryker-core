<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business;

use Spryker\Tool\GraphPhpDocumentor\Adapter\PhpDocumentorGraphAdapter;
use Spryker\Tool\Graph\Graph;
use Spryker\Zed\Maintenance\Business\Composer\ComposerJsonFinder;
use Spryker\Zed\Maintenance\Business\Composer\ComposerJsonUpdater;
use Spryker\Zed\Maintenance\Business\Composer\Updater\BranchAliasUpdater;
use Spryker\Zed\Maintenance\Business\Composer\Updater\ComposerUpdaterComposite;
use Spryker\Zed\Maintenance\Business\Composer\Updater\DescriptionUpdater;
use Spryker\Zed\Maintenance\Business\Composer\Updater\LicenseUpdater;
use Spryker\Zed\Maintenance\Business\Composer\Updater\MinimumStabilityUpdater;
use Spryker\Zed\Maintenance\Business\Composer\Updater\RequireUpdater;
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
use Spryker\Zed\Maintenance\Business\Model\PropelMigrationCleaner;
use Spryker\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollectorFilter;
use Spryker\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollector;
use Generated\Shared\Transfer\InstalledPackagesTransfer;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Maintenance\Business\Dependency\BundleParser;
use Spryker\Zed\Maintenance\Business\Dependency\Graph as DependencyGraph;
use Spryker\Zed\Maintenance\Business\Dependency\Manager;
use Spryker\Zed\Maintenance\Business\InstalledPackages\Composer\InstalledPackageFinder as ComposerInstalledPackageFinder;
use Spryker\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollectorInterface;
use Spryker\Zed\Maintenance\Business\InstalledPackages\MarkDownWriter;
use Spryker\Zed\Maintenance\Business\InstalledPackages\NodePackageManager\InstalledPackageFinder;
use Spryker\Zed\Maintenance\Business\Model\PropelBaseFolderFinder;
use Spryker\Zed\Maintenance\Business\Model\PropelMigrationCleanerInterface;
use Spryker\Zed\Maintenance\MaintenanceConfig;
use Symfony\Component\Process\Process;
use Symfony\Component\Finder\Finder as SfFinder;

/**
 * @method MaintenanceConfig getConfig()
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
     * @param InstalledPackagesTransfer $collection
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
     * @param InstalledPackagesTransfer $collection
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
     * @param InstalledPackagesTransfer $installedPackages
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
     * @return \Spryker\Zed\Maintenance\Business\Dependency\Graph
     */
    public function createDependencyGraph()
    {
        $bundleParser = $this->createDependencyBundleParser();
        $manager = $this->createDependencyManager();

        return new DependencyGraph($bundleParser, $manager, $this->createGraphViz('Bundle Graph'));
    }

    /**
     * @param string $name
     * @param array $attributes
     * @param bool $directed
     * @param bool $strict
     *
     * @return Graph
     */
    protected function createGraphViz($name, array $attributes = [], $directed = true, $strict = true)
    {
        $adapter = $this->createGraphVizAdapter();
        $graph = new Graph($adapter, $name, $attributes, $directed, $strict);

        return $graph;
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
     * @deprecated will be removed. Core does not contain any base or map directories of Propel anymore
     *
     * @return \Spryker\Zed\Maintenance\Business\Model\PropelBaseFolderFinder
     */
    public function createPropelBaseFolderFinder()
    {
        trigger_error('Deprecated, will be removed.', E_USER_DEPRECATED);

        return new PropelBaseFolderFinder($this->getConfig()->getPathToSpryker());
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
     * @param $collection
     * @param $finder
     *
     * @return \Spryker\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollector
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
        $engineBundleList = $this->createEngineBundleList();

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
     * @param string|bool $bundleToView
     *
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraphBuilder
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
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraph\DetailedGraphBuilder
     */
    protected function createDetailedGraphBuilder()
    {
        return new DetailedGraphBuilder($this->createGraphViz('Detailed Dependencies'));
    }

    /**
     * @return PhpDocumentorGraphAdapter
     */
    protected function createGraphVizAdapter()
    {
        return new PhpDocumentorGraphAdapter();
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
        $dependencyGraphBuilder = new DependencyGraphBuilder(
            $this->createSimpleGraphBuilder(),
            $this->createDependencyTreeReader(),
            $this->createSimpleGraphDependencyTreeFilter($bundleToView)
        );

        return $dependencyGraphBuilder;
    }

    /**
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraph\DetailedGraphBuilder
     */
    protected function createSimpleGraphBuilder()
    {
        return new SimpleGraphBuilder($this->createGraphViz('Bundle Dependencies'));
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
            ->addFilter($this->createDependencyTreeClassNameFilter('/\\Dependency\\\(.*?)Interface/'));

        if (is_string($bundleToView)) {
            $treeFilter->addFilter($this->createDependencyTreeBundleToViewFilter($bundleToView));
        }

        return $treeFilter;
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
            $this->createEngineBundleList()
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
     * @return \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\TreeFilter
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
            ->addUpdater($this->createComposerJsonMinimumStabilityUpdater())
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
     * @return \Spryker\Zed\Maintenance\Business\Composer\Updater\MinimumStabilityUpdater
     */
    protected function createComposerJsonMinimumStabilityUpdater()
    {
        return new MinimumStabilityUpdater('dev');
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
            ->addFilter($this->createDependencyTreeClassNameFilter('/\\Dependency\\\(.*?)Interface/'));

        return $treeFilter;
    }

}

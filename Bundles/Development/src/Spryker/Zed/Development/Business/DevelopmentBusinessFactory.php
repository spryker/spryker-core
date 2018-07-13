<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business;

use Spryker\Zed\Development\Business\ArchitectureSniffer\AllBundleFinder;
use Spryker\Zed\Development\Business\ArchitectureSniffer\ArchitectureSniffer;
use Spryker\Zed\Development\Business\CodeBuilder\Bridge\BridgeBuilder;
use Spryker\Zed\Development\Business\CodeBuilder\Module\ModuleBuilder;
use Spryker\Zed\Development\Business\CodeStyleSniffer\CodeStyleSniffer;
use Spryker\Zed\Development\Business\CodeTest\CodeTester;
use Spryker\Zed\Development\Business\Composer\ComposerJsonFinder;
use Spryker\Zed\Development\Business\Composer\ComposerJsonFinderComposite;
use Spryker\Zed\Development\Business\Composer\ComposerJsonUpdater;
use Spryker\Zed\Development\Business\Composer\Updater\AutoloadUpdater;
use Spryker\Zed\Development\Business\Composer\Updater\BranchAliasUpdater;
use Spryker\Zed\Development\Business\Composer\Updater\ComposerUpdaterComposite;
use Spryker\Zed\Development\Business\Composer\Updater\DescriptionUpdater;
use Spryker\Zed\Development\Business\Composer\Updater\LicenseUpdater;
use Spryker\Zed\Development\Business\Composer\Updater\RequireExternalUpdater;
use Spryker\Zed\Development\Business\Composer\Updater\RequireUpdater;
use Spryker\Zed\Development\Business\Composer\Updater\StabilityUpdater;
use Spryker\Zed\Development\Business\Dependency\BundleParser;
use Spryker\Zed\Development\Business\Dependency\Manager;
use Spryker\Zed\Development\Business\DependencyTree\AdjacencyMatrixBuilder;
use Spryker\Zed\Development\Business\DependencyTree\ComposerDependencyParser;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\BundleToViewFilter;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\ClassNameFilter;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\ConstantsToForeignConstantsFilter;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\DependencyFilter;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\EngineBundleFilter;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\ExternalDependencyFilter;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\ForeignEngineBundleFilter;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\InternalDependencyFilter;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\InvalidForeignBundleFilter;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\TreeFilter;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\ExternalDependency;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\LocatorClient;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\LocatorFacade;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\LocatorQueryContainer;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\LocatorService;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\UseStatement;
use Spryker\Zed\Development\Business\DependencyTree\DependencyGraph\DetailedGraphBuilder;
use Spryker\Zed\Development\Business\DependencyTree\DependencyGraph\ExternalGraphBuilder;
use Spryker\Zed\Development\Business\DependencyTree\DependencyGraph\OutgoingGraphBuilder;
use Spryker\Zed\Development\Business\DependencyTree\DependencyGraph\SimpleGraphBuilder;
use Spryker\Zed\Development\Business\DependencyTree\DependencyGraphBuilder;
use Spryker\Zed\Development\Business\DependencyTree\DependencyHydrator\DependencyHydrator;
use Spryker\Zed\Development\Business\DependencyTree\DependencyHydrator\PackageNameHydrator;
use Spryker\Zed\Development\Business\DependencyTree\DependencyHydrator\PackageVersionHydrator;
use Spryker\Zed\Development\Business\DependencyTree\DependencyTree;
use Spryker\Zed\Development\Business\DependencyTree\DependencyTreeBuilder;
use Spryker\Zed\Development\Business\DependencyTree\DependencyTreeReader\JsonDependencyTreeReader;
use Spryker\Zed\Development\Business\DependencyTree\DependencyTreeWriter\JsonDependencyTreeWriter;
use Spryker\Zed\Development\Business\DependencyTree\FileInfoExtractor;
use Spryker\Zed\Development\Business\DependencyTree\Finder\FileFinder;
use Spryker\Zed\Development\Business\DependencyTree\Finder\FinderComposite;
use Spryker\Zed\Development\Business\DependencyTree\Finder\PathBuilder\SprykerEcoPathBuilder;
use Spryker\Zed\Development\Business\DependencyTree\Finder\PathBuilder\SprykerPathBuilder;
use Spryker\Zed\Development\Business\DependencyTree\Finder\PathBuilder\SprykerSdkPathBuilder;
use Spryker\Zed\Development\Business\DependencyTree\Finder\PathBuilder\SprykerShopPathBuilder;
use Spryker\Zed\Development\Business\DependencyTree\ViolationChecker\DependencyViolationChecker;
use Spryker\Zed\Development\Business\DependencyTree\ViolationFinder\BundleUsesConnector;
use Spryker\Zed\Development\Business\DependencyTree\ViolationFinder\UseForeignConstants;
use Spryker\Zed\Development\Business\DependencyTree\ViolationFinder\UseForeignException;
use Spryker\Zed\Development\Business\DependencyTree\ViolationFinder\ViolationFinder;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\BundleBuilder as IdeAutoCompletionBundleBuilder;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\BundleBuilderInterface;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\BundleFinder;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder\ClientMethodBuilder;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder\FacadeMethodBuilder;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder\QueryContainerMethodBuilder;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder\ResourceMethodBuilder;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder\ServiceMethodBuilder;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\NamespaceExtractor;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Generator\BundleGenerator;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Generator\BundleMethodGenerator;
use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionWriter;
use Spryker\Zed\Development\Business\PhpMd\PhpMdRunner;
use Spryker\Zed\Development\Business\Phpstan\PhpstanRunner;
use Spryker\Zed\Development\Business\Stability\StabilityCalculator;
use Spryker\Zed\Development\DevelopmentDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Symfony\Component\Finder\Finder as SymfonyFinder;
use Zend\Config\Reader\Xml;
use Zend\Filter\Word\CamelCaseToDash;

/**
 * @method \Spryker\Zed\Development\DevelopmentConfig getConfig()
 */
class DevelopmentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Development\Business\CodeStyleSniffer\CodeStyleSniffer
     */
    public function createCodeStyleSniffer()
    {
        return new CodeStyleSniffer(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\CodeTest\CodeTester
     */
    public function createCodeTester()
    {
        return new CodeTester(
            $this->getConfig()->getPathToRoot(),
            $this->getConfig()->getPathToCore()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\PhpMd\PhpMdRunner
     */
    public function createPhpMdRunner()
    {
        return new PhpMdRunner(
            $this->getConfig()->getPathToRoot(),
            $this->getConfig()->getPathToCore(),
            $this->getConfig()->getArchitectureStandard()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Phpstan\PhpstanRunnerInterface
     */
    public function createPhpstanRunner()
    {
        return new PhpstanRunner(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\CodeBuilder\Bridge\BridgeBuilder
     */
    public function createBridgeBuilder()
    {
        return new BridgeBuilder($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Development\Business\CodeBuilder\Module\ModuleBuilder
     */
    public function createModuleBuilder()
    {
        return new ModuleBuilder(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Graph\Communication\Plugin\GraphPlugin
     */
    protected function getGraph()
    {
        return $this->getProvidedDependency(DevelopmentDependencyProvider::PLUGIN_GRAPH);
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\BundleParserInterface
     */
    public function createDependencyBundleParser()
    {
        return new BundleParser(
            $this->createDependencyTreeFinder(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\ManagerInterface
     */
    public function createDependencyManager()
    {
        $bundleParser = $this->createDependencyBundleParser();

        return new Manager($bundleParser, [
            $this->getConfig()->getPathToCore(),
            $this->getConfig()->getPathToShop(),
            $this->getConfig()->getPathToSdk(),
            $this->getConfig()->getPathToEco(),
        ]);
    }

    /**
     * @return \Spryker\Zed\Development\Business\Stability\StabilityCalculatorInterface
     */
    public function createStabilityCalculator()
    {
        return new StabilityCalculator();
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyTreeBuilderInterface
     */
    public function createDependencyTreeBuilder()
    {
        $finder = $this->createDependencyTreeFinder();
        $report = $this->createDependencyTree();
        $writer = $this->createDependencyTreeWriter();

        $dependencyTreeBuilder = new DependencyTreeBuilder($finder, $report, $writer);
        $dependencyTreeBuilder->addDependencyChecker($this->createDependencyTreeChecker());

        return $dependencyTreeBuilder;
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\Finder\FinderInterface
     */
    protected function createDependencyTreeFinder()
    {
        $finderComposite = new FinderComposite();
        $finderComposite
            ->addFinder($this->createSprykerFinder())
            ->addFinder($this->createSdkFinder())
            ->addFinder($this->createShopFinder());

        return $finderComposite;
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\Finder\FinderInterface
     */
    protected function createSprykerFinder()
    {
        $finder = new FileFinder(
            $this->createSprykerPathBuilder()
        );

        return $finder;
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\Finder\PathBuilder\PathBuilderInterface
     */
    protected function createSprykerPathBuilder()
    {
        return new SprykerPathBuilder(
            $this->getConfig()->getPathToCore(),
            $this->getConfig()->getApplications()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\Finder\FinderInterface
     */
    protected function createSdkFinder()
    {
        $finder = new FileFinder(
            $this->createSprykerSdkPathBuilder()
        );

        return $finder;
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\Finder\PathBuilder\PathBuilderInterface
     */
    protected function createSprykerSdkPathBuilder()
    {
        return new SprykerSdkPathBuilder(
            $this->getConfig()->getPathToSdk(),
            $this->getConfig()->getApplications()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\Finder\FinderInterface
     */
    protected function createEcoFinder()
    {
        $finder = new FileFinder(
            $this->createSprykerEcoPathBuilder()
        );

        return $finder;
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\Finder\PathBuilder\PathBuilderInterface
     */
    protected function createSprykerEcoPathBuilder()
    {
        return new SprykerEcoPathBuilder(
            $this->getConfig()->getPathToEco(),
            $this->getConfig()->getApplications()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\Finder\FinderInterface
     */
    protected function createShopFinder()
    {
        $finder = new FileFinder(
            $this->createSprykerShopPathBuilder()
        );

        return $finder;
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\Finder\PathBuilder\PathBuilderInterface
     */
    protected function createSprykerShopPathBuilder()
    {
        return new SprykerShopPathBuilder(
            $this->getConfig()->getPathToShop(),
            $this->getConfig()->getApplications()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyTree
     */
    protected function createDependencyTree()
    {
        $fileInfoExtractor = $this->createDependencyTreeFileInfoExtractor();
        $engineBundleList = $this->getEngineBundleList();

        return new DependencyTree($fileInfoExtractor, $engineBundleList);
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\FileInfoExtractor
     */
    protected function createDependencyTreeFileInfoExtractor()
    {
        return new FileInfoExtractor();
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyTreeWriter\JsonDependencyTreeWriter
     */
    protected function createDependencyTreeWriter()
    {
        return new JsonDependencyTreeWriter($this->getConfig()->getPathToJsonDependencyTree());
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyTreeReader\JsonDependencyTreeReader
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
            $this->createLocatorServiceChecker(),
            $this->createExternalDependencyChecker(),
        ];
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\UseStatement
     */
    protected function createUseStatementChecker()
    {
        return new UseStatement();
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\LocatorFacade
     */
    protected function createLocatorFacadeChecker()
    {
        return new LocatorFacade();
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\LocatorQueryContainer
     */
    protected function createLocatorQueryContainerChecker()
    {
        return new LocatorQueryContainer();
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\LocatorClient
     */
    protected function createLocatorClientChecker()
    {
        return new LocatorClient();
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\LocatorService
     */
    protected function createLocatorServiceChecker()
    {
        return new LocatorService();
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\ExternalDependency
     */
    protected function createExternalDependencyChecker()
    {
        return new ExternalDependency($this->getConfig()->getExternalToInternalNamespaceMap());
    }

    /**
     * @param string|bool $bundleToView
     *
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyGraphBuilder
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
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyGraph\DetailedGraphBuilder
     */
    protected function createDetailedGraphBuilder()
    {
        return new DetailedGraphBuilder($this->getGraph()->init('Dependency Tree'));
    }

    /**
     * @param string|bool $bundleToView
     *
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\TreeFilterCompositeInterface
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
     * @param string $bundleToView
     * @param array $excludedBundles
     *
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyGraph\OutgoingGraphBuilder
     */
    public function createOutgoingDependencyGraphBuilder($bundleToView, array $excludedBundles = [])
    {
        $outgoingDependencyGraphBuilder = new OutgoingGraphBuilder(
            $bundleToView,
            $this->getGraph(),
            $this->createDependencyBundleParser(),
            $this->createDependencyManager(),
            $excludedBundles
        );

        return $outgoingDependencyGraphBuilder;
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyGraph\GraphBuilderInterface
     */
    protected function createOutgoingGraphBuilder()
    {
        return new DetailedGraphBuilder($this->getGraph()->init('Dependency Tree'));
    }

    /**
     * @param bool $showEngineBundle
     * @param string|bool $bundleToView
     *
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyGraphBuilderInterface
     */
    public function createSimpleDependencyGraphBuilder($showEngineBundle, $bundleToView)
    {
        $dependencyTreeFilter = $this->createSimpleGraphDependencyTreeFilter($showEngineBundle, $bundleToView);
        $dependencyTreeReader = $this->createDependencyTreeReader();

        $dependencyGraphBuilder = new DependencyGraphBuilder(
            $this->createSimpleGraphBuilder(),
            $dependencyTreeFilter->filter($dependencyTreeReader->read())
        );

        return $dependencyGraphBuilder;
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyGraph\GraphBuilderInterface
     */
    protected function createSimpleGraphBuilder()
    {
        return new SimpleGraphBuilder($this->getGraph()->init('Dependency Tree'));
    }

    /**
     * @param bool $showEngineBundle
     * @param string|bool $bundleToView
     *
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\TreeFilterCompositeInterface
     */
    protected function createSimpleGraphDependencyTreeFilter($showEngineBundle, $bundleToView)
    {
        $treeFilter = $this->createDependencyTreeFilter();
        $treeFilter
            ->addFilter($this->createDependencyTreeInvalidForeignBundleFilter())
            ->addFilter($this->createDependencyTreeExternalDependencyFilter())
            ->addFilter($this->createDependencyTreeClassNameFilter('/\\Dependency\\\(.*?)Interface/'));

        if (!$showEngineBundle) {
            $treeFilter->addFilter($this->createDependencyTreeForeignEngineBundleFilter());
            $treeFilter->addFilter($this->createDependencyTreeEngineBundleFilter());
        }

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
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyGraphBuilderInterface
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
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyGraph\GraphBuilderInterface
     */
    protected function createExternalGraphBuilder()
    {
        return new ExternalGraphBuilder($this->getGraph()->init('Dependency Tree'));
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\AdjacencyMatrixBuilderInterface
     */
    public function createAdjacencyMatrixBuilder()
    {
        $adjacencyMatrixBuilder = new AdjacencyMatrixBuilder(
            $this->createDependencyManager()->collectAllModules(),
            $this->createDependencyTreeReader(),
            $this->createAdjacencyMatrixDependencyTreeFilter()
        );

        return $adjacencyMatrixBuilder;
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\TreeFilterInterface
     */
    protected function createAdjacencyMatrixDependencyTreeFilter()
    {
        $treeFilter = $this->createDependencyTreeFilter();
        $treeFilter
            ->addFilter($this->createDependencyTreeInvalidForeignBundleFilter());

        return $treeFilter;
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\ViolationChecker\DependencyViolationCheckerInterface
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
     * @return \Spryker\Zed\Development\Business\DependencyTree\ViolationFinder\ViolationFinderInterface
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
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\DependencyFilterInterface
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
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\DependencyFilterInterface
     */
    protected function createDependencyTreeClassNameFilter($pattern)
    {
        return new ClassNameFilter($pattern);
    }

    /**
     * @param string $moduleToView
     *
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\DependencyFilterInterface
     */
    protected function createDependencyTreeBundleToViewFilter($moduleToView)
    {
        return new BundleToViewFilter($moduleToView);
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\ViolationFinder\ViolationFinderInterface
     */
    protected function createViolationFinderUseForeignConstants()
    {
        return new UseForeignConstants();
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\ViolationFinder\ViolationFinderInterface
     */
    protected function createViolationFinderUseForeignException()
    {
        return new UseForeignException();
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\ViolationFinder\ViolationFinderInterface
     */
    protected function createViolationFinderBundleUsesConnector()
    {
        return new BundleUsesConnector();
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\DependencyFilterInterface
     */
    protected function createDependencyTreeConstantsToForeignConstantsFilter()
    {
        return new ConstantsToForeignConstantsFilter();
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\DependencyFilterInterface
     */
    protected function createDependencyTreeForeignEngineBundleFilter()
    {
        return new ForeignEngineBundleFilter(
            $this->getConfig()->getPathToBundleConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\DependencyFilterInterface
     */
    protected function createDependencyTreeEngineBundleFilter()
    {
        return new EngineBundleFilter(
            $this->getConfig()->getPathToBundleConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\DependencyFilterInterface
     */
    protected function createDependencyTreeInvalidForeignBundleFilter()
    {
        return new InvalidForeignBundleFilter(
            $this->createDependencyManager()->collectAllModules()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\DependencyFilterInterface
     */
    protected function createDependencyTreeExternalDependencyFilter()
    {
        return new ExternalDependencyFilter();
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\DependencyFilterInterface
     */
    protected function createDependencyTreeInternalDependencyFilter()
    {
        return new InternalDependencyFilter();
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\TreeFilterCompositeInterface
     */
    protected function createDependencyTreeFilter()
    {
        return new TreeFilter();
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
     * @return \Spryker\Zed\Development\Business\Composer\ComposerJsonUpdaterInterface
     */
    public function createComposerJsonUpdater()
    {
        return new ComposerJsonUpdater(
            $this->createComposerJsonFinderComposite(),
            $this->createComposerJsonUpdaterComposite()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Composer\ComposerJsonFinderInterface
     */
    protected function createComposerJsonFinderComposite()
    {
        $finderComposite = new ComposerJsonFinderComposite();
        $finderComposite->addFinder($this->createComposerJsonFinder());
        $finderComposite->addFinder($this->createComposerJsonFinderSdk());
        $finderComposite->addFinder($this->createComposerJsonFinderShop());

        return $finderComposite;
    }

    /**
     * @return \Spryker\Zed\Development\Business\Composer\ComposerJsonFinderInterface
     */
    protected function createComposerJsonFinder()
    {
        $composerJsonFinder = new ComposerJsonFinder(
            $this->createFinder(),
            $this->getConfig()->getPathToCore()
        );

        return $composerJsonFinder;
    }

    /**
     * @return \Spryker\Zed\Development\Business\Composer\ComposerJsonFinderInterface
     */
    protected function createComposerJsonFinderSdk()
    {
        $composerJsonFinder = new ComposerJsonFinder(
            $this->createFinder(),
            $this->getConfig()->getPathToSdk()
        );

        return $composerJsonFinder;
    }

    /**
     * @return \Spryker\Zed\Development\Business\Composer\ComposerJsonFinderInterface
     */
    protected function createComposerJsonFinderShop()
    {
        $composerJsonFinder = new ComposerJsonFinder(
            $this->createFinder(),
            $this->getConfig()->getPathToShop()
        );

        return $composerJsonFinder;
    }

    /**
     * @return \Spryker\Zed\Development\Business\Composer\Updater\ComposerUpdaterCompositeInterface
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
            ->addUpdater($this->createComposerJsonAutoloadUpdater())
            ->addUpdater($this->createComposerJsonBranchAliasUpdater());

        return $updaterComposite;
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function createFinder()
    {
        return new SymfonyFinder();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Composer\Updater\UpdaterInterface
     */
    protected function createComposerJsonDescriptionUpdater()
    {
        return new DescriptionUpdater();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Composer\Updater\UpdaterInterface
     */
    protected function createComposerJsonLicenseUpdater()
    {
        return new LicenseUpdater();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Composer\Updater\UpdaterInterface
     */
    protected function createComposerJsonRequireUpdater()
    {
        return new RequireUpdater(
            $this->createDependencyTreeReader(),
            $this->createComposerJsonRequireUpdaterTreeFilter()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Composer\Updater\UpdaterInterface
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
     * @return \Spryker\Zed\Development\Business\Composer\Updater\UpdaterInterface
     */
    protected function createComposerJsonStabilityUpdater()
    {
        return new StabilityUpdater('dev');
    }

    /**
     * @return \Spryker\Zed\Development\Business\Composer\Updater\UpdaterInterface
     */
    protected function createComposerJsonAutoloadUpdater()
    {
        return new AutoloadUpdater();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Composer\Updater\UpdaterInterface
     */
    protected function createComposerJsonBranchAliasUpdater()
    {
        return new BranchAliasUpdater();
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\TreeFilterCompositeInterface
     */
    protected function createComposerJsonRequireUpdaterTreeFilter()
    {
        $treeFilter = new TreeFilter();
        $treeFilter
            ->addFilter($this->createDependencyTreeClassNameFilter('/\\Dependency\\\(.*?)Interface/'))
            ->addFilter($this->createDependencyTreeInvalidForeignBundleFilter());

        return $treeFilter;
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\ComposerDependencyParserInterface
     */
    public function createComposerDependencyParser()
    {
        return new ComposerDependencyParser($this->createComposerJsonFinderComposite());
    }

    /**
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionWriterInterface
     */
    public function createYvesIdeAutoCompletionWriter()
    {
        return $this->createIdeAutoCompletionWriter(
            $this->createYvesIdeAutoCompletionBundleBuilder(),
            $this->getConfig()->getYvesIdeAutoCompletionOptions()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\BundleBuilderInterface
     */
    protected function createYvesIdeAutoCompletionBundleBuilder()
    {
        return new IdeAutoCompletionBundleBuilder(
            $this->getYvesIdeAutoCompletionMethodBuilderStack(),
            $this->getConfig()->getYvesIdeAutoCompletionOptions()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionWriterInterface
     */
    public function createZedIdeAutoCompletionWriter()
    {
        return $this->createIdeAutoCompletionWriter(
            $this->createZedIdeAutoCompletionBundleBuilder(),
            $this->getConfig()->getZedIdeAutoCompletionOptions()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\BundleBuilderInterface
     */
    protected function createZedIdeAutoCompletionBundleBuilder()
    {
        return new IdeAutoCompletionBundleBuilder(
            $this->getZedIdeAutoCompletionMethodBuilderStack(),
            $this->getConfig()->getZedIdeAutoCompletionOptions()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\BundleBuilderInterface
     */
    protected function createGlueIdeAutoCompletionBundleBuilder()
    {
        return new IdeAutoCompletionBundleBuilder(
            $this->createGlueAutoCompletionMethodBuilderStack(),
            $this->getConfig()->getGlueIdeAutoCompletionOptions()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionWriterInterface
     */
    public function createGlueIdeAutoCompletionWriter()
    {
        return $this->createIdeAutoCompletionWriter(
            $this->createGlueIdeAutoCompletionBundleBuilder(),
            $this->getConfig()->getGlueIdeAutoCompletionOptions()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionWriterInterface
     */
    public function createClientIdeAutoCompletionWriter()
    {
        return $this->createIdeAutoCompletionWriter(
            $this->createClientIdeAutoCompletionBundleBuilder(),
            $this->getConfig()->getClientIdeAutoCompletionOptions()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\BundleBuilderInterface
     */
    protected function createClientIdeAutoCompletionBundleBuilder()
    {
        return new IdeAutoCompletionBundleBuilder(
            $this->getClientIdeAutoCompletionMethodBuilderStack(),
            $this->getConfig()->getClientIdeAutoCompletionOptions()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionWriterInterface
     */
    public function createServiceIdeAutoCompletionWriter()
    {
        return $this->createIdeAutoCompletionWriter(
            $this->createServiceIdeAutoCompletionBundleBuilder(),
            $this->getConfig()->getServiceIdeAutoCompletionOptions()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\BundleBuilderInterface
     */
    protected function createServiceIdeAutoCompletionBundleBuilder()
    {
        return new IdeAutoCompletionBundleBuilder(
            $this->getServiceIdeAutoCompletionMethodBuilderStack(),
            $this->getConfig()->getServiceIdeAutoCompletionOptions()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder\BundleMethodBuilderInterface[]
     */
    protected function getYvesIdeAutoCompletionMethodBuilderStack()
    {
        return [
            $this->createIdeAutoCompletionClientMethodBuilder(),
            $this->createIdeAutoCompletionServiceMethodBuilder(),
        ];
    }

    /**
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder\BundleMethodBuilderInterface[]
     */
    protected function getZedIdeAutoCompletionMethodBuilderStack()
    {
        return [
            $this->createIdeAutoCompletionFacadeMethodBuilder(),
            $this->createIdeAutoCompletionQueryContainerMethodBuilder(),
            $this->createIdeAutoCompletionServiceMethodBuilder(),
        ];
    }

    /**
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder\BundleMethodBuilderInterface[]
     */
    protected function createGlueAutoCompletionMethodBuilderStack()
    {
        return [
            $this->createIdeAutoCompletionResourceMethodBuild(),
            $this->createIdeAutoCompletionClientMethodBuilder(),
            $this->createIdeAutoCompletionServiceMethodBuilder(),
        ];
    }

    /**
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder\BundleMethodBuilderInterface[]
     */
    protected function getClientIdeAutoCompletionMethodBuilderStack()
    {
        return [
            $this->createIdeAutoCompletionClientMethodBuilder(),
        ];
    }

    /**
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder\BundleMethodBuilderInterface[]
     */
    protected function getServiceIdeAutoCompletionMethodBuilderStack()
    {
        return [
            $this->createIdeAutoCompletionServiceMethodBuilder(),
        ];
    }

    /**
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder\BundleMethodBuilderInterface
     */
    protected function createIdeAutoCompletionFacadeMethodBuilder()
    {
        return new FacadeMethodBuilder($this->createIdeAutoCompletionNamespaceExtractor());
    }

    /**
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder\BundleMethodBuilderInterface
     */
    protected function createIdeAutoCompletionQueryContainerMethodBuilder()
    {
        return new QueryContainerMethodBuilder($this->createIdeAutoCompletionNamespaceExtractor());
    }

    /**
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder\BundleMethodBuilderInterface
     */
    protected function createIdeAutoCompletionClientMethodBuilder()
    {
        return new ClientMethodBuilder($this->createIdeAutoCompletionNamespaceExtractor());
    }

    /**
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder\BundleMethodBuilderInterface
     */
    protected function createIdeAutoCompletionServiceMethodBuilder()
    {
        return new ServiceMethodBuilder($this->createIdeAutoCompletionNamespaceExtractor());
    }

    /**
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder\BundleMethodBuilderInterface
     */
    protected function createIdeAutoCompletionResourceMethodBuild()
    {
        return new ResourceMethodBuilder($this->createIdeAutoCompletionNamespaceExtractor());
    }

    /**
     * @param \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\BundleBuilderInterface $bundleBuilder
     * @param array $options
     *
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionWriterInterface
     */
    protected function createIdeAutoCompletionWriter(BundleBuilderInterface $bundleBuilder, array $options)
    {
        return new IdeAutoCompletionWriter(
            $this->getIdeAutoCompletionGeneratorStack($options),
            $this->createIdeAutoCompletionBundleFinder($bundleBuilder),
            $options
        );
    }

    /**
     * @param \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\BundleBuilderInterface $bundleBuilder
     *
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\BundleFinderInterface
     */
    protected function createIdeAutoCompletionBundleFinder(BundleBuilderInterface $bundleBuilder)
    {
        return new BundleFinder(
            $this->getProvidedDependency(DevelopmentDependencyProvider::FINDER),
            $bundleBuilder,
            $this->getConfig()->getIdeAutoCompletionSourceDirectoryGlobPatterns()
        );
    }

    /**
     * @param array $options
     *
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\Generator\GeneratorInterface[]
     */
    protected function getIdeAutoCompletionGeneratorStack(array $options)
    {
        return [
            $this->createIdeAutoCompletionBundleGenerator($options),
            $this->createIdeAutoCompletionBundleMethodGenerator($options),
        ];
    }

    /**
     * @param array $options
     *
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\Generator\GeneratorInterface
     */
    protected function createIdeAutoCompletionBundleGenerator(array $options)
    {
        return new BundleGenerator($this->getTwigEnvironment(), $options);
    }

    /**
     * @param array $options
     *
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\Generator\GeneratorInterface
     */
    protected function createIdeAutoCompletionBundleMethodGenerator(array $options)
    {
        return new BundleMethodGenerator($this->getTwigEnvironment(), $options);
    }

    /**
     * @return \Twig_Environment
     */
    protected function getTwigEnvironment()
    {
        $twigEnvironment = $this->getProvidedDependency(DevelopmentDependencyProvider::TWIG_ENVIRONMENT);
        $twigEnvironment->setLoader($this->getTwigFilesystemLoader());

        return $twigEnvironment;
    }

    /**
     * @return \Twig_LoaderInterface
     */
    protected function getTwigFilesystemLoader()
    {
        $filesystemLoader = $this->getProvidedDependency(DevelopmentDependencyProvider::TWIG_LOADER_FILESYSTEM);
        $filesystemLoader->setPaths($this->getConfig()->getIdeAutoCompletionGeneratorTemplatePaths());

        return $filesystemLoader;
    }

    /**
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\NamespaceExtractorInterface
     */
    protected function createIdeAutoCompletionNamespaceExtractor()
    {
        return new NamespaceExtractor();
    }

    /**
     * @return \Spryker\Zed\Development\Business\ArchitectureSniffer\ArchitectureSnifferInterface
     */
    public function createArchitectureSniffer()
    {
        $xml = $this->createXmlReader();
        $command = $this->getConfig()->getArchitectureSnifferCommand();
        $defaultPriority = $this->getConfig()->getArchitectureSnifferDefaultPriority();

        return new ArchitectureSniffer($xml, $command, $defaultPriority);
    }

    /**
     * @return \Zend\Config\Reader\ReaderInterface
     */
    protected function createXmlReader()
    {
        return new Xml();
    }

    /**
     * @return \Spryker\Zed\Development\Business\ArchitectureSniffer\AllBundleFinderInterface
     */
    public function createArchitectureBundleFinder()
    {
        return new AllBundleFinder(
            $this->createFinder(),
            $this->createCamelCaseToDashFilter(),
            $this->getConfig()->getProjectNamespaces(),
            $this->getConfig()->getCoreNamespaces()
        );
    }

    /**
     * @return \Zend\Filter\FilterInterface
     */
    protected function createCamelCaseToDashFilter()
    {
        return new CamelCaseToDash();
    }
}

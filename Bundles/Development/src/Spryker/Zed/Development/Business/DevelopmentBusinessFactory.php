<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Development\Business;

use Spryker\Zed\Development\Business\CodeBuilder\Bridge\BridgeBuilder;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\BundleFilter;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\EngineBundleFilter;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\LocatorClient;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\LocatorFacade;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\LocatorQueryContainer;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\UseStatement;
use Spryker\Zed\Development\Business\DependencyTree\DependencyGraph\GraphBuilder;
use Spryker\Zed\Development\Business\DependencyTree\DependencyReport\DependencyReport;
use Spryker\Zed\Development\Business\DependencyTree\DependencyTreeBuilder;
use Spryker\Zed\Development\Business\DependencyTree\DependencyTreeReader\JsonDependencyTreeReader;
use Spryker\Zed\Development\Business\DependencyTree\DependencyTreeWriter\JsonDependencyTreeWriter;
use Spryker\Zed\Development\Business\DependencyTree\FileInfoExtractor;
use Spryker\Zed\Development\Business\DependencyTree\Finder;
use Spryker\Zed\Development\Business\PhpMd\PhpMdRunner;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Development\Business\CodeStyleFixer\CodeStyleFixer;
use Spryker\Zed\Development\Business\CodeStyleSniffer\CodeStyleSniffer;
use Spryker\Zed\Development\Business\CodeTest\CodeTester;
use Spryker\Zed\Development\DevelopmentConfig;

/**
 * @method DevelopmentConfig getConfig()
 */
class DevelopmentBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return CodeStyleFixer
     */
    public function createCodeStyleFixer()
    {
        return new CodeStyleFixer(
            $this->getConfig()->getPathToRoot(),
            $this->getConfig()->getBundleDirectory()
        );
    }

    /**
     * @return CodeStyleSniffer
     */
    public function createCodeStyleSniffer()
    {
        return new CodeStyleSniffer(
            $this->getConfig()->getPathToRoot(),
            $this->getConfig()->getBundleDirectory()
        );
    }

    /**
     * @return CodeTester
     */
    public function createCodeTester()
    {
        return new CodeTester(
            $this->getConfig()->getPathToRoot(),
            $this->getConfig()->getBundleDirectory()
        );
    }

    /**
     * @return PhpMdRunner
     */
    public function createPhpMdRunner()
    {
        return new PhpMdRunner(
            $this->getConfig()->getPathToRoot(),
            $this->getConfig()->getBundleDirectory()
        );
    }

    /**
     * @return BridgeBuilder
     */
    public function createBridgeBuilder()
    {
        return new BridgeBuilder();
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
        $report = $this->createDependencyTreeReport();
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
     * @return DependencyReport
     */
    protected function createDependencyTreeReport()
    {
        $fileInfoExtractor = $this->createDependencyTreeFileInfoExtractor();

        return new DependencyReport($fileInfoExtractor);
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
        return new JsonDependencyTreeWriter($this->createPathToJsonDependencyTree());
    }

    /**
     * @return JsonDependencyTreeReader
     */
    public function createDependencyTreeReader()
    {
        return new JsonDependencyTreeReader($this->createPathToJsonDependencyTree());
    }

    /**
     * @return string
     */
    protected function createPathToJsonDependencyTree()
    {
        $pathParts = [
            APPLICATION_VENDOR_DIR,
            'spryker',
            'spryker',
            'dependencyTree.json',
        ];

        return implode(DIRECTORY_SEPARATOR, $pathParts);
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
     * @return GraphBuilder
     */
    public function createDependencyGraphBuilder()
    {
        return new GraphBuilder($this->createDependencyGraphFilter());
    }

    /**
     * @return array
     */
    protected function createDependencyGraphFilter()
    {
        return [
            $this->createDependencyTreeEngineBundleFilter(),
            $this->createDependencyTreeBundleFilter()
        ];
    }

    /**
     * @return EngineBundleFilter
     */
    protected function createDependencyTreeEngineBundleFilter()
    {
        return new EngineBundleFilter();
    }

    /**
     * @return BundleFilter
     */
    protected function createDependencyTreeBundleFilter()
    {
        return new BundleFilter();
    }

}

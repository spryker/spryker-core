<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business;

use Nette\DI\Config\Loader;
use Spryker\Zed\Development\Business\ArchitectureSniffer\AllBundleFinder;
use Spryker\Zed\Development\Business\ArchitectureSniffer\AllModuleFinder;
use Spryker\Zed\Development\Business\ArchitectureSniffer\AllModuleFinderInterface;
use Spryker\Zed\Development\Business\ArchitectureSniffer\ArchitectureSniffer;
use Spryker\Zed\Development\Business\ArchitectureSniffer\ArchitectureSnifferInterface;
use Spryker\Zed\Development\Business\CodeBuilder\Bridge\BridgeBuilder;
use Spryker\Zed\Development\Business\CodeBuilder\Module\ModuleBuilder;
use Spryker\Zed\Development\Business\Codeception\Argument\Builder\CodeceptionArgumentsBuilder;
use Spryker\Zed\Development\Business\Codeception\Argument\Builder\CodeceptionArgumentsBuilderInterface;
use Spryker\Zed\Development\Business\CodeStyleSniffer\CodeStyleSniffer;
use Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfiguration;
use Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationInterface;
use Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationLoader;
use Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationLoaderInterface;
use Spryker\Zed\Development\Business\CodeTest\CodeTester;
use Spryker\Zed\Development\Business\Composer\ComposerJson;
use Spryker\Zed\Development\Business\Composer\ComposerJsonFinder;
use Spryker\Zed\Development\Business\Composer\ComposerJsonFinderInterface;
use Spryker\Zed\Development\Business\Composer\ComposerJsonInterface;
use Spryker\Zed\Development\Business\Composer\ComposerJsonUpdater;
use Spryker\Zed\Development\Business\Composer\ComposerJsonUpdaterInterface;
use Spryker\Zed\Development\Business\Composer\ComposerNameFinder;
use Spryker\Zed\Development\Business\Composer\ComposerNameFinderInterface;
use Spryker\Zed\Development\Business\Composer\Updater\AutoloadUpdater;
use Spryker\Zed\Development\Business\Composer\Updater\BranchAliasUpdater;
use Spryker\Zed\Development\Business\Composer\Updater\ComposerUpdaterComposite;
use Spryker\Zed\Development\Business\Composer\Updater\DescriptionUpdater;
use Spryker\Zed\Development\Business\Composer\Updater\LicenseUpdater;
use Spryker\Zed\Development\Business\Composer\Updater\RequireUpdater;
use Spryker\Zed\Development\Business\Composer\Updater\StabilityUpdater;
use Spryker\Zed\Development\Business\Composer\Updater\TypeUpdater;
use Spryker\Zed\Development\Business\Composer\Validator\ComposerJsonUnboundRequireConstraintValidator;
use Spryker\Zed\Development\Business\Composer\Validator\ComposerJsonValidatorComposite;
use Spryker\Zed\Development\Business\Composer\Validator\ComposerJsonValidatorInterface;
use Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainer;
use Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\BehaviorDependencyFinder;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\CodeceptionDependencyFinder;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\ComposerDependencyFinder;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\ComposerScriptDependencyFinder;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderComposite;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderInterface;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\ExtensionDependencyFinder;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\ExternalDependencyFinder;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\InternalDependencyFinder;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\LocatorDependencyFinder;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\ModuleAnnotationDependencyFinder;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\PersistenceDependencyFinder;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\SprykerSdkDependencyFinder;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\TravisDependencyFinder;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\TwigDependencyFinder;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\TwigDependencyFinder\AtomFunctionDependencyFinder;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\TwigDependencyFinder\MoleculeFunctionDependencyFinder;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\TwigDependencyFinder\OrganismFunctionDependencyFinder;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\TwigDependencyFinder\TemplateFunctionDependencyFinder;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\TwigDependencyFinder\TwigDependencyFinderInterface;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\TwigDependencyFinder\ViewFunctionDependencyFinder;
use Spryker\Zed\Development\Business\Dependency\Manager;
use Spryker\Zed\Development\Business\Dependency\ModuleDependencyParser;
use Spryker\Zed\Development\Business\Dependency\ModuleDependencyParserInterface;
use Spryker\Zed\Development\Business\Dependency\ModuleParser\UseStatementParser;
use Spryker\Zed\Development\Business\Dependency\ModuleParser\UseStatementParserInterface;
use Spryker\Zed\Development\Business\Dependency\SchemaParser\PropelSchemaParser;
use Spryker\Zed\Development\Business\Dependency\SchemaParser\PropelSchemaParserInterface;
use Spryker\Zed\Development\Business\Dependency\TwigFileFinder\TwigFileFinder;
use Spryker\Zed\Development\Business\Dependency\TwigFileFinder\TwigFileFinderInterface;
use Spryker\Zed\Development\Business\Dependency\Validator\DependencyValidator;
use Spryker\Zed\Development\Business\Dependency\Validator\DependencyValidatorInterface;
use Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleAmbiguousModuleName;
use Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleComposite;
use Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleDevelopmentOnlyDependency;
use Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleInRequireAndRequireDev;
use Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleInSourceAndInSuggested;
use Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleInSourceNotInRequire;
use Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleInterface;
use Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleInTestNotInRequireDev;
use Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleIsOptionalButInRequire;
use Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleIsOptionalButNotInRequireDev;
use Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleIsOptionalButNotSuggested;
use Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleNotInSourceButInRequire;
use Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleNotInTestButInRequireDev;
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
use Spryker\Zed\Development\Business\DependencyTree\Finder\PathBuilder\SprykerMerchantPortalPathBuilder;
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
use Spryker\Zed\Development\Business\IdeAutoCompletion\FileWriter;
use Spryker\Zed\Development\Business\IdeAutoCompletion\FileWriterInterface;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Generator\BundleGenerator;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Generator\BundleMethodGenerator;
use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionWriter;
use Spryker\Zed\Development\Business\Integration\DependencyProviderUsedPluginFinder;
use Spryker\Zed\Development\Business\Integration\DependencyProviderUsedPluginFinderInterface;
use Spryker\Zed\Development\Business\Module\ModuleFileFinder\ModuleFileFinder;
use Spryker\Zed\Development\Business\Module\ModuleFileFinder\ModuleFileFinderInterface;
use Spryker\Zed\Development\Business\Module\ModuleFinder\ModuleFinder;
use Spryker\Zed\Development\Business\Module\ModuleFinder\ModuleFinderInterface;
use Spryker\Zed\Development\Business\Module\ModuleMatcher\ModuleMatcher;
use Spryker\Zed\Development\Business\Module\ModuleMatcher\ModuleMatcherInterface;
use Spryker\Zed\Development\Business\Module\ModuleOverview;
use Spryker\Zed\Development\Business\Module\ModuleOverviewInterface;
use Spryker\Zed\Development\Business\Module\PathBuilder\PathBuilderComposite;
use Spryker\Zed\Development\Business\Module\PathBuilder\PathBuilderInterface;
use Spryker\Zed\Development\Business\Module\PathBuilder\SprykerEcoModulePathBuilder;
use Spryker\Zed\Development\Business\Module\PathBuilder\SprykerMerchantPortalModulePathBuilder;
use Spryker\Zed\Development\Business\Module\PathBuilder\SprykerModulePathBuilder;
use Spryker\Zed\Development\Business\Module\PathBuilder\SprykerSdkModulePathBuilder;
use Spryker\Zed\Development\Business\Module\PathBuilder\SprykerShopModulePathBuilder;
use Spryker\Zed\Development\Business\Module\PathBuilder\SprykerStandaloneModulePathBuilder;
use Spryker\Zed\Development\Business\Module\ProjectModuleFinder\ProjectModuleFinder;
use Spryker\Zed\Development\Business\Module\ProjectModuleFinder\ProjectModuleFinderInterface;
use Spryker\Zed\Development\Business\Package\PackageFinder\PackageFinder;
use Spryker\Zed\Development\Business\Package\PackageFinder\PackageFinderInterface;
use Spryker\Zed\Development\Business\PhpMd\PhpMdRunner;
use Spryker\Zed\Development\Business\Phpstan\Config\PhpstanConfigFileFinder;
use Spryker\Zed\Development\Business\Phpstan\Config\PhpstanConfigFileFinderInterface;
use Spryker\Zed\Development\Business\Phpstan\Config\PhpstanConfigFileManager;
use Spryker\Zed\Development\Business\Phpstan\Config\PhpstanConfigFileManagerInterface;
use Spryker\Zed\Development\Business\Phpstan\PhpstanRunner;
use Spryker\Zed\Development\Business\Propel\PropelAbstractClassValidator;
use Spryker\Zed\Development\Business\Propel\PropelAbstractClassValidatorInterface;
use Spryker\Zed\Development\Business\SnifferConfiguration\Builder\ArchitectureSnifferConfigurationBuilder;
use Spryker\Zed\Development\Business\SnifferConfiguration\Builder\SnifferConfigurationBuilderInterface;
use Spryker\Zed\Development\Business\SnifferConfiguration\ConfigurationReader\ConfigurationReader;
use Spryker\Zed\Development\Business\SnifferConfiguration\ConfigurationReader\ConfigurationReaderInterface;
use Spryker\Zed\Development\Business\Stability\StabilityCalculator;
use Spryker\Zed\Development\Dependency\Facade\DevelopmentToModuleFinderFacadeInterface;
use Spryker\Zed\Development\DevelopmentDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder as SymfonyFinder;
use Symfony\Component\Yaml\Parser;
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
    public function createCodeStyleSniffer(): CodeStyleSniffer
    {
        return new CodeStyleSniffer(
            $this->getConfig(),
            $this->createCodeStyleSnifferConfigurationLoader()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationLoaderInterface
     */
    public function createCodeStyleSnifferConfigurationLoader(): CodeStyleSnifferConfigurationLoaderInterface
    {
        return new CodeStyleSnifferConfigurationLoader(
            $this->createConfigurationReader(),
            $this->createCodeStyleSnifferConfiguration()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationInterface
     */
    public function createCodeStyleSnifferConfiguration(): CodeStyleSnifferConfigurationInterface
    {
        return new CodeStyleSnifferConfiguration($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Development\Business\CodeTest\CodeTester
     */
    public function createCodeTester()
    {
        return new CodeTester(
            $this->getConfig()->getPathToRoot(),
            $this->getConfig()->getPathToCore(),
            $this->createConfigArgumentCollectionBuilder(),
            $this->getConfig()->getProcessTimeout()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\PhpMd\PhpMdRunner
     */
    public function createPhpMdRunner()
    {
        return new PhpMdRunner(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Phpstan\PhpstanRunnerInterface
     */
    public function createPhpstanRunner()
    {
        return new PhpstanRunner(
            $this->getConfig(),
            $this->createPhpstanConfigFileFinder(),
            $this->createPhpstanConfigFileManager()
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
     * @return \Spryker\Zed\Development\Business\Dependency\ModuleDependencyParserInterface
     */
    public function createModuleDependencyParser(): ModuleDependencyParserInterface
    {
        return new ModuleDependencyParser(
            $this->createModuleFileFinder(),
            $this->createDependencyContainer(),
            $this->createDependencyFinder()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Module\ModuleFileFinder\ModuleFileFinderInterface
     */
    public function createModuleFileFinder(): ModuleFileFinderInterface
    {
        return new ModuleFileFinder($this->createPathBuilder());
    }

    /**
     * @return \Spryker\Zed\Development\Business\Module\PathBuilder\PathBuilderInterface
     */
    public function createPathBuilder(): PathBuilderInterface
    {
        return new PathBuilderComposite([
            $this->createSprykerStandaloneModuleFilePathBuilder(),
            $this->createSprykerModuleFilePathBuilder(),
            $this->createSprykerShopModuleFilePathBuilder(),
            $this->createSprykerEcoModuleFilePathBuilder(),
            $this->createSprykerSdkModulePathBuilder(),
            $this->createSprykerMerchantPortalModulePathBuilder(),
        ]);
    }

    /**
     * @return \Spryker\Zed\Development\Business\Module\PathBuilder\PathBuilderInterface
     */
    public function createSprykerStandaloneModuleFilePathBuilder(): PathBuilderInterface
    {
        return new SprykerStandaloneModulePathBuilder($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Development\Business\Module\PathBuilder\PathBuilderInterface
     */
    public function createSprykerModuleFilePathBuilder(): PathBuilderInterface
    {
        return new SprykerModulePathBuilder(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Module\PathBuilder\PathBuilderInterface
     */
    public function createSprykerShopModuleFilePathBuilder(): PathBuilderInterface
    {
        return new SprykerShopModulePathBuilder(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Module\PathBuilder\PathBuilderInterface
     */
    public function createSprykerEcoModuleFilePathBuilder(): PathBuilderInterface
    {
        return new SprykerEcoModulePathBuilder(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Module\PathBuilder\PathBuilderInterface
     */
    public function createSprykerSdkModulePathBuilder(): PathBuilderInterface
    {
        return new SprykerSdkModulePathBuilder(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Module\PathBuilder\PathBuilderInterface
     */
    public function createSprykerMerchantPortalModulePathBuilder(): PathBuilderInterface
    {
        return new SprykerMerchantPortalModulePathBuilder($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface
     */
    public function createDependencyContainer(): DependencyContainerInterface
    {
        return new DependencyContainer();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderInterface
     */
    public function createDependencyFinder(): DependencyFinderInterface
    {
        return new DependencyFinderComposite([
            $this->createSprykerSdkDependencyFinder(),
            $this->createInternalDependencyFinder(),
            $this->createExternalDependencyFinder(),
            $this->createExtensionDependencyFinder(),
            $this->createLocatorDependencyFinder(),
            $this->createPersistenceDependencyFinder(),
            $this->createBehaviorDependencyFinder(),
            $this->createTwigDependencyFinder(),
            $this->createComposerDependencyFinder(),
            $this->createTravisDependencyFinder(),
            $this->createComposerScriptDependencyFinder(),
            $this->createCodeceptionDependencyFinder(),
            $this->createModuleAnnotationDependencyFinder(),
        ]);
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderInterface
     */
    public function createSprykerSdkDependencyFinder(): DependencyFinderInterface
    {
        return new SprykerSdkDependencyFinder(
            $this->createUseStatementParser(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderInterface
     */
    public function createInternalDependencyFinder(): DependencyFinderInterface
    {
        return new InternalDependencyFinder(
            $this->createUseStatementParser(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderInterface
     */
    public function createExternalDependencyFinder(): DependencyFinderInterface
    {
        return new ExternalDependencyFinder(
            $this->createUseStatementParser(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderInterface
     */
    public function createExtensionDependencyFinder(): DependencyFinderInterface
    {
        return new ExtensionDependencyFinder(
            $this->getModuleFinderFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderInterface
     */
    public function createLocatorDependencyFinder(): DependencyFinderInterface
    {
        return new LocatorDependencyFinder();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderInterface
     */
    public function createPersistenceDependencyFinder(): DependencyFinderInterface
    {
        return new PersistenceDependencyFinder(
            $this->createPropelSchemaParser()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderInterface
     */
    public function createBehaviorDependencyFinder(): DependencyFinderInterface
    {
        return new BehaviorDependencyFinder(
            $this->getModuleFinderFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\SchemaParser\PropelSchemaParserInterface
     */
    public function createPropelSchemaParser(): PropelSchemaParserInterface
    {
        return new PropelSchemaParser(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\ModuleParser\UseStatementParserInterface
     */
    public function createUseStatementParser(): UseStatementParserInterface
    {
        return new UseStatementParser();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderInterface
     */
    public function createTwigDependencyFinder(): DependencyFinderInterface
    {
        return new TwigDependencyFinder(
            $this->getTwigDependencyFinder()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderInterface
     */
    public function createComposerDependencyFinder(): DependencyFinderInterface
    {
        return new ComposerDependencyFinder();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderInterface
     */
    public function createTravisDependencyFinder(): DependencyFinderInterface
    {
        return new TravisDependencyFinder();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderInterface
     */
    public function createComposerScriptDependencyFinder(): DependencyFinderInterface
    {
        return new ComposerScriptDependencyFinder();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderInterface
     */
    public function createCodeceptionDependencyFinder(): DependencyFinderInterface
    {
        return new CodeceptionDependencyFinder();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyFinder\DependencyFinderInterface
     */
    public function createModuleAnnotationDependencyFinder(): DependencyFinderInterface
    {
        return new ModuleAnnotationDependencyFinder();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\TwigFileFinder\TwigFileFinderInterface
     */
    public function createTwigFinder(): TwigFileFinderInterface
    {
        return new TwigFileFinder($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyFinder\TwigDependencyFinder\TwigDependencyFinderInterface[]
     */
    public function getTwigDependencyFinder(): array
    {
        return [
            $this->createAtomFunctionDependencyFinder(),
            $this->createMoleculeFunctionDependencyFinder(),
            $this->createOrganismFunctionDependencyFinder(),
            $this->createTemplateFunctionDependencyFinder(),
            $this->createViewFunctionDependencyFinder(),
        ];
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyFinder\TwigDependencyFinder\TwigDependencyFinderInterface
     */
    public function createAtomFunctionDependencyFinder(): TwigDependencyFinderInterface
    {
        return new AtomFunctionDependencyFinder();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyFinder\TwigDependencyFinder\TwigDependencyFinderInterface
     */
    public function createMoleculeFunctionDependencyFinder(): TwigDependencyFinderInterface
    {
        return new MoleculeFunctionDependencyFinder();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyFinder\TwigDependencyFinder\TwigDependencyFinderInterface
     */
    public function createOrganismFunctionDependencyFinder(): TwigDependencyFinderInterface
    {
        return new OrganismFunctionDependencyFinder();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyFinder\TwigDependencyFinder\TwigDependencyFinderInterface
     */
    public function createTemplateFunctionDependencyFinder(): TwigDependencyFinderInterface
    {
        return new TemplateFunctionDependencyFinder();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyFinder\TwigDependencyFinder\TwigDependencyFinderInterface
     */
    public function createViewFunctionDependencyFinder(): TwigDependencyFinderInterface
    {
        return new ViewFunctionDependencyFinder();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\ManagerInterface
     */
    public function createDependencyManager()
    {
        return new Manager(
            $this->createModuleDependencyParser(),
            $this->getConfig()
        );
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
            ->addFinder($this->createSprykerMerchantPortalFinder())
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
     * @return \Spryker\Zed\Development\Business\DependencyTree\Finder\FinderInterface
     */
    protected function createSprykerMerchantPortalFinder()
    {
        $finder = new FileFinder(
            $this->createSprykerMerchantPortalPathBuilder()
        );

        return $finder;
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\Finder\PathBuilder\PathBuilderInterface
     */
    protected function createSprykerPathBuilder()
    {
        return new SprykerPathBuilder(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\DependencyTree\Finder\PathBuilder\PathBuilderInterface
     */
    protected function createSprykerMerchantPortalPathBuilder()
    {
        return new SprykerMerchantPortalPathBuilder(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\Validator\DependencyValidatorInterface
     */
    public function createDependencyValidator(): DependencyValidatorInterface
    {
        return new DependencyValidator(
            $this->createModuleDependencyParser(),
            $this->createComposerDependencyParser(),
            $this->createDependencyValidationRules(),
            $this->createComposerNameFinder()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleInterface
     */
    public function createDependencyValidationRules(): ValidationRuleInterface
    {
        return new ValidationRuleComposite([
            $this->createValidationRuleAmbiguousModuleName(),
            $this->createValidationRuleDevelopmentOnlyDependency(),
            $this->createValidationRuleInSourceNotInRequire(),
            $this->createValidationRuleNotInSourceButInRequire(),
            $this->createValidationRuleInTestNotInRequireDev(),
            $this->createValidationRuleNotInTestButInRequireDev(),
            $this->createValidationRuleIsOptionalButInRequire(),
            $this->createValidationRuleIsOptionalButNotInRequireDev(),
            $this->createValidationRuleIsOptionalButNotSuggested(),
            $this->createValidationRuleInSourceAndInSuggested(),
            $this->createValidationRuleInRequireAndRequireDev(),
        ]);
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleInterface
     */
    public function createValidationRuleAmbiguousModuleName(): ValidationRuleInterface
    {
        return new ValidationRuleAmbiguousModuleName();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleInterface
     */
    public function createValidationRuleDevelopmentOnlyDependency(): ValidationRuleInterface
    {
        return new ValidationRuleDevelopmentOnlyDependency();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleInterface
     */
    public function createValidationRuleInSourceNotInRequire(): ValidationRuleInterface
    {
        return new ValidationRuleInSourceNotInRequire();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleInterface
     */
    public function createValidationRuleNotInSourceButInRequire(): ValidationRuleInterface
    {
        return new ValidationRuleNotInSourceButInRequire();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleInterface
     */
    public function createValidationRuleInTestNotInRequireDev(): ValidationRuleInterface
    {
        return new ValidationRuleInTestNotInRequireDev();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleInterface
     */
    public function createValidationRuleNotInTestButInRequireDev(): ValidationRuleInterface
    {
        return new ValidationRuleNotInTestButInRequireDev();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleInterface
     */
    public function createValidationRuleIsOptionalButInRequire(): ValidationRuleInterface
    {
        return new ValidationRuleIsOptionalButInRequire();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleInterface
     */
    public function createValidationRuleIsOptionalButNotInRequireDev(): ValidationRuleInterface
    {
        return new ValidationRuleIsOptionalButNotInRequireDev();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleInterface
     */
    public function createValidationRuleIsOptionalButNotSuggested(): ValidationRuleInterface
    {
        return new ValidationRuleIsOptionalButNotSuggested();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleInterface
     */
    public function createValidationRuleInSourceAndInSuggested(): ValidationRuleInterface
    {
        return new ValidationRuleInSourceAndInSuggested();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleInterface
     */
    public function createValidationRuleInRequireAndRequireDev(): ValidationRuleInterface
    {
        return new ValidationRuleInRequireAndRequireDev();
    }

    /**
     * @deprecated Use `spryker/module-finder` instead.
     *
     * @return \Spryker\Zed\Development\Business\Module\ModuleFinder\ModuleFinderInterface
     */
    public function createModuleFinder(): ModuleFinderInterface
    {
        return new ModuleFinder($this->getConfig(), $this->createModuleMatcher());
    }

    /**
     * @deprecated Use `spryker/module-finder` instead.
     *
     * @return \Spryker\Zed\Development\Business\Module\ModuleMatcher\ModuleMatcherInterface
     */
    public function createModuleMatcher(): ModuleMatcherInterface
    {
        return new ModuleMatcher();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Composer\ComposerJsonInterface
     */
    public function createComposerJsonValidator(): ComposerJsonInterface
    {
        return new ComposerJson(
            $this->createComposerJsonValidatorComposite()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Composer\Validator\ComposerJsonValidatorInterface
     */
    public function createComposerJsonValidatorComposite(): ComposerJsonValidatorInterface
    {
        return new ComposerJsonValidatorComposite([
            $this->createComposerJsonUnboundRequireConstraintValidator(),
        ]);
    }

    /**
     * @return \Spryker\Zed\Development\Business\Composer\Validator\ComposerJsonValidatorInterface
     */
    public function createComposerJsonUnboundRequireConstraintValidator(): ComposerJsonValidatorInterface
    {
        return new ComposerJsonUnboundRequireConstraintValidator();
    }

    /**
     * @return \Spryker\Zed\Development\Business\Integration\DependencyProviderUsedPluginFinderInterface
     */
    public function createDependencyProviderUsedPluginFinder(): DependencyProviderUsedPluginFinderInterface
    {
        return new DependencyProviderUsedPluginFinder(
            $this->getModuleFinderFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Module\ModuleOverviewInterface
     */
    public function createModuleOverview(): ModuleOverviewInterface
    {
        return new ModuleOverview($this->getModuleFinderFacade());
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
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Propel\PropelAbstractClassValidatorInterface
     */
    public function createPropelAbstractValidator(): PropelAbstractClassValidatorInterface
    {
        return new PropelAbstractClassValidator();
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
            $this->getConfig()
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
            $this->getConfig()
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
            $this->createModuleDependencyParser(),
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
     * @deprecated This is only used by an unused facade method.
     *
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
     * @deprecated Not used anymore.
     *
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
     * @deprecated Not used anymore.
     *
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
     * @deprecated Not used anymore.
     *
     * @return \Spryker\Zed\Development\Business\DependencyTree\ViolationFinder\ViolationFinderInterface
     */
    protected function createViolationFinderUseForeignConstants()
    {
        return new UseForeignConstants();
    }

    /**
     * @deprecated Not used anymore.
     *
     * @return \Spryker\Zed\Development\Business\DependencyTree\ViolationFinder\ViolationFinderInterface
     */
    protected function createViolationFinderUseForeignException()
    {
        return new UseForeignException();
    }

    /**
     * @deprecated Not used anymore.
     *
     * @return \Spryker\Zed\Development\Business\DependencyTree\ViolationFinder\ViolationFinderInterface
     */
    protected function createViolationFinderBundleUsesConnector()
    {
        return new BundleUsesConnector();
    }

    /**
     * @deprecated Not used anymore.
     *
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
    public function createComposerJsonUpdater(): ComposerJsonUpdaterInterface
    {
        return new ComposerJsonUpdater(
            $this->createComposerJsonFinder(),
            $this->createComposerJsonUpdaterComposite()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Composer\ComposerJsonFinderInterface
     */
    protected function createComposerJsonFinder(): ComposerJsonFinderInterface
    {
        $composerJsonFinder = new ComposerJsonFinder(
            $this->createFinder()
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
            ->addUpdater($this->createComposerJsonTypeUpdater())
            ->addUpdater($this->createComposerJsonDescriptionUpdater())
            ->addUpdater($this->createComposerJsonLicenseUpdater())
            ->addUpdater($this->createComposerJsonRequireUpdater())
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
    protected function createComposerJsonTypeUpdater()
    {
        return new TypeUpdater();
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
        return new RequireUpdater();
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
        return new ComposerDependencyParser(
            $this->createComposerNameFinder()
        );
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
            $this->createIdeAutoCompletionClientMethodBuilder(),
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
            $this->createIdeAutoCompletionServiceMethodBuilder(),
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
            $this->createIdeAutoCompletionBundleFinder($bundleBuilder)
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
        return new BundleGenerator($this->getTwigEnvironment(), $this->createFileWriter(), $options);
    }

    /**
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\FileWriterInterface
     */
    protected function createFileWriter(): FileWriterInterface
    {
        return new FileWriter();
    }

    /**
     * @param array $options
     *
     * @return \Spryker\Zed\Development\Business\IdeAutoCompletion\Generator\GeneratorInterface
     */
    protected function createIdeAutoCompletionBundleMethodGenerator(array $options)
    {
        return new BundleMethodGenerator($this->getTwigEnvironment(), $this->createFileWriter(), $options);
    }

    /**
     * @return \Twig\Environment
     */
    protected function getTwigEnvironment()
    {
        $twigEnvironment = $this->getProvidedDependency(DevelopmentDependencyProvider::TWIG_ENVIRONMENT);
        $twigEnvironment->setLoader($this->getTwigFilesystemLoader());

        return $twigEnvironment;
    }

    /**
     * @return \Twig\Loader\LoaderInterface
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
    public function createArchitectureSniffer(): ArchitectureSnifferInterface
    {
        $xml = $this->createXmlReader();
        $command = $this->getConfig()->getArchitectureSnifferCommand();

        return new ArchitectureSniffer(
            $xml,
            $command,
            $this->createArchitectureSnifferConfigurationBuilder()
        );
    }

    /**
     * @return \Zend\Config\Reader\ReaderInterface
     */
    protected function createXmlReader()
    {
        return new Xml();
    }

    /**
     * @deprecated use `createAllModuleFinder` instead.
     *
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
     * @return \Spryker\Zed\Development\Business\ArchitectureSniffer\AllModuleFinderInterface
     */
    public function createAllModuleFinder(): AllModuleFinderInterface
    {
        return new AllModuleFinder(
            $this->createFinder(),
            $this->getConfig(),
            $this->createCamelCaseToDashFilter()
        );
    }

    /**
     * @return \Zend\Filter\FilterInterface
     */
    protected function createCamelCaseToDashFilter()
    {
        return new CamelCaseToDash();
    }

    /**
     * @deprecated Use `spryker/module-finder` instead.
     *
     * @return \Spryker\Zed\Development\Business\Module\ProjectModuleFinder\ProjectModuleFinderInterface
     */
    public function createProjectModuleFinder(): ProjectModuleFinderInterface
    {
        return new ProjectModuleFinder($this->getConfig(), $this->createModuleMatcher());
    }

    /**
     * @deprecated Use `spryker/module-finder` instead.
     *
     * @return \Spryker\Zed\Development\Business\Package\PackageFinder\PackageFinderInterface
     */
    public function createPackageFinder(): PackageFinderInterface
    {
        return new PackageFinder($this->getConfig());
    }

    /**
     * @return \Symfony\Component\Yaml\Parser
     */
    public function createYamlParser(): Parser
    {
        return new Parser();
    }

    /**
     * @return \Spryker\Zed\Development\Business\SnifferConfiguration\ConfigurationReader\ConfigurationReaderInterface
     */
    public function createConfigurationReader(): ConfigurationReaderInterface
    {
        return new ConfigurationReader(
            $this->createYamlParser()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\SnifferConfiguration\Builder\SnifferConfigurationBuilderInterface
     */
    public function createArchitectureSnifferConfigurationBuilder(): SnifferConfigurationBuilderInterface
    {
        return new ArchitectureSnifferConfigurationBuilder(
            $this->createConfigurationReader(),
            $this->getConfig()->getArchitectureSnifferDefaultPriority()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Phpstan\Config\PhpstanConfigFileFinderInterface
     */
    protected function createPhpstanConfigFileFinder(): PhpstanConfigFileFinderInterface
    {
        return new PhpstanConfigFileFinder($this->createFinder(), $this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Development\Business\Phpstan\Config\PhpstanConfigFileManagerInterface
     */
    protected function createPhpstanConfigFileManager(): PhpstanConfigFileManagerInterface
    {
        return new PhpstanConfigFileManager($this->getFilesystem(), $this->getConfig(), $this->getConfigLoader());
    }

    /**
     * @return \Symfony\Component\Filesystem\Filesystem
     */
    protected function getFilesystem(): Filesystem
    {
        return $this->getProvidedDependency(DevelopmentDependencyProvider::FILESYSTEM);
    }

    /**
     * @return \Nette\DI\Config\Loader
     */
    protected function getConfigLoader(): Loader
    {
        return $this->getProvidedDependency(DevelopmentDependencyProvider::CONFIG_LOADER);
    }

    /**
     * @return \Spryker\Zed\Development\Dependency\Facade\DevelopmentToModuleFinderFacadeInterface
     */
    public function getModuleFinderFacade(): DevelopmentToModuleFinderFacadeInterface
    {
        return $this->getProvidedDependency(DevelopmentDependencyProvider::FACADE_MODULE_FINDER);
    }

    /**
     * @return \Spryker\Zed\Development\Business\Codeception\Argument\Builder\CodeceptionArgumentsBuilderInterface
     */
    public function createConfigArgumentCollectionBuilder(): CodeceptionArgumentsBuilderInterface
    {
        return new CodeceptionArgumentsBuilder(
            $this->getConfig()->getDefaultInclusiveGroups()
        );
    }

    /**
     * @return \Spryker\Zed\Development\Business\Composer\ComposerNameFinderInterface
     */
    public function createComposerNameFinder(): ComposerNameFinderInterface
    {
        return new ComposerNameFinder($this->getModuleFinderFacade());
    }
}

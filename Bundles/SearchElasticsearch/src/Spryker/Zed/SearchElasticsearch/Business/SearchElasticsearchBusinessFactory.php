<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business;

use Elastica\Client;
use Spryker\Client\SearchElasticsearch\Provider\SearchElasticsearchClientProvider;
use Spryker\Shared\SearchElasticsearch\Index\IndexNameResolver;
use Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Builder\IndexDefinitionBuilder;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Builder\IndexDefinitionBuilderInterface;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Finder\IndexDefinitionFinder;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Finder\IndexDefinitionFinderInterface;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Loader\IndexDefinitionLoader;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Loader\IndexDefinitionLoaderInterface;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Merger\IndexDefinitionMerger;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Merger\IndexDefinitionMergerInterface;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Reader\IndexDefinitionReader;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Reader\IndexDefinitionReaderInterface;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\IndexInstallBroker;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\IndexInstallBrokerInterface;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Install\IndexInstaller as ES6IndexInstaller;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\InstallerInterface;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping\MappingBuilder;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping\MappingBuilderInterface;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Update\IndexSettingsUpdater;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Update\IndexUpdater;
use Spryker\Zed\SearchElasticsearch\Business\Installer\IndexMap\Cleaner\IndexMapCleaner as CleanerIndexMapCleaner;
use Spryker\Zed\SearchElasticsearch\Business\Installer\IndexMap\Cleaner\IndexMapCleanerInterface;
use Spryker\Zed\SearchElasticsearch\Business\Installer\IndexMap\Generator\IndexMapGenerator as GeneratorIndexMapGenerator;
use Spryker\Zed\SearchElasticsearch\Business\Installer\IndexMap\Generator\IndexMapGeneratorInterface;
use Spryker\Zed\SearchElasticsearch\Business\Installer\IndexMap\IndexMapInstaller as IndexMapIndexMapInstaller;
use Spryker\Zed\SearchElasticsearch\Business\Installer\IndexMap\IndexMapInstallerInterface;
use Spryker\Zed\SearchElasticsearch\Dependency\Service\SearchToUtilEncodingInterface;
use Spryker\Zed\SearchElasticsearch\SearchElasticsearchDependencyProvider;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * @method \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig getConfig()
 */
class SearchElasticsearchBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Elastica\Client
     */
    public function getElasticsearchClient(): Client
    {
        /** @var \Elastica\Client $client */
        $client = $this
            ->createSearchClientProvider()
            ->getInstance();

        return $client;
    }

    /**
     * @return \Spryker\Client\SearchElasticsearch\Provider\SearchElasticsearchClientProvider
     */
    public function createSearchClientProvider(): SearchElasticsearchClientProvider
    {
        return new SearchElasticsearchClientProvider();
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Definition\Loader\IndexDefinitionLoaderInterface
     */
    public function createIndexDefinitionLoader(): IndexDefinitionLoaderInterface
    {
        return new IndexDefinitionLoader(
            $this->createIndexDefinitionFinder(),
            $this->createIndexDefinitionReader(),
            $this->createIndexNameResolver()
        );
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Definition\Finder\IndexDefinitionFinderInterface
     */
    public function createIndexDefinitionFinder(): IndexDefinitionFinderInterface
    {
        return new IndexDefinitionFinder(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Definition\Reader\IndexDefinitionReaderInterface
     */
    public function createIndexDefinitionReader(): IndexDefinitionReaderInterface
    {
        return new IndexDefinitionReader(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Dependency\Service\SearchToUtilEncodingInterface
     */
    public function getUtilEncodingService(): SearchToUtilEncodingInterface
    {
        return $this->getProvidedDependency(SearchElasticsearchDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Definition\Merger\IndexDefinitionMergerInterface
     */
    public function createIndexDefinitionMerger(): IndexDefinitionMergerInterface
    {
        return new IndexDefinitionMerger();
    }

    /**
     * @return \Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface
     */
    public function createIndexNameResolver(): IndexNameResolverInterface
    {
        return new IndexNameResolver(
            $this->getConfig()->getIndexNameMap()
        );
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Installer\Index\IndexInstallBrokerInterface
     */
    public function createIndexInstallBroker(): IndexInstallBrokerInterface
    {
        return new IndexInstallBroker(
            $this->createIndexDefinitionBuilder(),
            $this->getInstaller()
        );
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Definition\Builder\IndexDefinitionBuilderInterface
     */
    public function createIndexDefinitionBuilder(): IndexDefinitionBuilderInterface
    {
        return new IndexDefinitionBuilder(
            $this->createIndexDefinitionLoader(),
            $this->createIndexDefinitionMerger(),
            $this->createIndexNameResolver()
        );
    }

    /**
     * Order of returned installer matters. Updater must go before installer to not update directly after install.
     *
     * @return array
     */
    public function getInstaller(): array
    {
        return [
            $this->createIndexUpdater(),
            $this->createIndexSettingsUpdater(),
            $this->createIndexInstaller(),
        ];
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Installer\Index\InstallerInterface
     */
    public function createIndexInstaller(): InstallerInterface
    {
        return new ES6IndexInstaller(
            $this->getElasticsearchClient(),
            $this->createMappingBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping\MappingBuilderInterface
     */
    public function createMappingBuilder(): MappingBuilderInterface
    {
        return new MappingBuilder();
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Installer\Index\InstallerInterface
     */
    public function createIndexUpdater(): InstallerInterface
    {
        return new IndexUpdater(
            $this->getElasticsearchClient(),
            $this->createMappingBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Installer\Index\InstallerInterface
     */
    public function createIndexSettingsUpdater(): InstallerInterface
    {
        return new IndexSettingsUpdater(
            $this->getElasticsearchClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Installer\IndexMap\IndexMapInstallerInterface
     */
    public function createIndexMapperInstaller(): IndexMapInstallerInterface
    {
        return new IndexMapIndexMapInstaller(
            $this->createIndexDefinitionBuilder(),
            $this->createIndexMapCleaner(),
            $this->createIndexMapGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Installer\IndexMap\Cleaner\IndexMapCleanerInterface
     */
    public function createIndexMapCleaner(): IndexMapCleanerInterface
    {
        return new CleanerIndexMapCleaner(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Installer\IndexMap\Generator\IndexMapGeneratorInterface
     */
    public function createIndexMapGenerator(): IndexMapGeneratorInterface
    {
        return new GeneratorIndexMapGenerator(
            $this->getConfig(),
            $this->createTwig()
        );
    }

    /**
     * @return \Twig\Environment
     */
    public function createTwig(): Environment
    {
        return new Environment(
            $this->createFilesystemLoader()
        );
    }

    /**
     * @return \Twig\Loader\FilesystemLoader
     */
    public function createFilesystemLoader(): FilesystemLoader
    {
        return new FilesystemLoader($this->getConfig()->getIndexMapClassTemplateDirectory());
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business;

use Elastica\Client;
use Elastica\Snapshot as ElasticaSnapshot;
use Spryker\Shared\SearchElasticsearch\Dependency\Client\SearchElasticsearchToStoreClientInterface;
use Spryker\Shared\SearchElasticsearch\ElasticaClient\ElasticaClientFactory;
use Spryker\Shared\SearchElasticsearch\ElasticaClient\ElasticaClientFactoryInterface;
use Spryker\Shared\SearchElasticsearch\Index\IndexNameResolver;
use Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SearchElasticsearch\Business\DataMapper\DataMapperInterface;
use Spryker\Zed\SearchElasticsearch\Business\DataMapper\Delegator\DataMapperDelegator;
use Spryker\Zed\SearchElasticsearch\Business\DataMapper\Delegator\DataMapperDelegatorInterface;
use Spryker\Zed\SearchElasticsearch\Business\DataMapper\Page\PageDataMapper;
use Spryker\Zed\SearchElasticsearch\Business\DataMapper\Page\PageMapBuilder;
use Spryker\Zed\SearchElasticsearch\Business\DataMapper\ProductReview\ProductReviewDataMapper;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Builder\IndexDefinitionBuilder;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Builder\IndexDefinitionBuilderInterface;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Finder\SchemaDefinitionFinder;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Finder\SchemaDefinitionFinderInterface;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Loader\IndexDefinitionLoader;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Loader\IndexDefinitionLoaderInterface;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Merger\IndexDefinitionMerger;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Merger\IndexDefinitionMergerInterface;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Reader\IndexDefinitionReader;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Reader\IndexDefinitionReaderInterface;
use Spryker\Zed\SearchElasticsearch\Business\Index\Index;
use Spryker\Zed\SearchElasticsearch\Business\Index\IndexInterface;
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
use Spryker\Zed\SearchElasticsearch\Business\Snapshot\Repository;
use Spryker\Zed\SearchElasticsearch\Business\Snapshot\RepositoryInterface;
use Spryker\Zed\SearchElasticsearch\Business\Snapshot\Snapshot;
use Spryker\Zed\SearchElasticsearch\Business\Snapshot\SnapshotInterface;
use Spryker\Zed\SearchElasticsearch\Dependency\Service\SearchElasticsearchToUtilEncodingServiceInterface;
use Spryker\Zed\SearchElasticsearch\Dependency\Service\SearchElasticsearchToUtilSanitizeServiceInterface;
use Spryker\Zed\SearchElasticsearch\SearchElasticsearchDependencyProvider;
use Spryker\Zed\SearchElasticsearchExtension\Business\DataMapper\PageMapBuilderInterface;
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
        return $this->createElasticsearchClientFactory()->createClient(
            $this->getConfig()->getClientConfig()
        );
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Definition\Loader\IndexDefinitionLoaderInterface
     */
    public function createIndexDefinitionLoader(): IndexDefinitionLoaderInterface
    {
        return new IndexDefinitionLoader(
            $this->createIndexDefinitionFinder(),
            $this->createIndexDefinitionReader()
        );
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Definition\Finder\SchemaDefinitionFinderInterface
     */
    public function createIndexDefinitionFinder(): SchemaDefinitionFinderInterface
    {
        return new SchemaDefinitionFinder(
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
     * @return \Spryker\Zed\SearchElasticsearch\Dependency\Service\SearchElasticsearchToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): SearchElasticsearchToUtilEncodingServiceInterface
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
            $this->getStoreClient()
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
            $this->getConfig(),
            $this->getUtilSanitizeService()
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

    /**
     * @return \Spryker\Shared\SearchElasticsearch\ElasticaClient\ElasticaClientFactoryInterface
     */
    public function createElasticsearchClientFactory(): ElasticaClientFactoryInterface
    {
        return new ElasticaClientFactory();
    }

    /**
     * @return \Spryker\Shared\SearchElasticsearch\Dependency\Client\SearchElasticsearchToStoreClientInterface
     */
    public function getStoreClient(): SearchElasticsearchToStoreClientInterface
    {
        return $this->getProvidedDependency(SearchElasticsearchDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Dependency\Service\SearchElasticsearchToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService(): SearchElasticsearchToUtilSanitizeServiceInterface
    {
        return $this->getProvidedDependency(SearchElasticsearchDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Index\IndexInterface
     */
    public function createIndex(): IndexInterface
    {
        return new Index(
            $this->getElasticsearchClient(),
            $this->createIndexNameResolver(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Snapshot\SnapshotInterface
     */
    public function createSnapshot(): SnapshotInterface
    {
        return new Snapshot(
            $this->createElasticaSnapshot()
        );
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Snapshot\RepositoryInterface
     */
    public function createRepository(): RepositoryInterface
    {
        return new Repository(
            $this->createElasticaSnapshot()
        );
    }

    /**
     * @return \Elastica\Snapshot
     */
    public function createElasticaSnapshot(): ElasticaSnapshot
    {
        return new ElasticaSnapshot($this->getElasticsearchClient());
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\DataMapper\DataMapperInterface
     */
    public function createPageDataMapper(): DataMapperInterface
    {
        return new PageDataMapper($this->createPageMapBuilder(), $this->getPageDataMapperPlugins());
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\DataMapper\DataMapperInterface
     */
    public function createProductReviewDataMapper(): DataMapperInterface
    {
        return new ProductReviewDataMapper();
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearchExtension\Business\DataMapper\PageMapBuilderInterface
     */
    public function createPageMapBuilder(): PageMapBuilderInterface
    {
        return new PageMapBuilder();
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearchExtension\Dependency\Plugin\PageMapPluginInterface[]
     */
    public function getPageDataMapperPlugins(): array
    {
        return $this->getProvidedDependency(SearchElasticsearchDependencyProvider::PLUGINS_PAGE_DATA_MAPPER);
    }

    /**
     * @api
     *
     * @return \Spryker\Zed\SearchElasticsearch\Business\DataMapper\Delegator\DataMapperDelegatorInterface
     */
    public function createDataMapperDelegator(): DataMapperDelegatorInterface
    {
        return new DataMapperDelegator($this->getResourceDataMapperPlugins());
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearchExtension\Dependency\Plugin\ResourceDataMapperPluginInterface[]
     */
    public function getResourceDataMapperPlugins(): array
    {
        return $this->getProvidedDependency(SearchElasticsearchDependencyProvider::PLUGINS_RESOURCE_DATA_MAPPER);
    }
}

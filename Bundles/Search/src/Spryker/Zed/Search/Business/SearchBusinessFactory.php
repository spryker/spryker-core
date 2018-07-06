<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business;

use Elastica\Snapshot;
use Psr\Log\LoggerInterface;
use Spryker\Client\Search\Provider\IndexClientProvider;
use Spryker\Client\Search\Provider\SearchClientProvider;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Copier\IndexCopier;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageDataMapper;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilder;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\JsonIndexDefinitionLoader;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\JsonIndexDefinitionMerger;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapCleaner;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapGenerator;
use Spryker\Zed\Search\Business\Model\Elasticsearch\IndexInstaller;
use Spryker\Zed\Search\Business\Model\Elasticsearch\IndexMapInstaller;
use Spryker\Zed\Search\Business\Model\Elasticsearch\SearchIndexManager;
use Spryker\Zed\Search\Business\Model\Elasticsearch\SnapshotHandler;
use Spryker\Zed\Search\Business\Model\SearchInstaller;
use Spryker\Zed\Search\SearchDependencyProvider;

/**
 * @method \Spryker\Zed\Search\SearchConfig getConfig()
 */
class SearchBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return \Spryker\Zed\Search\Business\Model\SearchInstallerInterface
     */
    public function createSearchInstaller(LoggerInterface $messenger)
    {
        return new SearchInstaller($this->getSearchInstallerStack($messenger));
    }

    /**
     * @return \Spryker\Zed\Search\Business\Model\Elasticsearch\SearchIndexManagerInterface
     */
    public function createSearchIndexManager()
    {
        return new SearchIndexManager($this->getElasticsearchIndex());
    }

    /**
     * @return \Spryker\Zed\Search\Business\Model\Elasticsearch\SearchIndexManagerInterface
     */
    public function createSearchIndicesManager()
    {
        return new SearchIndexManager($this->getElasticsearchIndex('_all'));
    }

    /**
     * @return \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionLoaderInterface
     */
    protected function createJsonIndexDefinitionLoader()
    {
        return new JsonIndexDefinitionLoader(
            $this->getConfig()->getJsonIndexDefinitionDirectories(),
            $this->createJsonIndexDefinitionMerger(),
            $this->getUtilEncodingService(),
            [Store::getInstance()->getStoreName()]
        );
    }

    /**
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return \Spryker\Zed\Search\Business\Model\SearchInstallerInterface[]
     */
    protected function getSearchInstallerStack(LoggerInterface $messenger)
    {
        return [
            $this->createElasticsearchIndexInstaller($messenger),
            $this->createIndexMapInstaller($messenger),
        ];
    }

    /**
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return \Spryker\Zed\Search\Business\Model\SearchInstallerInterface
     */
    protected function createElasticsearchIndexInstaller(LoggerInterface $messenger)
    {
        return new IndexInstaller(
            $this->createJsonIndexDefinitionLoader(),
            $this->getElasticsearchClient(),
            $messenger,
            $this->getConfig()->getIndexDefinitionBlacklistedSettings()
        );
    }

    /**
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return \Spryker\Zed\Search\Business\Model\SearchInstallerInterface
     */
    public function createIndexMapInstaller(LoggerInterface $messenger)
    {
        return new IndexMapInstaller(
            $this->createJsonIndexDefinitionLoader(),
            $this->createElasticsearchIndexMapCleaner(),
            $this->createElasticsearchIndexMapGenerator(),
            $messenger
        );
    }

    /**
     * @return \Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapGeneratorInterface
     */
    protected function createElasticsearchIndexMapGenerator()
    {
        return new IndexMapGenerator($this->getConfig()->getClassTargetDirectory());
    }

    /**
     * @return \Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapCleanerInterface
     */
    protected function createElasticsearchIndexMapCleaner()
    {
        return new IndexMapCleaner($this->getConfig()->getClassTargetDirectory());
    }

    /**
     * @return \Elastica\Client
     */
    public function getElasticsearchClient()
    {
        return $this
            ->createSearchClientProvider()
            ->getInstance();
    }

    /**
     * @return \Spryker\Client\Search\Provider\SearchClientProvider
     */
    protected function createSearchClientProvider()
    {
        return new SearchClientProvider();
    }

    /**
     * @param null|string $index
     *
     * @return \Elastica\Index
     */
    protected function getElasticsearchIndex($index = null)
    {
        return $this
            ->createIndexProvider()
            ->getClient($index);
    }

    /**
     * @return \Spryker\Client\Search\Provider\IndexClientProvider
     */
    protected function createIndexProvider()
    {
        return new IndexClientProvider();
    }

    /**
     * @return \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionMergerInterface
     */
    protected function createJsonIndexDefinitionMerger()
    {
        return new JsonIndexDefinitionMerger();
    }

    /**
     * @return \Spryker\Client\Search\SearchClientInterface
     */
    public function getSearchClient()
    {
        return $this->getProvidedDependency(SearchDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageDataMapperInterface
     */
    public function createPageDataMapper()
    {
        return new PageDataMapper(
            $this->createPageMapBuilder(),
            $this->getSearchPageMapPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface
     */
    protected function createPageMapBuilder()
    {
        return new PageMapBuilder();
    }

    /**
     * @return \Spryker\Zed\Search\Dependency\Service\SearchToUtilEncodingInterface
     */
    protected function getUtilEncodingService()
    {
        return $this->getProvidedDependency(SearchDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\Search\Dependency\Plugin\PageMapInterface[]
     */
    public function getSearchPageMapPlugins()
    {
        return $this->getProvidedDependency(SearchDependencyProvider::PLUGIN_SEARCH_PAGE_MAPS);
    }

    /**
     * @return \Spryker\Zed\Search\Business\Model\Elasticsearch\SnapshotHandlerInterface
     */
    public function createSnapshotHandler()
    {
        return new SnapshotHandler($this->createElasticsearchSnapshot());
    }

    /**
     * @return \Elastica\Snapshot
     */
    protected function createElasticsearchSnapshot()
    {
        return new Snapshot($this->getElasticsearchClient());
    }

    /**
     * @return \Spryker\Zed\Search\Business\Model\Elasticsearch\Copier\IndexCopierInterface
     */
    public function createElasticsearchIndexCopier()
    {
        return new IndexCopier(
            $this->getGuzzleClient(),
            $this->getConfig()->getReindexUrl()
        );
    }

    /**
     * @return \GuzzleHttp\Client
     */
    protected function getGuzzleClient()
    {
        return $this->getProvidedDependency(SearchDependencyProvider::GUZZLE_CLIENT);
    }
}

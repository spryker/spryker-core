<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business;

use Spryker\Client\Search\Provider\IndexClientProvider;
use Spryker\Client\Search\Provider\SearchClientProvider;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Messenger\Business\Model\MessengerInterface;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageDataMapper;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilder;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\JsonIndexDefinitionLoader;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\JsonIndexDefinitionMerger;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapCleaner;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapGenerator;
use Spryker\Zed\Search\Business\Model\Elasticsearch\IndexInstaller;
use Spryker\Zed\Search\Business\Model\Elasticsearch\IndexMapInstaller;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Query\SearchKeysQuery;
use Spryker\Zed\Search\Business\Model\Elasticsearch\SearchIndexManager;
use Spryker\Zed\Search\Business\Model\SearchInstaller;
use Spryker\Zed\Search\SearchDependencyProvider;

/**
 * @method \Spryker\Zed\Search\SearchConfig getConfig()
 */
class SearchBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     *
     * @return \Spryker\Zed\Search\Business\Model\SearchInstallerInterface
     */
    public function createSearchInstaller(MessengerInterface $messenger)
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
     * @return \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\JsonIndexDefinitionLoader
     */
    protected function createJsonIndexDefinitionLoader()
    {
        return new JsonIndexDefinitionLoader(
            $this->getConfig()->getJsonIndexDefinitionDirectories(),
            $this->createJsonIndexDefinitionMerger(),
            Store::getInstance()->getAllowedStores()
        );
    }

    /**
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     *
     * @return \Spryker\Zed\Search\Business\Model\SearchInstallerInterface[]
     */
    protected function getSearchInstallerStack(MessengerInterface $messenger)
    {
        return [
            $this->createElasticsearchIndexInstaller($messenger),
            $this->createIndexMapInstaller($messenger),
        ];
    }

    /**
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     *
     * @return \Spryker\Zed\Search\Business\Model\SearchInstallerInterface
     */
    protected function createElasticsearchIndexInstaller(MessengerInterface $messenger)
    {
        return new IndexInstaller(
            $this->createJsonIndexDefinitionLoader(),
            $this->getElasticsearchClient(),
            $messenger
        );
    }

    /**
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     *
     * @return \Spryker\Zed\Search\Business\Model\SearchInstallerInterface
     */
    protected function createIndexMapInstaller(MessengerInterface $messenger)
    {
        return new IndexMapInstaller(
            $this->createJsonIndexDefinitionLoader(),
            $this->createElasticsearchIndexMapCleaner(),
            $this->createElasticsearchIndexMapGenerator(),
            $messenger
        );
    }

    /**
     * @return \Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapGenerator
     */
    protected function createElasticsearchIndexMapGenerator()
    {
        return new IndexMapGenerator($this->getConfig()->getClassTargetDirectory());
    }

    /**
     * @return \Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapCleaner
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
     * @return \Elastica\Index
     */
    protected function getElasticsearchIndex()
    {
        return $this
            ->createIndexProvider()
            ->getClient();
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
     * @param string $searchString
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function createSearchKeysQuery($searchString)
    {
        return new SearchKeysQuery($searchString);
    }

    /**
     * @return \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageDataMapperInterface
     */
    public function createPageDataMapper()
    {
        return new PageDataMapper($this->createPageMapBuilder());
    }

    /**
     * @return \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface
     */
    protected function createPageMapBuilder()
    {
        return new PageMapBuilder();
    }

}

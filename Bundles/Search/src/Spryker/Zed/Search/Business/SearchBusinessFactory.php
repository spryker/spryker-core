<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business;

use Spryker\Shared\Library\Storage\StorageInstanceBuilder;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Messenger\Business\Model\MessengerInterface;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\XmlIndexDefinitionLoader;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapCleaner;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapGenerator;
use Spryker\Zed\Search\Business\Model\Elasticsearch\IndexInstaller;
use Spryker\Zed\Search\Business\Model\Elasticsearch\IndexMapInstaller;
use Spryker\Zed\Search\Business\Model\Search;
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
     * @return \Spryker\Zed\Search\Business\Model\Search
     */
    public function createSearch()
    {
        return new Search(
            $this->getProvidedDependency(SearchDependencyProvider::CLIENT_SEARCH)
        );
    }

    /**
     * @return \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\XmlIndexDefinitionLoader
     */
    protected function createXmlIndexDefinitionLoader()
    {
        return new XmlIndexDefinitionLoader($this->getConfig()->getXmlIndexDefinitionDirectories());
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
            $this->createXmlIndexDefinitionLoader(),
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
            $this->createXmlIndexDefinitionLoader(),
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
    protected function getElasticsearchClient()
    {
        // FIXME
        return StorageInstanceBuilder::getElasticsearchInstance();
    }

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business;

use Spryker\Shared\Library\Storage\StorageInstanceBuilder;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Messenger\Business\Model\MessengerInterface;
use Spryker\Zed\Search\Business\Model\Elasticsearch\IndexInstaller;
use Spryker\Zed\Search\Business\Model\Elasticsearch\XmlIndexDefinitionLoader;
use Spryker\Zed\Search\Business\Model\Search;
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
    public function createElasticsearchIndexInstaller(MessengerInterface $messenger)
    {
        return new IndexInstaller(
            $this->createXmlIndexDefinitionLoader(),
            $this->getElasticsearchClient(),
            $messenger
        );
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
     * @return \Elastica\Client
     */
    protected function getElasticsearchClient()
    {
        // FIXME
        return StorageInstanceBuilder::getElasticsearchInstance();
    }

    /**
     * @return \Spryker\Zed\Search\Business\Model\Elasticsearch\XmlIndexDefinitionLoader
     */
    protected function createXmlIndexDefinitionLoader()
    {
        return new XmlIndexDefinitionLoader($this->getConfig()->getXmlIndexDefinitionDirectories());
    }

}

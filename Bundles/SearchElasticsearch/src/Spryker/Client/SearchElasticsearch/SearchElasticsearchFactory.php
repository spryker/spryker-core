<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch;

use Elastica\Client;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\SearchElasticsearch\Search\Search;
use Spryker\Client\SearchElasticsearch\Search\SearchInterface;
use Spryker\Shared\SearchElasticsearch\ElasticsearchClient\ElasticsearchClientFactory;
use Spryker\Shared\SearchElasticsearch\ElasticsearchClient\ElasticsearchClientFactoryInterface;
use Spryker\Shared\SearchElasticsearch\Index\IndexNameResolver;
use Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface;

/**
 * @method \Spryker\Client\SearchElasticsearch\SearchElasticsearchConfig getConfig()
 */
class SearchElasticsearchFactory extends AbstractFactory
{
    /**
     * @var \Elastica\Client
     */
    protected static $client;

    /**
     * @return \Spryker\Client\SearchElasticsearch\Search\SearchInterface
     */
    public function createSearch(): SearchInterface
    {
        return new Search(
            $this->getElasticsearchClient(),
            $this->createIndexNameResolver()
        );
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
     * @return \Elastica\Client
     */
    public function getElasticsearchClient(): Client
    {
        return $this->createElasticsearchClientFactory()->createClient(
            $this->getConfig()->getClientConfig()
        );
    }

    /**
     * @return \Spryker\Shared\SearchElasticsearch\ElasticsearchClient\ElasticsearchClientFactoryInterface
     */
    public function createElasticsearchClientFactory(): ElasticsearchClientFactoryInterface
    {
        return new ElasticsearchClientFactory();
    }
}

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

/**
 * @method \Spryker\Client\SearchElasticsearch\SearchElasticsearchConfig getConfig()
 */
class SearchElasticsearchFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\SearchElasticsearch\Search\SearchInterface
     */
    public function createSearch(): SearchInterface
    {
        return new Search($this->getClient());
    }

    /**
     * @return \Elastica\Client
     */
    public function getClient(): Client
    {
        return new Client($this->getConfig()->getClientConfig());
    }
}

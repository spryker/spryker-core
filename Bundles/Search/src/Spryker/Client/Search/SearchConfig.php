<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\Search\SearchConstants;

/**
 * @method \Spryker\Shared\Search\SearchConfig getSharedConfig()
 */
class SearchConfig extends AbstractBundleConfig
{
    public const FACET_NAME_AGGREGATION_SIZE = 10;

    /**
     * @deprecated Use `\Spryker\Client\SearchExtension\Dependency\Plugin\NamedQueryInterface::getIndexName()` in your Query class to determine which index should be used for the specific query.
     *
     * @return string
     */
    public function getSearchIndexName()
    {
        return $this->get(SearchConstants::ELASTICA_PARAMETER__INDEX_NAME);
    }

    /**
     * @deprecated Will be removed without replacement. Since ES v6 only one type per index is allowed and we don't need it anymore.
     *
     * @return string
     */
    public function getSearchDocumentType()
    {
        return $this->get(SearchConstants::ELASTICA_PARAMETER__DOCUMENT_TYPE);
    }

    /**
     * @return array
     */
    public function getElasticsearchConfig()
    {
        $config = $this->get(SearchConstants::ELASTICA_CLIENT_CONFIGURATION, null);
        if ($config !== null) {
            return $config;
        }

        $config = $this->get(SearchConstants::ELASTICA_PARAMETER__EXTRA, null);
        if ($config === null) {
            $config = [];
        }

        $config['transport'] = ucfirst($this->get(SearchConstants::ELASTICA_PARAMETER__TRANSPORT));
        $config['port'] = $this->get(SearchConstants::ELASTICA_PARAMETER__PORT);
        $config['host'] = $this->get(SearchConstants::ELASTICA_PARAMETER__HOST);

        $authHeader = $this->get(SearchConstants::ELASTICA_PARAMETER__AUTH_HEADER, null);
        if ($authHeader !== null) {
            $config['headers'] = [
                'Authorization' => 'Basic ' . $authHeader,
            ];
        }

        return $config;
    }

    /**
     * @return int
     */
    public function getFacetNameAggregationSize()
    {
        return static::FACET_NAME_AGGREGATION_SIZE;
    }
}

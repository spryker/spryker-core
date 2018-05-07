<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\Search\SearchConstants;

class SearchConfig extends AbstractBundleConfig
{
    const FACET_NAME_AGGREGATION_SIZE = 10;

    /**
     * @return string
     */
    public function getSearchIndexName()
    {
        return $this->get(SearchConstants::ELASTICA_PARAMETER__INDEX_NAME);
    }

    /**
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

        $config['protocol'] = ucfirst($this->get(SearchConstants::ELASTICA_PARAMETER__TRANSPORT));
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

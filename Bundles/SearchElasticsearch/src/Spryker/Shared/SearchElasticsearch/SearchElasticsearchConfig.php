<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SearchElasticsearch;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class SearchElasticsearchConfig extends AbstractSharedConfig
{
    /**
     * Available facet types
     */
    public const FACET_TYPE_ENUMERATION = 'enumeration';
    public const FACET_TYPE_RANGE = 'range';
    public const FACET_TYPE_PRICE_RANGE = 'price-range';
    public const FACET_TYPE_CATEGORY = 'category';

    /**
     * @return array
     */
    public function getIndexNameMap(): array
    {
        return $this->get(SearchElasticsearchConstants::INDEX_NAME_MAP, []);
    }

    /**
     * @return array
     */
    public function getClientConfig(): array
    {
        if ($this->getConfig()->hasValue(SearchElasticsearchConstants::CLIENT_CONFIGURATION)) {
            return $this->get(SearchElasticsearchConstants::CLIENT_CONFIGURATION);
        }

        if ($this->getConfig()->hasValue(SearchElasticsearchConstants::EXTRA)) {
            $config = $this->get(SearchElasticsearchConstants::EXTRA);
        }

        $config['transport'] = ucfirst($this->get(SearchElasticsearchConstants::TRANSPORT));
        $config['port'] = $this->get(SearchElasticsearchConstants::PORT);
        $config['host'] = $this->get(SearchElasticsearchConstants::HOST);

        if ($this->getConfig()->hasValue(SearchElasticsearchConstants::AUTH_HEADER)) {
            $config['headers'] = [
                'Authorization' => sprintf('Basic %s', $this->get(SearchElasticsearchConstants::AUTH_HEADER)),
            ];
        }

        return $config;
    }

    /**
     * @return string[]
     */
    public function getSupportedSourceNames(): array
    {
        return [];
    }
}

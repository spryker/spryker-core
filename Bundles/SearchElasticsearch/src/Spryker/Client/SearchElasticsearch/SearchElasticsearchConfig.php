<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\SearchElasticsearch\SearchElasticsearchConstants;

/**
 * @method \Spryker\Shared\SearchElasticsearch\SearchElasticsearchConfig getSharedConfig()
 */
class SearchElasticsearchConfig extends AbstractBundleConfig
{
    public const FACET_NAME_AGGREGATION_SIZE = 10;

    /**
     * @return array
     */
    public function getClientConfig(): array
    {
        return $this->getSharedConfig()->getClientConfig();
    }

    /**
     * @return array
     */
    public function getIndexNameMap(): array
    {
        return $this->getSharedConfig()->getIndexNameMap();
    }

    /**
     * @return int
     */
    public function getFullTextBoostedBoostingValue(): int
    {
        return $this->get(SearchElasticsearchConstants::FULL_TEXT_BOOSTED_BOOSTING_VALUE);
    }

    /**
     * @return int
     */
    public function getFacetNameAggregationSize(): int
    {
        return static::FACET_NAME_AGGREGATION_SIZE;
    }

    /**
     * @return string[]
     */
    public function getSupportedSourceNames(): array
    {
        return $this->getSharedConfig()->getSupportedSourceNames();
    }
}

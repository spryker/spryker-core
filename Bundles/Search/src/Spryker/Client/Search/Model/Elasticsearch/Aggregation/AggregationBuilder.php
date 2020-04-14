<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Aggregation;

use Elastica\Aggregation\Filter;
use Elastica\Aggregation\GlobalAggregation;
use Elastica\Aggregation\Stats;
use Elastica\Aggregation\Terms;
use Elastica\Aggregation\TopHits;

/**
 * @deprecated Use `\Spryker\Client\SearchElasticsearch\Aggregation\AggregationBuilder` instead.
 */
class AggregationBuilder implements AggregationBuilderInterface
{
    /**
     * @param string $name
     *
     * @return \Elastica\Aggregation\GlobalAggregation
     */
    public function createGlobalAggregation($name)
    {
        return new GlobalAggregation($name);
    }

    /**
     * @param string $name
     *
     * @return \Elastica\Aggregation\Filter
     */
    public function createFilterAggregation($name)
    {
        return new Filter($name);
    }

    /**
     * @param string $name
     *
     * @return \Elastica\Aggregation\Terms
     */
    public function createTermsAggregation($name)
    {
        return new Terms($name);
    }

    /**
     * @param string $name
     *
     * @return \Elastica\Aggregation\Stats
     */
    public function createStatsAggregation($name)
    {
        return new Stats($name);
    }

    /**
     * @param string $name
     *
     * @return \Elastica\Aggregation\TopHits
     */
    public function createTopHitsAggregation($name)
    {
        return new TopHits($name);
    }
}

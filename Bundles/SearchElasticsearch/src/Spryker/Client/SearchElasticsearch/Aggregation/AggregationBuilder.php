<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Aggregation;

use Elastica\Aggregation\Filter;
use Elastica\Aggregation\GlobalAggregation;
use Elastica\Aggregation\Stats;
use Elastica\Aggregation\Terms;
use Elastica\Aggregation\TopHits;

class AggregationBuilder implements AggregationBuilderInterface
{
    /**
     * @param string $name
     *
     * @return \Elastica\Aggregation\GlobalAggregation
     */
    public function createGlobalAggregation(string $name): GlobalAggregation
    {
        return new GlobalAggregation($name);
    }

    /**
     * @param string $name
     *
     * @return \Elastica\Aggregation\Filter
     */
    public function createFilterAggregation(string $name): Filter
    {
        return new Filter($name);
    }

    /**
     * @param string $name
     *
     * @return \Elastica\Aggregation\Terms
     */
    public function createTermsAggregation(string $name): Terms
    {
        return new Terms($name);
    }

    /**
     * @param string $name
     *
     * @return \Elastica\Aggregation\Stats
     */
    public function createStatsAggregation(string $name): Stats
    {
        return new Stats($name);
    }

    /**
     * @param string $name
     *
     * @return \Elastica\Aggregation\TopHits
     */
    public function createTopHitsAggregation(string $name): TopHits
    {
        return new TopHits($name);
    }
}

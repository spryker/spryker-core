<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Aggregation;

/**
 * @deprecated Use `\Spryker\Client\SearchElasticsearch\Aggregation` instead.
 */
interface AggregationBuilderInterface
{
    /**
     * @param string $name
     *
     * @return \Elastica\Aggregation\GlobalAggregation
     */
    public function createGlobalAggregation($name);

    /**
     * @param string $name
     *
     * @return \Elastica\Aggregation\Filter
     */
    public function createFilterAggregation($name);

    /**
     * @param string $name
     *
     * @return \Elastica\Aggregation\Terms
     */
    public function createTermsAggregation($name);

    /**
     * @param string $name
     *
     * @return \Elastica\Aggregation\Stats
     */
    public function createStatsAggregation($name);

    /**
     * @param string $name
     *
     * @return \Elastica\Aggregation\TopHits
     */
    public function createTopHitsAggregation($name);
}

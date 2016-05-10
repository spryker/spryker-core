<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\FacetQueryExpanderPlugin;

abstract class AbstractFacetQueryExpanderPluginAggregationTest extends AbstractFacetQueryExpanderPluginTest
{

    /**
     * @dataProvider facetQueryExpanderDataProvider
     *
     * @param \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface $searchConfig
     * @param array $expectedAggregations
     * @param array $params
     *
     * @return void
     */
    public function testFacetQueryExpanderShouldCreateAggregationsBasedOnSearchConfig(SearchConfigInterface $searchConfig, array $expectedAggregations, array $params = [])
    {
        $queryExpander = new FacetQueryExpanderPlugin();
        $query = $queryExpander->expandQuery($this->createQueryMock(), $searchConfig, $params);

        $aggregations = $query->getSearchQuery($params)->getParam('aggs');

        $this->assertEquals($expectedAggregations, $aggregations);
    }

    /**
     * @return array
     */
    abstract public function facetQueryExpanderDataProvider();

}

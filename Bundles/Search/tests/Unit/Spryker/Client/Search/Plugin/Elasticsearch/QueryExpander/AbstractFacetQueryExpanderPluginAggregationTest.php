<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\FacetQueryExpanderPlugin;

/**
 * @group Unit
 * @group Spryker
 * @group Client
 * @group Search
 * @group Plugin
 * @group Elasticsearch
 * @group QueryExpander
 * @group AbstractFacetQueryExpanderPluginAggregationTest
 */
abstract class AbstractFacetQueryExpanderPluginAggregationTest extends AbstractQueryExpanderPluginTest
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
        $searchFactoryMock = $this->createSearchFactoryMockedWithSearchConfig($searchConfig);

        $queryExpander = new FacetQueryExpanderPlugin();
        $queryExpander->setFactory($searchFactoryMock);

        $query = $queryExpander->expandQuery($this->createBaseQueryPlugin(), $params);

        $aggregations = $query->getSearchQuery()->getParam('aggs');

        $this->assertEquals($expectedAggregations, $aggregations);
    }

    /**
     * @return array
     */
    abstract public function facetQueryExpanderDataProvider();

}

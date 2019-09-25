<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query\BoolQuery;
use Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\FacetQueryExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Search
 * @group Plugin
 * @group Elasticsearch
 * @group QueryExpander
 * @group AbstractFacetQueryExpanderPluginQueryTest
 * Add your own group annotations below this line
 */
abstract class AbstractFacetQueryExpanderPluginQueryTest extends AbstractQueryExpanderPluginTest
{
    /**
     * @dataProvider facetQueryExpanderDataProvider
     *
     * @param \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface $searchConfig
     * @param \Elastica\Query\BoolQuery $expectedQuery
     * @param array $params
     *
     * @return void
     */
    public function testFacetQueryExpanderShouldCreateSearchQueryBasedOnSearchConfig(SearchConfigInterface $searchConfig, BoolQuery $expectedQuery, array $params = [])
    {
        $searchFactoryMock = $this->createSearchFactoryMockedWithSearchConfig($searchConfig);

        $queryExpander = new FacetQueryExpanderPlugin();
        $queryExpander->setFactory($searchFactoryMock);

        $query = $queryExpander->expandQuery($this->createBaseQueryPlugin(), $params);

        $query = $query->getSearchQuery()->getQuery();

        $this->assertEquals($expectedQuery, $query);
    }

    /**
     * @return array
     */
    abstract public function facetQueryExpanderDataProvider();
}

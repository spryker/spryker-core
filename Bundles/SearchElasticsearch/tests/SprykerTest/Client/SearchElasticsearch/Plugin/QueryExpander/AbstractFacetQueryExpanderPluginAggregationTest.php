<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchElasticsearch\Plugin\QueryExpander;

use Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface;
use Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\FacetQueryExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchElasticsearch
 * @group Plugin
 * @group QueryExpander
 * @group AbstractFacetQueryExpanderPluginAggregationTest
 * Add your own group annotations below this line
 */
abstract class AbstractFacetQueryExpanderPluginAggregationTest extends AbstractQueryExpanderPluginTest
{
    /**
     * @dataProvider facetQueryExpanderDataProvider
     *
     * @param \Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface $searchConfigMock
     * @param array $expectedAggregations
     * @param array $params
     *
     * @return void
     */
    public function testFacetQueryExpanderShouldCreateAggregationsBasedOnSearchConfig(SearchConfigInterface $searchConfigMock, array $expectedAggregations, array $params = []): void
    {
        // Arrange
        $searchFactoryMock = $this->createSearchFactoryMockedWithSearchConfig($searchConfigMock);

        $queryExpander = new FacetQueryExpanderPlugin();
        $queryExpander->setFactory($searchFactoryMock);

        // Act
        $query = $queryExpander->expandQuery($this->createBaseQueryPlugin(), $params);

        $aggregations = $query->getSearchQuery()->getParam('aggs');

        // Assert
        $this->assertEquals($expectedAggregations, $aggregations);
    }

    /**
     * @return array
     */
    abstract public function facetQueryExpanderDataProvider(): array;
}

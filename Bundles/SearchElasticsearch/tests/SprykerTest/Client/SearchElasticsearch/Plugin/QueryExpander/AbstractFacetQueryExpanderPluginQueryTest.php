<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchElasticsearch\Plugin\QueryExpander;

use Elastica\Query\BoolQuery;
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
 * @group AbstractFacetQueryExpanderPluginQueryTest
 * Add your own group annotations below this line
 */
abstract class AbstractFacetQueryExpanderPluginQueryTest extends AbstractQueryExpanderPluginTest
{
    /**
     * @dataProvider facetQueryExpanderDataProvider
     *
     * @param \Spryker\Client\SearchElasticsearch\Config\SearchConfigInterface $searchConfigMock
     * @param \Elastica\Query\BoolQuery $expectedQuery
     * @param array $params
     *
     * @return void
     */
    public function testFacetQueryExpanderShouldCreateSearchQueryBasedOnSearchConfig(
        SearchConfigInterface $searchConfigMock,
        BoolQuery $expectedQuery,
        array $params = []
    ): void {
        // Arrange
        $searchFactoryMock = $this->createSearchFactoryMockedWithSearchConfig($searchConfigMock);

        $queryExpander = new FacetQueryExpanderPlugin();
        $queryExpander->setFactory($searchFactoryMock);

        // Act
        $query = $queryExpander->expandQuery($this->createBaseQueryPlugin(), $params);

        $query = $query->getSearchQuery()->getQuery();

        // Assert
        $this->assertEquals($expectedQuery, $query);
    }

    /**
     * @return array
     */
    abstract public function facetQueryExpanderDataProvider(): array;
}

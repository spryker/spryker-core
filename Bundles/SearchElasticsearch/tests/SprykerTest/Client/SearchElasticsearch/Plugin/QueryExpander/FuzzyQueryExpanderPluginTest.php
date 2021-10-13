<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchElasticsearch\Plugin\QueryExpander;

use Elastica\Query;
use Elastica\Query\MultiMatch;
use Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\FuzzyQueryExpanderPlugin;
use Spryker\Client\SearchElasticsearch\Query\QueryBuilder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchElasticsearch
 * @group Plugin
 * @group QueryExpander
 * @group FuzzyQueryExpanderPluginTest
 * Add your own group annotations below this line
 */
class FuzzyQueryExpanderPluginTest extends AbstractQueryExpanderPluginTest
{
    /**
     * @return void
     */
    public function testExpandQueryShouldExpandQueryPluginWithFuzziness(): void
    {
        // Arrange
        $queryPlugin = $this->createBaseQueryPlugin();
        $boolQuery = $queryPlugin->getSearchQuery()->getQuery();
        $boolQuery->addMust($this->createMultiMatchQuery());

        $queryExpander = new FuzzyQueryExpanderPlugin();

        // Act
        $query = $queryExpander->expandQuery($queryPlugin);

        // Assert
        $query = $query->getSearchQuery();
        $this->assertEquals($this->createExpectedMultiMatchQuery(), $query);
    }

    /**
     * @return void
     */
    public function testExpandQueryShouldNotExpandQueryPluginWithFuzziness(): void
    {
        // Arrange
        $queryPlugin = $this->createBaseQueryPlugin();
        $matchQuery = (new QueryBuilder())->createMatchQuery();
        $queryPlugin->getSearchQuery()->getQuery()->addMust($matchQuery);
        $queryExpander = new FuzzyQueryExpanderPlugin();

        $expectedQuery = $this->createBaseQuery();
        /** @var \Elastica\Query\BoolQuery $expectedBoolQuery */
        $expectedBoolQuery = $expectedQuery->getQuery();
        $matchQuery = (new QueryBuilder())->createMatchQuery();
        $expectedBoolQuery->addMust($matchQuery);

        // Act
        $query = $queryExpander->expandQuery($queryPlugin);

        // Assert
        $query = $query->getSearchQuery();
        $this->assertEquals($expectedQuery, $query);
    }

    /**
     * @return \Elastica\Query\MultiMatch
     */
    protected function createMultiMatchQuery(): MultiMatch
    {
        return (new MultiMatch())->setFields(['test'])->setQuery('');
    }

    /**
     * @return \Elastica\Query
     */
    protected function createExpectedMultiMatchQuery(): Query
    {
        $query = $this->createBaseQuery();
        /** @var \Elastica\Query\BoolQuery $boolQuery */
        $boolQuery = $query->getQuery();

        $multiMatchQuery = $this->createMultiMatchQuery()
            ->setFuzziness(FuzzyQueryExpanderPlugin::FUZZINESS_AUTO);
        $boolQuery->addMust($multiMatchQuery);

        return $query;
    }
}

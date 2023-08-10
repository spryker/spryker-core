<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointSearch\Plugin\Elasticsearch\Query;

use Codeception\Test\Unit;
use Elastica\Query\MultiMatch;
use Generated\Shared\Search\ServicePointIndexMap;
use Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\ServicePointSearchQueryPlugin;
use SprykerTest\Client\ServicePointSearch\ServicePointSearchClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePointSearch
 * @group Plugin
 * @group Elasticsearch
 * @group Query
 * @group ServicePointSearchQueryPluginTest
 * Add your own group annotations below this line
 */
class ServicePointSearchQueryPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ServicePointSearch\ServicePointSearchClientTester $tester
     */
    protected ServicePointSearchClientTester $tester;

    /**
     * @var int
     */
    protected const FULL_TEXT_BOOSTED_VALUE = 3.0;

    /**
     * @var string
     */
    protected const SEARCH_STRING = 'searchString';

    /**
     * @return void
     */
    public function testGetSearchQueryShouldReturnQueryWithFullTextQueryUsingWildcardsAndMultiMatch(): void
    {
        // Arrange
        $servicePointSearchQueryPlugin = new ServicePointSearchQueryPlugin();
        $servicePointSearchQueryPlugin->setSearchString(static::SEARCH_STRING);
        $this->tester->mockConfigMethod('getElasticsearchFullTextBoostedBoostingValue', static::FULL_TEXT_BOOSTED_VALUE);

        // Act
        $searchQuery = $servicePointSearchQueryPlugin->getSearchQuery()->toArray();

        // Assert
        $this->assertTerm($searchQuery);
        $this->assertFullTextWildcard($searchQuery);
        $this->assertFullTextBoostedWildcard($searchQuery);
        $this->assertFullTextMultiMatch($searchQuery);
        $this->assertSame(static::SEARCH_STRING, $searchQuery['suggest']['text']);
        $this->assertSame('search-result-data', $searchQuery['_source']);
    }

    /**
     * @return void
     */
    public function testGetSearchQueryShouldReturnQueryWithoutFullTextQuery(): void
    {
        // Arrange
        $servicePointSearchQueryPlugin = new ServicePointSearchQueryPlugin();

        // Act
        $searchQuery = $servicePointSearchQueryPlugin->getSearchQuery()->toArray();

        // Assert
        $this->assertTerm($searchQuery);
        $this->assertSame('', $searchQuery['suggest']['text']);
        $this->assertSame('search-result-data', $searchQuery['_source']);
    }

    /**
     * @param array $searchQuery
     *
     * @return void
     */
    protected function assertFullTextWildcard(array $searchQuery): void
    {
        $wildcard = $searchQuery['query']['bool']['must'][1]['bool']['should'][0]['wildcard']['full-text'];

        $this->assertSame(sprintf('*%s*', static::SEARCH_STRING), $wildcard['value']);
        $this->assertSame(1.0, (float)$wildcard['boost']);
    }

    /**
     * @param array $searchQuery
     *
     * @return void
     */
    protected function assertFullTextBoostedWildcard(array $searchQuery): void
    {
        $wildcard = $searchQuery['query']['bool']['must'][1]['bool']['should'][1]['wildcard']['full-text-boosted'];

        $this->assertSame(sprintf('*%s*', static::SEARCH_STRING), $wildcard['value']);
        $this->assertSame(static::FULL_TEXT_BOOSTED_VALUE, (float)$wildcard['boost']);
    }

    /**
     * @param array $searchQuery
     *
     * @return void
     */
    protected function assertFullTextMultiMatch(array $searchQuery): void
    {
        $multiMatch = $searchQuery['query']['bool']['must'][1]['bool']['should'][2]['multi_match'];

        $this->assertSame(static::SEARCH_STRING, $multiMatch['query']);
        $this->assertSame(MultiMatch::TYPE_PHRASE_PREFIX, $multiMatch['type']);

        $boostedField = sprintf('%s^%d', ServicePointIndexMap::FULL_TEXT_BOOSTED, static::FULL_TEXT_BOOSTED_VALUE);
        $this->assertSame([ServicePointIndexMap::FULL_TEXT, $boostedField], $multiMatch['fields']);
    }

    /**
     * @param array $searchQuery
     *
     * @return void
     */
    protected function assertTerm(array $searchQuery): void
    {
        $term = $searchQuery['query']['bool']['must'][0]['term']['type'];

        $this->assertSame('service_point', $term['value']);
        $this->assertSame(1.0, (float)$term['boost']);
    }
}

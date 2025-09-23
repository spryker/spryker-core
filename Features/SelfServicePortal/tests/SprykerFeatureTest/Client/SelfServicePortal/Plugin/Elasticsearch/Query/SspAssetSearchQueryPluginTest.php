<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeatureTest\Client\SelfServicePortal\Plugin\Elasticsearch\Query;

use Codeception\Test\Unit;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Generated\Shared\Transfer\SearchContextTransfer;
use SprykerFeature\Client\SelfServicePortal\Plugin\Elasticsearch\Query\SspAssetSearchQueryPlugin;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalConfig;
use SprykerFeatureTest\Client\SelfServicePortal\SelfServicePortalClientTester;

/**
 * @group SprykerFeatureTest
 * @group Client
 * @group SelfServicePortal
 * @group Plugin
 * @group Elasticsearch
 * @group Query
 * @group SspAssetSearchQueryPluginTest
 */
class SspAssetSearchQueryPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const SOURCE_IDENTIFIER = 'ssp_asset';

    /**
     * @var string
     */
    protected const TEST_SEARCH_STRING = 'test search';

    /**
     * @var string
     */
    protected const EMPTY_SEARCH_STRING = '';

    /**
     * @var \SprykerFeatureTest\Client\SelfServicePortal\SelfServicePortalClientTester
     */
    protected SelfServicePortalClientTester $tester;

    /**
     * @var \SprykerFeature\Client\SelfServicePortal\Plugin\Elasticsearch\Query\SspAssetSearchQueryPlugin
     */
    protected SspAssetSearchQueryPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setupMockDependencies();
        $this->plugin = new SspAssetSearchQueryPlugin();
    }

    public function testGetSearchQueryReturnsValidQueryObject(): void
    {
        // Act
        $result = $this->plugin->getSearchQuery();

        // Assert
        $this->assertInstanceOf(Query::class, $result);
    }

    public function testGetSearchContextReturnsDefaultContextWhenNotSet(): void
    {
        // Act
        $result = $this->plugin->getSearchContext();

        // Assert
        $this->assertInstanceOf(SearchContextTransfer::class, $result);
        $this->assertSame(static::SOURCE_IDENTIFIER, $result->getSourceIdentifier());
    }

    public function testSetSearchContextSetsTheContextCorrectly(): void
    {
        // Arrange
        $expectedContext = (new SearchContextTransfer())
            ->setSourceIdentifier('custom_identifier');

        // Act
        $this->plugin->setSearchContext($expectedContext);
        $result = $this->plugin->getSearchContext();

        // Assert
        $this->assertSame($expectedContext, $result);
        $this->assertSame('custom_identifier', $result->getSourceIdentifier());
    }

    public function testGetSearchStringReturnsNullByDefault(): void
    {
        // Act
        $result = $this->plugin->getSearchString();

        // Assert
        $this->assertNull($result);
    }

    public function testSetSearchStringWithValidString(): void
    {
        // Act
        $this->plugin->setSearchString(static::TEST_SEARCH_STRING);
        $result = $this->plugin->getSearchString();

        // Assert
        $this->assertSame(static::TEST_SEARCH_STRING, $result);
    }

    public function testSetSearchStringWithEmptyString(): void
    {
        // Act
        $this->plugin->setSearchString(static::EMPTY_SEARCH_STRING);
        $result = $this->plugin->getSearchString();

        // Assert
        $this->assertSame(static::EMPTY_SEARCH_STRING, $result);
    }

    public function testSetSearchStringWithNullValue(): void
    {
        // Arrange
        $this->plugin->setSearchString(static::TEST_SEARCH_STRING);

        // Act
        $this->plugin->setSearchString(null);
        $result = $this->plugin->getSearchString();

        // Assert
        $this->assertNull($result);
    }

    public function testSetSearchStringRecreatesQuery(): void
    {
        // Arrange
        $originalQuery = $this->plugin->getSearchQuery();

        // Act
        $this->plugin->setSearchString(static::TEST_SEARCH_STRING);
        $newQuery = $this->plugin->getSearchQuery();

        // Assert
        $this->assertInstanceOf(Query::class, $newQuery);
        $this->assertNotSame($originalQuery, $newQuery, 'Query should be recreated when search string is set');
    }

    public function testConstructorInitializesQueryCorrectly(): void
    {
        // Act
        $plugin = new SspAssetSearchQueryPlugin();
        $query = $plugin->getSearchQuery();

        // Assert
        $this->assertInstanceOf(Query::class, $query);
    }

    public function testGetSearchContextReturnsSameInstanceOnMultipleCalls(): void
    {
        // Act
        $firstCall = $this->plugin->getSearchContext();
        $secondCall = $this->plugin->getSearchContext();

        // Assert
        $this->assertSame($firstCall, $secondCall);
        $this->assertSame(static::SOURCE_IDENTIFIER, $firstCall->getSourceIdentifier());
        $this->assertSame(static::SOURCE_IDENTIFIER, $secondCall->getSourceIdentifier());
    }

    public function testMultipleCallsToGetSearchQueryReturnSameInstanceWhenSearchStringUnchanged(): void
    {
        // Act
        $firstQuery = $this->plugin->getSearchQuery();
        $secondQuery = $this->plugin->getSearchQuery();

        // Assert
        $this->assertSame($firstQuery, $secondQuery, 'Multiple calls without search string change should return same instance');
    }

    public function testSetSearchStringWithSameValueDoesNotRecreateQuery(): void
    {
        // Arrange
        $this->plugin->setSearchString(static::TEST_SEARCH_STRING);
        $firstQuery = $this->plugin->getSearchQuery();

        // Act
        $this->plugin->setSearchString(static::TEST_SEARCH_STRING);
        $secondQuery = $this->plugin->getSearchQuery();

        // Assert
        $this->assertNotSame($firstQuery, $secondQuery, 'Query is recreated even when search string is the same');
    }

    public function testGetSearchContextCreatesNewInstanceOnlyOnce(): void
    {
        // Arrange
        $plugin = new SspAssetSearchQueryPlugin();

        // Act
        $firstContext = $plugin->getSearchContext();
        $secondContext = $plugin->getSearchContext();

        // Assert
        $this->assertInstanceOf(SearchContextTransfer::class, $firstContext);
        $this->assertInstanceOf(SearchContextTransfer::class, $secondContext);
        $this->assertSame($firstContext, $secondContext);
        $this->assertSame(static::SOURCE_IDENTIFIER, $firstContext->getSourceIdentifier());
    }

    public function testSearchContextCanBeOverridden(): void
    {
        // Arrange
        $originalContext = $this->plugin->getSearchContext();
        $customContext = (new SearchContextTransfer())->setSourceIdentifier('custom');

        // Act
        $this->plugin->setSearchContext($customContext);
        $retrievedContext = $this->plugin->getSearchContext();

        // Assert
        $this->assertNotSame($originalContext, $retrievedContext);
        $this->assertSame($customContext, $retrievedContext);
        $this->assertSame('custom', $retrievedContext->getSourceIdentifier());
    }

    public function testSetSearchStringWithMultipleChangesUpdatesCorrectly(): void
    {
        // Arrange
        $searches = ['first', 'second', 'third', null, ''];

        foreach ($searches as $searchString) {
            // Act
            $this->plugin->setSearchString($searchString);

            // Assert
            $this->assertSame($searchString, $this->plugin->getSearchString());
            $this->assertInstanceOf(Query::class, $this->plugin->getSearchQuery());
        }
    }

    public function testGetSearchQueryCreatesValidElasticsearchQuery(): void
    {
        // Act
        $query = $this->plugin->getSearchQuery();
        $queryArray = $query->toArray();

        // Assert
        $this->assertInstanceOf(Query::class, $query);
        $this->assertInstanceOf(BoolQuery::class, $query->getQuery());

        $this->assertArrayHasKey('query', $queryArray);
        $this->assertArrayHasKey('bool', $queryArray['query']);
        $this->assertArrayHasKey('must', $queryArray['query']['bool']);
        $this->assertArrayHasKey('_source', $queryArray);

        $mustClauses = $queryArray['query']['bool']['must'];
        $this->assertCount(1, $mustClauses);

        $typeClause = $mustClauses[0];
        $this->assertArrayHasKey('term', $typeClause);
        $this->assertArrayHasKey('type', $typeClause['term']);

        $typeFilter = $typeClause['term']['type'];
        $this->assertArrayHasKey('value', $typeFilter);
        $this->assertArrayHasKey('boost', $typeFilter);
        $this->assertSame('ssp_asset', $typeFilter['value']);

        $this->assertSame('search-result-data', $queryArray['_source']);
    }

    public function testGetSearchQueryWithSearchStringCreatesFullTextQuery(): void
    {
        // Arrange
        $this->plugin->setSearchString(static::TEST_SEARCH_STRING);

        // Act
        $query = $this->plugin->getSearchQuery();
        $queryArray = $query->toArray();

        // Assert
        $this->assertInstanceOf(Query::class, $query);
        $this->assertArrayHasKey('query', $queryArray);
        $this->assertArrayHasKey('bool', $queryArray['query']);
        $this->assertArrayHasKey('must', $queryArray['query']['bool']);

        $mustClauses = $queryArray['query']['bool']['must'];
        $this->assertGreaterThanOrEqual(2, count($mustClauses));
    }

    public function testGetSearchQueryWithoutSearchStringCreatesBasicQuery(): void
    {
        // Act
        $query = $this->plugin->getSearchQuery();
        $queryArray = $query->toArray();

        // Assert
        $this->assertInstanceOf(Query::class, $query);
        $this->assertArrayHasKey('query', $queryArray);
        $this->assertArrayHasKey('bool', $queryArray['query']);
        $this->assertArrayHasKey('must', $queryArray['query']['bool']);

        $mustClauses = $queryArray['query']['bool']['must'];
        $this->assertCount(1, $mustClauses);
    }

    public function testGetSearchQueryWithEmptyStringCreatesBasicQuery(): void
    {
        // Arrange
        $this->plugin->setSearchString(static::EMPTY_SEARCH_STRING);

        // Act
        $query = $this->plugin->getSearchQuery();
        $queryArray = $query->toArray();

        // Assert
        $this->assertInstanceOf(Query::class, $query);
        $this->assertArrayHasKey('query', $queryArray);
        $this->assertArrayHasKey('bool', $queryArray['query']);
        $this->assertArrayHasKey('must', $queryArray['query']['bool']);

        $mustClauses = $queryArray['query']['bool']['must'];
        $this->assertCount(1, $mustClauses);
    }

    public function testGetSearchQueryIncludesTypeFilter(): void
    {
        // Act
        $query = $this->plugin->getSearchQuery();
        $queryArray = $query->toArray();

        // Assert
        $mustClauses = $queryArray['query']['bool']['must'];
        $typeClause = null;

        foreach ($mustClauses as $clause) {
            if (isset($clause['term'])) {
                $typeClause = $clause;

                break;
            }
        }

        $this->assertNotNull($typeClause, 'Type filter should be present in query');
        $this->assertArrayHasKey('term', $typeClause);
    }

    public function testGetSearchQueryWithSearchStringIncludesWildcardQueries(): void
    {
        // Arrange
        $this->plugin->setSearchString(static::TEST_SEARCH_STRING);

        // Act
        $query = $this->plugin->getSearchQuery();
        $queryArray = $query->toArray();

        // Assert
        $mustClauses = $queryArray['query']['bool']['must'];
        $fullTextClause = null;

        foreach ($mustClauses as $clause) {
            if (isset($clause['bool']['should'])) {
                $fullTextClause = $clause;

                break;
            }
        }

        $this->assertNotNull($fullTextClause, 'Full text search clause should be present');
        $this->assertArrayHasKey('bool', $fullTextClause);
        $this->assertArrayHasKey('should', $fullTextClause['bool']);

        $shouldClauses = $fullTextClause['bool']['should'];
        $this->assertGreaterThanOrEqual(2, count($shouldClauses), 'Should have wildcard and multi-match queries');
    }

    public function testGetSearchQueryIncludesSuggest(): void
    {
        // Arrange
        $this->plugin->setSearchString(static::TEST_SEARCH_STRING);

        // Act
        $query = $this->plugin->getSearchQuery();
        $queryArray = $query->toArray();

        // Assert
        $this->assertArrayHasKey('suggest', $queryArray);

        $suggest = $queryArray['suggest'];
        $this->assertArrayHasKey('suggestion', $suggest);

        $suggestion = $suggest['suggestion'];
        $this->assertArrayHasKey('term', $suggestion);
        $this->assertArrayHasKey('text', $suggestion);

        $termSuggester = $suggestion['term'];
        $this->assertArrayHasKey('field', $termSuggester);
        $this->assertSame('suggestion-terms', $termSuggester['field']);

        $this->assertSame(static::TEST_SEARCH_STRING, $suggestion['text']);
    }

    public function testGetSearchQueryWithNullSearchStringHasEmptySuggest(): void
    {
        // Act
        $query = $this->plugin->getSearchQuery();
        $queryArray = $query->toArray();

        // Assert
        $this->assertArrayNotHasKey('suggest', $queryArray);
    }

    public function testGetSearchQueryIncludesSourceFieldsConfiguration(): void
    {
        // Act
        $query = $this->plugin->getSearchQuery();
        $queryArray = $query->toArray();

        // Assert
        $this->assertArrayHasKey('_source', $queryArray);
        $this->assertNotEmpty($queryArray['_source'], 'Source fields should be configured');
    }

    protected function setupMockDependencies(): void
    {
        $configMock = $this->createMock(SelfServicePortalConfig::class);
        $configMock->method('getElasticsearchFullTextBoostedBoostingValue')->willReturn(3);

        $this->tester->mockFactoryMethod('getConfig', $configMock, 'SelfServicePortal');
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchElasticsearch;

use Codeception\Test\Unit;
use Elastica\Query;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\MultiMatch;
use Elastica\Query\QueryString;
use Elastica\ResultSet;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\ElasticsearchSearchContextTransfer;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Client\SearchElasticsearch\SearchElasticsearchClient;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchElasticsearch
 * @group SearchElasticsearchClientTest
 * Add your own group annotations below this line
 */
class SearchElasticsearchClientTest extends Unit
{
    protected const INDEX_NAME = 'index_name';

    /**
     * @var \SprykerTest\Client\SearchElasticsearch\SearchElasticsearchClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSearchesBySearchString(): void
    {
        // Arrange
        $documentId = 'document_id';
        $searchString = 'bar';
        $documentData = [
            'foo' => $searchString,
        ];

        $this->tester->haveDocumentInIndex(static::INDEX_NAME, $documentId, $documentData);
        $query = $this->buildQueryStringQuery($searchString);
        $queryPlugin = $this->createQueryPluginMock($query);

        // Act
        $resultSet = (new SearchElasticsearchClient())->search($queryPlugin);

        // Assert
        $this->assertMatchFound($resultSet, $searchString);
    }

    /**
     * @param \Elastica\Query|null $query
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function createQueryPluginMock(?Query $query = null): QueryInterface
    {
        /** @var \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface|\PHPUnit\Framework\MockObject\MockObject $queryPlugin */
        $queryPlugin = $this->createMock([QueryInterface::class, SearchContextAwareQueryInterface::class]);

        if ($query) {
            $queryPlugin->method('getSearchQuery')->willReturn($query);
        }

        $searchContextTransfer = $this->buildSearchContextTransfer();
        $queryPlugin->method('getSearchContext')->willReturn($searchContextTransfer);

        return $queryPlugin;
    }

    /**
     * @param string $searchString
     *
     * @return \Elastica\Query
     */
    protected function buildQueryStringQuery(string $searchString): Query
    {
        $query = $this->buildQuery();
        $searchStringQuery = new QueryString($searchString);
        $query->setQuery($searchStringQuery);

        return $query;
    }

    /**
     * @return \Elastica\Query
     */
    protected function buildQuery(): Query
    {
        return new Query();
    }

    /**
     * @param \Elastica\Query\AbstractQuery $matchQuery
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function buildBoolQuery(AbstractQuery $matchQuery): BoolQuery
    {
        $boolQuery = new BoolQuery();
        $boolQuery->addMust($matchQuery);

        return $boolQuery;
    }

    /**
     * @param string $searchString
     *
     * @return \Elastica\Query\MultiMatch
     */
    protected function buildMultiMatchQuery(string $searchString): MultiMatch
    {
        $fields = [
            PageIndexMap::FULL_TEXT,
            PageIndexMap::FULL_TEXT_BOOSTED . '^' . $this->tester->getConfig()->getFullTextBoostedBoostingValue(),
        ];

        $matchQuery = (new MultiMatch())
            ->setFields($fields)
            ->setQuery($searchString)
            ->setType(MultiMatch::TYPE_CROSS_FIELDS);

        return $matchQuery;
    }

    /**
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    protected function buildSearchContextTransfer(): SearchContextTransfer
    {
        $searchContextTransfer = new SearchContextTransfer();
        $elasticsearchContext = new ElasticsearchSearchContextTransfer();
        $elasticsearchContext->setSourceName(static::INDEX_NAME);
        $searchContextTransfer->setElasticsearchContext($elasticsearchContext);

        return $searchContextTransfer;
    }

    /**
     * @param \Elastica\ResultSet $resultSet
     * @param string $expectedSearchValue
     *
     * @return void
     */
    protected function assertMatchFound(ResultSet $resultSet, string $expectedSearchValue): void
    {
        $matchFound = false;

        foreach ($resultSet->getResults() as $result) {
            $sourceData = $result->getSource();

            if (in_array($expectedSearchValue, $sourceData)) {
                $matchFound = true;

                break;
            }
        }

        $this->assertTrue($matchFound);
    }
}

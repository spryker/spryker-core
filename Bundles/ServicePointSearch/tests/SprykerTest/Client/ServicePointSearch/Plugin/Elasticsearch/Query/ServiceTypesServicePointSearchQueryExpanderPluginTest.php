<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointSearch\Plugin\Elasticsearch\Query;

use Codeception\Test\Unit;
use Elastica\Query;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchQuery;
use Generated\Shared\Search\ServicePointIndexMap;
use InvalidArgumentException;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\ServiceTypesServicePointSearchQueryExpanderPlugin;
use TypeError;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePointSearch
 * @group Plugin
 * @group Elasticsearch
 * @group Query
 * @group ServiceTypesServicePointSearchQueryExpanderPluginTest
 * Add your own group annotations below this line
 */
class ServiceTypesServicePointSearchQueryExpanderPluginTest extends Unit
{
    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\ServiceTypesServicePointSearchQueryExpanderPlugin::PARAMETER_SERVICE_TYPES
     *
     * @var string
     */
    protected const PARAMETER_SERVICE_TYPES = 'serviceTypes';

    /**
     * @var string
     */
    protected const QUERY_PARAM_FILTER = 'filter';

    /**
     * @var string
     */
    protected const TEST_SERVICE_TYPE = 'TEST_SERVICE_TYPE';

    /**
     * @var string
     */
    protected const KEY_TERMS = 'terms';

    /**
     * @return void
     */
    public function testExpandQueryShouldNotAddFilterWhenServiceTypesParameterIsNotProvided(): void
    {
        // Arrange
        $query = $this->createQueryMock($this->createBoolQuery());

        // Act
        $expandedQuery = $this->createServiceTypesServicePointSearchQueryExpanderPlugin()->expandQuery($query);

        // Assert
        /** @var \Elastica\Query\BoolQuery $resultBoolQuery */
        $resultBoolQuery = $expandedQuery->getSearchQuery()->getQuery();

        $this->assertFalse($resultBoolQuery->hasParam(static::QUERY_PARAM_FILTER));
    }

    /**
     * @return void
     */
    public function testExpandQueryShouldNotAddFilterWhenEmptyServiceTypesParameterIsProvided(): void
    {
        // Arrange
        $query = $this->createQueryMock($this->createBoolQuery());

        // Act
        $expandedQuery = $this->createServiceTypesServicePointSearchQueryExpanderPlugin()
            ->expandQuery($query, [static::PARAMETER_SERVICE_TYPES => []]);

        // Assert
        /** @var \Elastica\Query\BoolQuery $resultBoolQuery */
        $resultBoolQuery = $expandedQuery->getSearchQuery()->getQuery();

        $this->assertFalse($resultBoolQuery->hasParam(static::QUERY_PARAM_FILTER));
    }

    /**
     * @return void
     */
    public function testExpandQueryShouldThrowAnExceptionWhenProvidedServiceTypesParameterIsNotArray(): void
    {
        // Assert
        $this->expectException(TypeError::class);

        // Arrange
        $query = $this->createQueryMock($this->createBoolQuery());

        // Act
        $this->createServiceTypesServicePointSearchQueryExpanderPlugin()
            ->expandQuery($query, [static::PARAMETER_SERVICE_TYPES => static::PARAMETER_SERVICE_TYPES]);
    }

    /**
     * @return void
     */
    public function testExpandQueryShouldThrowAnExceptionWhenSearchQueryDoesNotContainBoolQuery(): void
    {
        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Arrange
        $query = $this->createQueryMock($this->createMatchQuery());

        // Act
        $this->createServiceTypesServicePointSearchQueryExpanderPlugin()
            ->expandQuery($query, [static::PARAMETER_SERVICE_TYPES => [static::PARAMETER_SERVICE_TYPES]]);
    }

    /**
     * @return void
     */
    public function testExpandQueryShouldAddFilterWhenServiceTypesParameterIsProvided(): void
    {
        // Arrange
        $query = $this->createQueryMock($this->createBoolQuery());

        // Act
        $expandedQuery = $this->createServiceTypesServicePointSearchQueryExpanderPlugin()
            ->expandQuery($query, [static::PARAMETER_SERVICE_TYPES => [static::TEST_SERVICE_TYPE]]);

        // Assert
        /** @var \Elastica\Query\BoolQuery $resultBoolQuery */
        $resultBoolQuery = $expandedQuery->getSearchQuery()->getQuery();

        $this->assertTrue($resultBoolQuery->hasParam(static::QUERY_PARAM_FILTER));

        /** @var \Elastica\Query\Terms $terms */
        $terms = $resultBoolQuery->getParam(static::QUERY_PARAM_FILTER)[0];
        $termsData = $terms->toArray();

        $this->assertArrayHasKey(ServicePointIndexMap::SERVICE_TYPES, $termsData[static::KEY_TERMS]);
        $this->assertSame([static::TEST_SERVICE_TYPE], $termsData[static::KEY_TERMS][ServicePointIndexMap::SERVICE_TYPES]);
    }

    /**
     * @param \Elastica\Query\AbstractQuery $abstractQuery
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createQueryMock(AbstractQuery $abstractQuery): QueryInterface
    {
        $queryMock = $this->getMockBuilder(QueryInterface::class)->getMock();

        $queryMock->method('getSearchQuery')->willReturn(new Query($abstractQuery));

        return $queryMock;
    }

    /**
     * @return \Elastica\Query\BoolQuery
     */
    protected function createBoolQuery(): BoolQuery
    {
        return new BoolQuery();
    }

    /**
     * @return \Elastica\Query\MatchQuery
     */
    protected function createMatchQuery(): MatchQuery
    {
        return new MatchQuery();
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface
     */
    protected function createServiceTypesServicePointSearchQueryExpanderPlugin(): QueryExpanderPluginInterface
    {
        return new ServiceTypesServicePointSearchQueryExpanderPlugin();
    }
}

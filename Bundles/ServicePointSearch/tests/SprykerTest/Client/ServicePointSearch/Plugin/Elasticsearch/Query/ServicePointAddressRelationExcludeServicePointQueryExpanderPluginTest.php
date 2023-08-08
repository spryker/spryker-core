<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointSearch\Plugin\Elasticsearch\Query;

use Codeception\Test\Unit;
use Generated\Shared\Search\ServicePointIndexMap;
use Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\ServicePointAddressRelationExcludeServicePointQueryExpanderPlugin;
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
 * @group ServicePointAddressRelationExcludeServicePointQueryExpanderPluginTest
 * Add your own group annotations below this line
 */
class ServicePointAddressRelationExcludeServicePointQueryExpanderPluginTest extends Unit
{
    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\ServicePointAddressRelationExcludeServicePointQueryExpanderPlugin::PARAMETER_EXCLUDE_ADDRESS_RELATION
     *
     * @var string
     */
    protected const PARAMETER_EXCLUDE_ADDRESS_RELATION = 'excludeAddressRelation';

    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\ServicePointAddressRelationExcludeServicePointQueryExpanderPlugin::QUERY_PARAM_SOURCE
     *
     * @var string
     */
    protected const QUERY_PARAM_SOURCE = '_source';

    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\ServicePointAddressRelationExcludeServicePointQueryExpanderPlugin::KEY_INCLUDES
     *
     * @var string
     */
    protected const KEY_INCLUDES = 'includes';

    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\ServicePointAddressRelationExcludeServicePointQueryExpanderPlugin::KEY_EXCLUDES
     *
     * @var string
     */
    protected const KEY_EXCLUDES = 'excludes';

    /**
     * @var string
     */
    protected const KEY_EXCLUDE_ADDRESSES = 'search-result-data.address';

    /**
     * @var string
     */
    protected const KEY_EXCLUDE_NAME = 'search-result-data.name';

    /**
     * @var \SprykerTest\Client\ServicePointSearch\ServicePointSearchClientTester
     */
    protected ServicePointSearchClientTester $tester;

    /**
     * @dataProvider getExpandQueryDataProvider
     *
     * @param array $querySource
     * @param array<string, bool> $requestParameters
     * @param array $expectedQuerySource
     *
     * @return void
     */
    public function testExpandQuery(
        array $querySource,
        array $requestParameters,
        array $expectedQuerySource
    ): void {
        // Arrange
        $query = $this->tester->createQueryMock();

        /** @var \Elastica\Query $searchQuery */
        $searchQuery = $query->getSearchQuery();
        $searchQuery->setSource($querySource);

        // Act
        $expandedQuery = (new ServicePointAddressRelationExcludeServicePointQueryExpanderPlugin())
            ->expandQuery($query, $requestParameters);

        // Assert
        /** @var \Elastica\Query $resultSearchQuery */
        $resultSearchQuery = $expandedQuery->getSearchQuery();

        $this->assertSame($expectedQuerySource, $resultSearchQuery->getParam(static::QUERY_PARAM_SOURCE));
    }

    /**
     * @return array<string, mixed>
     */
    protected function getExpandQueryDataProvider(): array
    {
        return [
            'Should not exclude service point address relation when `excludeAddressRelation` parameter is `false`.' => [
                [ServicePointIndexMap::SEARCH_RESULT_DATA],
                [static::PARAMETER_EXCLUDE_ADDRESS_RELATION => false],
                [ServicePointIndexMap::SEARCH_RESULT_DATA],
            ],
            'Should not exclude service point address when `excludeAddressRelation` parameter is not provided.' => [
                [ServicePointIndexMap::SEARCH_RESULT_DATA],
                [],
                [ServicePointIndexMap::SEARCH_RESULT_DATA],
            ],
            'Should exclude service point address when `excludeAddressRelation` parameter is `true`.' => [
                [ServicePointIndexMap::SEARCH_RESULT_DATA],
                [static::PARAMETER_EXCLUDE_ADDRESS_RELATION => true],
                [
                    static::KEY_INCLUDES => ServicePointIndexMap::SEARCH_RESULT_DATA,
                    static::KEY_EXCLUDES => [static::KEY_EXCLUDE_ADDRESSES],
                ],
            ],
            'Should expand already existing exclude with service point address relation exclude when `excludeAddressRelation` parameter is `true`.' => [
                [
                    static::KEY_INCLUDES => ServicePointIndexMap::SEARCH_RESULT_DATA,
                    static::KEY_EXCLUDES => [static::KEY_EXCLUDE_NAME],
                ],
                [static::PARAMETER_EXCLUDE_ADDRESS_RELATION => true],
                [
                    static::KEY_INCLUDES => ServicePointIndexMap::SEARCH_RESULT_DATA,
                    static::KEY_EXCLUDES => [static::KEY_EXCLUDE_NAME, static::KEY_EXCLUDE_ADDRESSES],
                ],
            ],
        ];
    }
}

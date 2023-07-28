<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointSearch\Plugin\Elasticsearch\Query;

use Codeception\Test\Unit;
use Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\ServicePointSearchQueryPlugin;

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
     * @return void
     */
    public function testGetSearchQueryShouldReturnQueryWithFullTextQueryUsingWildcards(): void
    {
        // Arrange
        $servicePointSearchQueryPlugin = new ServicePointSearchQueryPlugin();

        $servicePointSearchQueryPlugin->setSearchString('searchString');

        // Act
        $searchQuery = $servicePointSearchQueryPlugin->getSearchQuery()->toArray();

        // Assert
        $this->assertTerm($searchQuery);
        $this->assertFullTextWildcard($searchQuery);
        $this->assertFullTextBoostedWildcard($searchQuery);
        $this->assertSame('searchString', $searchQuery['suggest']['text']);
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

        $this->assertSame('*searchString*', $wildcard['value']);
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

        $this->assertSame('*searchString*', $wildcard['value']);
        $this->assertSame(3.0, (float)$wildcard['boost']);
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

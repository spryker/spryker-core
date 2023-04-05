<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchHttp\Plugin\Catalog\QueryExpander;

use Codeception\Test\Unit;
use Spryker\Client\SearchHttp\Plugin\Catalog\QueryExpander\FacetSearchHttpQueryExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchHttp
 * @group Plugin
 * @group Catalog
 * @group QueryExpander
 * @group FacetSearchHttpQueryExpanderPluginTest
 * Add your own group annotations below this line
 */
class FacetSearchHttpQueryExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Client\SearchHttp\SearchHttpClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSearchHttpQueryExpandedWithFacets(): void
    {
        // Arrange
        $this->tester->mockSearchConfig();
        $this->tester->mockLocaleClientDependency();
        $this->tester->mockStoreClientDependency();
        $this->tester->mockCategoryStorageClientDependency();
        $this->tester->mockMoneyClientDependency();
        $searchHttpQueryPlugin = $this->tester->getSearchHttpQueryPlugin();
        $facetSearchHttpQueryExpanderPlugin = new FacetSearchHttpQueryExpanderPlugin();
        $facetSearchHttpQueryExpanderPlugin->setFactory($this->tester->getFactory());

        $requestData = [
            'range' => [
                'min' => 10,
                'max' => 100,
            ],
            'price' => [
                'min' => 10,
                'max' => 100,
            ],
            'category' => 6,
            'custom-configured-range' => [
                'min' => 1,
                'max' => 5,
            ],
            'custom-configured-values' => [1, 2, 3],
        ];

        // Act
        $expandedSearchHttpQueryPlugin = $facetSearchHttpQueryExpanderPlugin->expandQuery($searchHttpQueryPlugin, $requestData);

        // Assert
        $this->assertEquals(
            5,
            count($expandedSearchHttpQueryPlugin->getSearchQuery()->getSearchQueryFacetFilters()),
        );
        $this->assertSame(
            'range',
            $expandedSearchHttpQueryPlugin->getSearchQuery()->getSearchQueryFacetFilters()[0]->getFieldName(),
        );
        $this->assertSame(
            10,
            $expandedSearchHttpQueryPlugin->getSearchQuery()->getSearchQueryFacetFilters()[0]->getFrom(),
        );
        $this->assertSame(
            100,
            $expandedSearchHttpQueryPlugin->getSearchQuery()->getSearchQueryFacetFilters()[0]->getTo(),
        );
        $this->assertSame(
            'price',
            $expandedSearchHttpQueryPlugin->getSearchQuery()->getSearchQueryFacetFilters()[1]->getFieldName(),
        );
        $this->assertSame(
            1000,
            (int)$expandedSearchHttpQueryPlugin->getSearchQuery()->getSearchQueryFacetFilters()[1]->getFrom(),
        );
        $this->assertSame(
            10000,
            (int)$expandedSearchHttpQueryPlugin->getSearchQuery()->getSearchQueryFacetFilters()[1]->getTo(),
        );
        $this->assertSame(
            'category',
            $expandedSearchHttpQueryPlugin->getSearchQuery()->getSearchQueryFacetFilters()[2]->getFieldName(),
        );
        $this->assertSame(
            [0 => 'Category_3'],
            $expandedSearchHttpQueryPlugin->getSearchQuery()->getSearchQueryFacetFilters()[2]->getValues(),
        );
        $this->assertSame(
            'custom-configured-range',
            $expandedSearchHttpQueryPlugin->getSearchQuery()->getSearchQueryFacetFilters()[3]->getFieldName(),
        );
        $this->assertSame(
            1,
            (int)$expandedSearchHttpQueryPlugin->getSearchQuery()->getSearchQueryFacetFilters()[3]->getFrom(),
        );
        $this->assertSame(
            5,
            (int)$expandedSearchHttpQueryPlugin->getSearchQuery()->getSearchQueryFacetFilters()[3]->getTo(),
        );
        $this->assertSame(
            'custom-configured-values',
            $expandedSearchHttpQueryPlugin->getSearchQuery()->getSearchQueryFacetFilters()[4]->getFieldName(),
        );
        $this->assertSame(
            [
                0 => 1,
                1 => 2,
                2 => 3,
            ],
            $expandedSearchHttpQueryPlugin->getSearchQuery()->getSearchQueryFacetFilters()[4]->getValues(),
        );
    }
}

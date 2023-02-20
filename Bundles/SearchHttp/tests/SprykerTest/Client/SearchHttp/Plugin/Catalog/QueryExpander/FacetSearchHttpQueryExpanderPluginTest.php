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
        $searchHttpQueryPlugin = $this->tester->getSearchHttpQueryPlugin();
        $facetSearchHttpQueryExpanderPlugin = new FacetSearchHttpQueryExpanderPlugin();
        $facetSearchHttpQueryExpanderPlugin->setFactory($this->tester->getFactory());

        $requestData = [
            'range-param' => [
                'min' => 10,
                'max' => 100,
            ],
            'pricerange-param' => [
                'min' => 1000,
                'max' => 10000,
            ],
            'category-param' => 6,
        ];

        // Act
        $expandedSearchHttpQueryPlugin = $facetSearchHttpQueryExpanderPlugin->expandQuery($searchHttpQueryPlugin, $requestData);

        // Assert
        $this->assertSame(
            'range-param',
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
            'pricerange-param',
            $expandedSearchHttpQueryPlugin->getSearchQuery()->getSearchQueryFacetFilters()[1]->getFieldName(),
        );
        $this->assertSame(
            1000,
            $expandedSearchHttpQueryPlugin->getSearchQuery()->getSearchQueryFacetFilters()[1]->getFrom(),
        );
        $this->assertSame(
            10000,
            $expandedSearchHttpQueryPlugin->getSearchQuery()->getSearchQueryFacetFilters()[1]->getTo(),
        );
        $this->assertSame(
            'category-param',
            $expandedSearchHttpQueryPlugin->getSearchQuery()->getSearchQueryFacetFilters()[2]->getFieldName(),
        );
        $this->assertSame(
            [0 => 'Category_3'],
            $expandedSearchHttpQueryPlugin->getSearchQuery()->getSearchQueryFacetFilters()[2]->getValues(),
        );
    }
}

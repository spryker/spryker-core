<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchHttp\Plugin\Catalog\ResultFormatter;

use Codeception\Test\Unit;
use Spryker\Client\SearchHttp\Plugin\Catalog\ResultFormatter\FacetSearchHttpResultFormatterPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchHttp
 * @group Plugin
 * @group Catalog
 * @group ResultFormatter
 * @group FacetSearchHttpResultFormatterPluginTest
 * Add your own group annotations below this line
 */
class FacetSearchHttpResultFormatterPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Client\SearchHttp\SearchHttpClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFacetResultFormatterHasProperName(): void
    {
        // Arrange
        $facetSearchHttpResultFormatterPlugin = new FacetSearchHttpResultFormatterPlugin();

        // Act
        $name = $facetSearchHttpResultFormatterPlugin->getName();

        // Assert
        $this->assertEquals('facets', $name);
    }

    /**
     * @return void
     */
    public function testResultFacetsFormattedSuccessfully(): void
    {
        // Arrange
        $searchResult = $this->tester->createSearchHttpResponse();
        $this->tester->mockSearchConfig();
        $this->tester->mockLocaleClientDependency();
        $this->tester->mockStoreClientDependency();
        $this->tester->mockCategoryStorageClientDependency();
        $this->tester->mockMoneyClientDependency();
        $this->tester->mockAggregationExtractorFactory();
        $facetSearchHttpResultFormatterPlugin = new FacetSearchHttpResultFormatterPlugin();
        $facetSearchHttpResultFormatterPlugin->setFactory($this->tester->getFactory());
        $requestParameters = [
            'range' => [
                'min' => 150,
                'max' => 2500,
            ],
            'price' => [
                'min' => 5,
                'max' => 500,
            ],
        ];

        // Act
        $formattedResult = $facetSearchHttpResultFormatterPlugin->formatResult($searchResult, $requestParameters);

        // Assert
        $this->assertEquals(5, count($formattedResult));
        $this->assertEquals('range', $formattedResult['range']->getName());
        $this->assertEquals(200, $formattedResult['range']->getMin());
        $this->assertEquals(2000, $formattedResult['range']->getMax());
        $this->assertEquals(150, $formattedResult['range']->getActiveMin());
        $this->assertEquals(2500, $formattedResult['range']->getActiveMax());
        $this->assertEquals('price', $formattedResult['price']->getName());
        $this->assertEquals(1000, $formattedResult['price']->getMin());
        $this->assertEquals(10000, $formattedResult['price']->getMax());
        $this->assertEquals(500, $formattedResult['price']->getActiveMin());
        $this->assertEquals(50000, $formattedResult['price']->getActiveMax());
        $this->assertEquals('category', $formattedResult['category']->getName());
        $this->assertEquals('1', $formattedResult['category']->getValues()->offsetGet(0)->getValue());
        $this->assertEquals(1, $formattedResult['category']->getValues()->offsetGet(0)->getDocCount());
        $this->assertEquals('2', $formattedResult['category']->getValues()->offsetGet(1)->getValue());
        $this->assertEquals(10, $formattedResult['category']->getValues()->offsetGet(1)->getDocCount());
        $this->assertEquals('3', $formattedResult['category']->getValues()->offsetGet(2)->getValue());
        $this->assertEquals(100, $formattedResult['category']->getValues()->offsetGet(2)->getDocCount());
        $this->assertEquals(1, $formattedResult['custom_configured_range_facet']->getMin());
        $this->assertEquals(5, $formattedResult['custom_configured_range_facet']->getMax());
        $this->assertEquals(1, $formattedResult['custom_configured_range_facet']->getActiveMin());
        $this->assertEquals(5, $formattedResult['custom_configured_range_facet']->getActiveMax());
        $this->assertEquals('value1', $formattedResult['custom_configured_values_facet']->getValues()->offsetGet(0)->getValue());
        $this->assertEquals(5, $formattedResult['custom_configured_values_facet']->getValues()->offsetGet(0)->getDocCount());
        $this->assertEquals('value2', $formattedResult['custom_configured_values_facet']->getValues()->offsetGet(1)->getValue());
        $this->assertEquals(15, $formattedResult['custom_configured_values_facet']->getValues()->offsetGet(1)->getDocCount());
        $this->assertEquals('value3', $formattedResult['custom_configured_values_facet']->getValues()->offsetGet(2)->getValue());
        $this->assertEquals(20, $formattedResult['custom_configured_values_facet']->getValues()->offsetGet(2)->getDocCount());
    }
}

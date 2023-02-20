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

        // Act
        $formattedResult = $facetSearchHttpResultFormatterPlugin->formatResult($searchResult);

        // Assert
        $this->assertEquals('range', $formattedResult['range']->getName());
        $this->assertEquals(200, $formattedResult['range']->getMin());
        $this->assertEquals(2000, $formattedResult['range']->getMax());
        $this->assertEquals(200, $formattedResult['range']->getActiveMin());
        $this->assertEquals(2000, $formattedResult['range']->getActiveMax());
        $this->assertEquals('pricerange', $formattedResult['pricerange']->getName());
        $this->assertEquals(10, $formattedResult['pricerange']->getMin());
        $this->assertEquals(100, $formattedResult['pricerange']->getMax());
        $this->assertEquals(10.0, $formattedResult['pricerange']->getActiveMin());
        $this->assertEquals(100.0, $formattedResult['pricerange']->getActiveMax());
        $this->assertEquals('category', $formattedResult['category']->getName());
        $this->assertEquals('1', $formattedResult['category']->getValues()->offsetGet(0)->getValue());
        $this->assertEquals(1, $formattedResult['category']->getValues()->offsetGet(0)->getDocCount());
        $this->assertEquals('2', $formattedResult['category']->getValues()->offsetGet(1)->getValue());
        $this->assertEquals(10, $formattedResult['category']->getValues()->offsetGet(1)->getDocCount());
        $this->assertEquals('3', $formattedResult['category']->getValues()->offsetGet(2)->getValue());
        $this->assertEquals(100, $formattedResult['category']->getValues()->offsetGet(2)->getDocCount());
    }
}

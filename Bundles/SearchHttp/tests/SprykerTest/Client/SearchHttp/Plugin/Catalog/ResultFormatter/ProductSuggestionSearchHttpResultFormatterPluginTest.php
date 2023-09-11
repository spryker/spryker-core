<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchHttp\Plugin\Catalog\ResultFormatter;

use Codeception\Test\Unit;
use Spryker\Client\SearchHttp\Plugin\Catalog\ResultFormatter\ProductSuggestionSearchHttpResultFormatterPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchHttp
 * @group Plugin
 * @group Catalog
 * @group ResultFormatter
 * @group ProductSuggestionSearchHttpResultFormatterPluginTest
 * Add your own group annotations below this line
 */
class ProductSuggestionSearchHttpResultFormatterPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Client\SearchHttp\SearchHttpClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testProductSuggestionResultFormatterHasProperName(): void
    {
        // Arrange
        $productSuggestionSearchHttpResultFormatterPlugin = new ProductSuggestionSearchHttpResultFormatterPlugin();

        // Act
        $name = $productSuggestionSearchHttpResultFormatterPlugin->getName();

        // Assert
        $this->assertEquals('product_abstract', $name);
    }

    /**
     * @return void
     */
    public function testSuggestionResultProductsFormattedSuccessfully(): void
    {
        // Arrange
        $suggestionsSearchHttpResponse = $this->tester->createSuggestionsSearchHttpResponse();
        $this->tester->mockSearchConfig();
        $this->tester->mockLocaleClientDependency();
        $this->tester->mockProductStorageClientDependency(
            [$suggestionsSearchHttpResponse->getMatchedItems()[0]['product_abstract_sku'] => 1],
        );
        $this->tester->addResultProductMapperToMockedFactory();
        $productSuggestionSearchHttpResultFormatterPlugin = new ProductSuggestionSearchHttpResultFormatterPlugin();
        $productSuggestionSearchHttpResultFormatterPlugin->setFactory($this->tester->getFactory());

        // Act
        $formattedResult = $productSuggestionSearchHttpResultFormatterPlugin->formatResult($suggestionsSearchHttpResponse);

        // Assert
        $this->assertEquals(
            $suggestionsSearchHttpResponse->getMatchedItems()[0]['product_abstract_sku'],
            $formattedResult[0]['abstract_sku'],
        );
        $this->assertEquals(
            'product_abstract',
            $formattedResult[0]['type'],
        );
        $this->assertEquals(
            $suggestionsSearchHttpResponse->getMatchedItems()[0]['abstract_name'],
            $formattedResult[0]['abstract_name'],
        );
    }

    /**
     * @return void
     */
    public function testProductSkippedIfNotExistsInStorage(): void
    {
        // Arrange
        $suggestionsSearchHttpResponse = $this->tester->createSuggestionsSearchHttpResponse();
        $this->tester->mockSearchConfig();
        $this->tester->mockLocaleClientDependency();
        $this->tester->mockProductStorageClientDependency();
        $this->tester->addResultProductMapperToMockedFactory();
        $productSearchHttpResultFormatterPlugin = new ProductSuggestionSearchHttpResultFormatterPlugin();
        $productSearchHttpResultFormatterPlugin->setFactory($this->tester->getFactory());

        // Act
        $formattedResult = $productSearchHttpResultFormatterPlugin->formatResult($suggestionsSearchHttpResponse);

        // Assert
        $this->assertEquals([], $formattedResult);
    }
}

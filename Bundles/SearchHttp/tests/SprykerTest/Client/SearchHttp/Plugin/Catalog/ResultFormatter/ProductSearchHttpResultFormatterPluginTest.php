<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchHttp\Plugin\Catalog\ResultFormatter;

use Codeception\Test\Unit;
use Spryker\Client\SearchHttp\Plugin\Catalog\ResultFormatter\ProductSearchHttpResultFormatterPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchHttp
 * @group Plugin
 * @group Catalog
 * @group ResultFormatter
 * @group ProductSearchHttpResultFormatterPluginTest
 * Add your own group annotations below this line
 */
class ProductSearchHttpResultFormatterPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Client\SearchHttp\SearchHttpClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testProductsResultFormatterHasProperName(): void
    {
        // Arrange
        $productSearchHttpResultFormatterPlugin = new ProductSearchHttpResultFormatterPlugin();

        // Act
        $name = $productSearchHttpResultFormatterPlugin->getName();

        // Assert
        $this->assertEquals('products', $name);
    }

    /**
     * @return void
     */
    public function testResultProductsFormattedSuccessfully(): void
    {
        // Arrange
        $searchResult = $this->tester->createSearchHttpResponse();
        $this->tester->mockSearchConfig();
        $this->tester->mockLocaleClientDependency();
        $this->tester->mockProductStorageClientDependency(
            [$searchResult->getItems()[0]['product_abstract_sku'] => 1],
        );
        $this->tester->addResultProductMapperToMockedFactory();
        $productSearchHttpResultFormatterPlugin = new ProductSearchHttpResultFormatterPlugin();
        $productSearchHttpResultFormatterPlugin->setFactory($this->tester->getFactory());

        // Act
        $formattedResult = $productSearchHttpResultFormatterPlugin->formatResult($searchResult);

        // Assert
        $this->assertEquals(
            $searchResult->getItems()[0]['product_abstract_sku'],
            $formattedResult[0]['abstract_sku'],
        );
    }

    /**
     * @return void
     */
    public function testProductSkippedIfNotExistsInStorage(): void
    {
        // Arrange
        $searchResult = $this->tester->createSearchHttpResponse();
        $this->tester->mockSearchConfig();
        $this->tester->mockLocaleClientDependency();
        $this->tester->mockProductStorageClientDependency();
        $this->tester->addResultProductMapperToMockedFactory();
        $productSearchHttpResultFormatterPlugin = new ProductSearchHttpResultFormatterPlugin();
        $productSearchHttpResultFormatterPlugin->setFactory($this->tester->getFactory());

        // Act
        $formattedResult = $productSearchHttpResultFormatterPlugin->formatResult($searchResult);

        // Assert
        $this->assertEquals([], $formattedResult);
    }
}

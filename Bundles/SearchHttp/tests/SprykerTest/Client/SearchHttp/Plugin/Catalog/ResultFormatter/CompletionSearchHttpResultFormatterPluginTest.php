<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchHttp\Plugin\Catalog\ResultFormatter;

use Codeception\Test\Unit;
use Spryker\Client\SearchHttp\Plugin\Catalog\ResultFormatter\CompletionSearchHttpResultFormatterPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchHttp
 * @group Plugin
 * @group Catalog
 * @group ResultFormatter
 * @group CompletionSearchHttpResultFormatterPluginTest
 * Add your own group annotations below this line
 */
class CompletionSearchHttpResultFormatterPluginTest extends Unit
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
        $completionSearchHttpResultFormatterPlugin = new CompletionSearchHttpResultFormatterPlugin();

        // Act
        $name = $completionSearchHttpResultFormatterPlugin->getName();

        // Assert
        $this->assertEquals('completion', $name);
    }

    /**
     * @return void
     */
    public function testResultCompletionFormattedSuccessfully(): void
    {
        // Arrange
        $suggestionsSearchHttpResponse = $this->tester->createSuggestionsSearchHttpResponse();
        $this->tester->mockSearchConfig();
        $completionSearchHttpResultFormatterPlugin = new CompletionSearchHttpResultFormatterPlugin();
        $completionSearchHttpResultFormatterPlugin->setFactory($this->tester->getFactory());

        // Act
        $formattedResult = $completionSearchHttpResultFormatterPlugin->formatResult($suggestionsSearchHttpResponse);

        // Assert
        $this->assertCount(1, $formattedResult);
        $this->assertEquals('suggestion-product-sku', $formattedResult[0]);
    }
}

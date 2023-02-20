<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchHttp\Plugin\Catalog\ResultFormatter;

use Codeception\Test\Unit;
use Spryker\Client\SearchHttp\Plugin\Catalog\ResultFormatter\SortSearchHttpResultFormatterPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchHttp
 * @group Plugin
 * @group Catalog
 * @group ResultFormatter
 * @group SortSearchHttpResultFormatterPluginTest
 * Add your own group annotations below this line
 */
class SortSearchHttpResultFormatterPluginTest extends Unit
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
        $sortSearchHttpResultFormatterPlugin = new SortSearchHttpResultFormatterPlugin();

        // Act
        $name = $sortSearchHttpResultFormatterPlugin->getName();

        // Assert
        $this->assertEquals('sort', $name);
    }

    /**
     * @return void
     */
    public function testResultSortFormattedSuccessfully(): void
    {
        // Arrange
        $searchResult = $this->tester->createSearchHttpResponse();
        $this->tester->mockSearchConfig();
        $sortSearchHttpResultFormatterPlugin = new SortSearchHttpResultFormatterPlugin();
        $sortSearchHttpResultFormatterPlugin->setFactory($this->tester->getFactory());

        $requestData = [
            'sort' => 'foo_asc',
        ];

        // Act
        $formattedResult = $sortSearchHttpResultFormatterPlugin->formatResult($searchResult, $requestData);

        // Assert
        $this->assertEquals('foo_asc', $formattedResult->getCurrentSortParam());
        $this->assertEquals('asc', $formattedResult->getCurrentSortOrder());
    }
}

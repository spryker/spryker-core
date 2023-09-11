<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchHttp\Plugin\Catalog\ResultFormatter;

use Codeception\Test\Unit;
use Spryker\Client\SearchHttp\Plugin\Catalog\ResultFormatter\PaginationSearchHttpResultFormatterPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchHttp
 * @group Plugin
 * @group Catalog
 * @group ResultFormatter
 * @group PaginationSearchHttpResultFormatterPluginTest
 * Add your own group annotations below this line
 */
class PaginationSearchHttpResultFormatterPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Client\SearchHttp\SearchHttpClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testPaginationResultFormatterHasProperName(): void
    {
        // Arrange
        $paginationSearchHttpResultFormatterPlugin = new PaginationSearchHttpResultFormatterPlugin();

        // Act
        $name = $paginationSearchHttpResultFormatterPlugin->getName();

        // Assert
        $this->assertEquals('pagination', $name);
    }

    /**
     * @return void
     */
    public function testResultPaginationFormattedSuccessfully(): void
    {
        // Arrange
        $searchResult = $this->tester->createSearchHttpResponse();
        $this->tester->mockSearchConfig();
        $paginationSearchHttpResultFormatterPlugin = new PaginationSearchHttpResultFormatterPlugin();
        $paginationSearchHttpResultFormatterPlugin->setFactory($this->tester->getFactory());

        // Act
        $formattedResult = $paginationSearchHttpResultFormatterPlugin->formatResult($searchResult);

        // Assert
        $this->assertEquals($searchResult->getPagination()->getCurrentPage(), $formattedResult->getCurrentPage());
        $this->assertEquals($searchResult->getPagination()->getNumFound(), $formattedResult->getNumFound());
        $this->assertEquals($searchResult->getPagination()->getCurrentItemsPerPage(), $formattedResult->getCurrentItemsPerPage());
        $this->assertEquals(
            ((int)round($searchResult->getPagination()->getNumFound() / $searchResult->getPagination()->getCurrentItemsPerPage())),
            $formattedResult->getMaxPage(),
        );
    }
}

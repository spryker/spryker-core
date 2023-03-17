<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchHttp\Plugin\Catalog\QueryExpander;

use Codeception\Test\Unit;
use Spryker\Client\SearchHttp\Plugin\Catalog\QueryExpander\BasicSearchHttpQueryExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchHttp
 * @group Plugin
 * @group Catalog
 * @group QueryExpander
 * @group BasicSearchHttpQueryExpanderPluginTest
 * Add your own group annotations below this line
 */
class BasicSearchHttpQueryExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Client\SearchHttp\SearchHttpClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSearchHttpQueryExpandedWithSortAndPagination(): void
    {
        // Arrange
        $this->tester->mockSearchConfig();
        $searchHttpQueryPlugin = $this->tester->getSearchHttpQueryPlugin();
        $basicSearchHttpQueryExpanderPlugin = new BasicSearchHttpQueryExpanderPlugin();
        $basicSearchHttpQueryExpanderPlugin->setFactory($this->tester->getFactory());

        $requestData = [
            'page' => 5,
            'ipp' => 500,
            'sort' => 'foo',
        ];

        // Act
        $expandedSearchHttpQueryPlugin = $basicSearchHttpQueryExpanderPlugin->expandQuery($searchHttpQueryPlugin, $requestData);

        // Assert
        $this->assertSame(
            5,
            $expandedSearchHttpQueryPlugin->getSearchQuery()->getPagination()->getPage(),
        );
        $this->assertSame(
            500,
            $expandedSearchHttpQueryPlugin->getSearchQuery()->getPagination()->getItemsPerPage(),
        );
        $this->assertSame(
            'foo',
            $expandedSearchHttpQueryPlugin->getSearchQuery()->getSort()->getFieldName(),
        );
        $this->assertSame(
            'asc',
            $expandedSearchHttpQueryPlugin->getSearchQuery()->getSort()->getSortDirection(),
        );
    }

    /**
     * @return void
     */
    public function testSearchHttpQueryExpandedWithDefaultSortAndPagination(): void
    {
        // Arrange
        $this->tester->mockSearchConfig();
        $searchHttpQueryPlugin = $this->tester->getSearchHttpQueryPlugin();
        $basicSearchHttpQueryExpanderPlugin = new BasicSearchHttpQueryExpanderPlugin();
        $basicSearchHttpQueryExpanderPlugin->setFactory($this->tester->getFactory());

        // Act
        $expandedSearchHttpQueryPlugin = $basicSearchHttpQueryExpanderPlugin->expandQuery($searchHttpQueryPlugin);

        // Assert
        $this->assertSame(
            1,
            $expandedSearchHttpQueryPlugin->getSearchQuery()->getPagination()->getPage(),
        );
        $this->assertSame(
            10,
            $expandedSearchHttpQueryPlugin->getSearchQuery()->getPagination()->getItemsPerPage(),
        );
        $this->assertNull(
            $expandedSearchHttpQueryPlugin->getSearchQuery()->getSort(),
        );
    }
}

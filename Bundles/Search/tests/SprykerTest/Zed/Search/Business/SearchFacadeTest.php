<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Search\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Search\Elasticsearch\CategoryNodeDataPageMapBuilder;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Search\Elasticsearch\ProductPageMapPlugin;
use Spryker\Zed\Search\SearchDependencyProvider;
use Spryker\Zed\SearchElasticsearch\Communication\Plugin\Search\ElasticsearchDataMapperPlugin;
use Spryker\Zed\SearchElasticsearch\Communication\Plugin\Search\PageDataMapperPlugin;
use Spryker\Zed\SearchElasticsearch\SearchElasticsearchDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Search
 * @group Business
 * @group Facade
 * @group SearchFacadeTest
 * Add your own group annotations below this line
 */
class SearchFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Search\SearchBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function _setUp(): void
    {
        parent::_setUp();

        $this->setUpDependencies();
    }

    /**
     * @dataProvider canMapRawDataToSearchDataProvider
     *
     * @param array $inputData
     * @param array $expected
     * @param string $localeName
     * @param string $mapperName
     *
     * @return void
     */
    public function testCanMapRawDataToSearchData(array $inputData, array $expected, string $localeName, string $mapperName): void
    {
        // Arrange
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setLocaleName($localeName);

        // Act
        $result = $this->tester->getFacade()->transformPageMapToDocumentByMapperName($inputData, $localeTransfer, $mapperName);

        // Assert
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    public function canMapRawDataToSearchDataProvider(): array
    {
        return require codecept_data_dir('Fixtures/SearchDataMap/search_data_map_test_provider_data.php');
    }

    /**
     * @return void
     */
    protected function setUpDependencies(): void
    {
        $this->tester->setDependency(SearchDependencyProvider::PLUGINS_SEARCH_DATA_MAPPER, [
            new ElasticsearchDataMapperPlugin(),
        ]);

        $this->tester->setDependency(SearchElasticsearchDependencyProvider::PLUGINS_RESOURCE_DATA_MAPPER, [
            new PageDataMapperPlugin(),
        ]);

        $this->tester->setDependency(SearchElasticsearchDependencyProvider::PLUGINS_PAGE_DATA_MAPPER, [
            new ProductPageMapPlugin(),
            new CategoryNodeDataPageMapBuilder(),
        ]);
    }
}

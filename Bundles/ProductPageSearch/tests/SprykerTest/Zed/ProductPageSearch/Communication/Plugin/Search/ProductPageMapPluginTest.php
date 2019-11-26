<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\ProductPageSearch\tests\SprykerTest\Zed\ProductPageSearch\Communication\Plugin\Search;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Search\ProductPageMapPlugin;
use Spryker\Zed\Search\Business\SearchFacadeInterface;
use Spryker\Zed\Search\SearchDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group Spryker
 * @group ProductPageSearch
 * @group tests
 * @group SprykerTest
 * @group Zed
 * @group ProductPageSearch
 * @group Communication
 * @group Plugin
 * @group Search
 * @group ProductPageMapPluginTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Zed\ProductPageSearch\ProductPageSearchCommunicationTester $tester
 */
class ProductPageMapPluginTest extends Unit
{
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
    public function testCanTransformPageMapToDocumentByMapperName(array $inputData, array $expected, string $localeName, string $mapperName): void
    {
        // Arrange
        $this->tester->setDependency(SearchDependencyProvider::PLUGIN_SEARCH_PAGE_MAPS, [
            new ProductPageMapPlugin(),
        ]);
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setLocaleName($localeName);

        // Act
        $result = $this->getSearchFacade()->transformPageMapToDocumentByMapperName($inputData, $localeTransfer, $mapperName);

        // Assert
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    public function canMapRawDataToSearchDataProvider(): array
    {
        return require codecept_data_dir('Fixtures/SearchDataMap/transform_page_map_test_data_provider.php');
    }

    /**
     * @return \Spryker\Zed\Search\Business\SearchFacadeInterface
     */
    protected function getSearchFacade(): SearchFacadeInterface
    {
        return $this->tester->getLocator()->search()->facade();
    }
}

<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\ProductPageSearch\tests\SprykerTest\Zed\ProductPageSearch\Business\DataMapper;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ProductPageSearch\Business\DataMapper\PageMapBuilder;
use Spryker\Zed\ProductPageSearch\Business\DataMapper\ProductAbstractSearchDataMapper;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductSearchBridge;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductSearchInterface;

/**
 * Auto-generated group annotations
 *
 * @group Spryker
 * @group ProductPageSearch
 * @group tests
 * @group SprykerTest
 * @group Zed
 * @group ProductPageSearch
 * @group Business
 * @group DataMapper
 * @group ProductAbstractSearchDataMapperTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\ProductPageSearch\ProductPageSearchBusinessTester $tester
 */
class ProductAbstractSearchDataMapperTest extends Unit
{
    /**
     * @dataProvider canMapRawDataToSearchDataProvider
     *
     * @param array $inputData
     * @param array $expected
     * @param string $localeName
     *
     * @return void
     */
    public function testCanTransformPageMapToDocumentByMapperName(array $inputData, array $expected, string $localeName): void
    {
        // Arrange
        $productAbstractSearchDataMapper = new ProductAbstractSearchDataMapper(
            new PageMapBuilder(),
            $this->getSearchFacade(),
            []
        );
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setLocaleName($localeName);

        // Act
        $result = $productAbstractSearchDataMapper->mapProductDataToSearchData($inputData, $localeTransfer);

        // Assert
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    public function canMapRawDataToSearchDataProvider(): array
    {
        return require codecept_data_dir('Fixtures/SearchDataMap/product_abstract_page_data_map_data_provider.php');
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductSearchInterface
     */
    protected function getSearchFacade(): ProductPageSearchToProductSearchInterface
    {
        return new ProductPageSearchToProductSearchBridge($this->tester->getLocator()->productSearch()->facade());
    }
}

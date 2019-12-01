<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\ProductPageSearch\tests\SprykerTest\Zed\ProductPageSearch\Business\DataMapper;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ProductPageSearch\Business\DataMapper\PageMapBuilder;
use Spryker\Zed\ProductPageSearch\Business\DataMapper\ProductConcreteSearchDataMapper;

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
 * @group ProductConcreteSearchDataMapperTest
 * Add your own group annotations below this line
 */
class ProductConcreteSearchDataMapperTest extends Unit
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
        $productConcreteSearchDataMapper = new ProductConcreteSearchDataMapper(
            new PageMapBuilder(),
            []
        );
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setLocaleName($localeName);

        // Act
        $result = $productConcreteSearchDataMapper->mapProductDataToSearchData($inputData, $localeTransfer);

        // Assert
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    public function canMapRawDataToSearchDataProvider(): array
    {
        return require codecept_data_dir('Fixtures/SearchDataMap/product_concrete_page_data_map_data_provider.php');
    }
}

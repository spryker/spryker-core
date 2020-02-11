<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSetPageSearch\Business\DataMapper;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ProductSetPageSearch\Business\DataMapper\ProductSetSearchDataMapper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductSetPageSearch
 * @group Business
 * @group DataMapper
 * @group ProductSetPageSearchDataMapperTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\ProductSetPageSearch\ProductSetPageSearchBusinessTester $tester
 */
class ProductSetPageSearchDataMapperTest extends Unit
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
        $productSetPageSearchDataMapper = new ProductSetSearchDataMapper();
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setLocaleName($localeName);

        // Act
        $result = $productSetPageSearchDataMapper->mapProductSetDataToSearchData($inputData, $localeTransfer);

        // Assert
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    public function canMapRawDataToSearchDataProvider(): array
    {
        return require codecept_data_dir('Fixtures/SearchDataMap/product_set_page_data_map_data_provider.php');
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryPageSearch\Business\DataMapper;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\CategoryPageSearch\Business\Search\DataMapper\CategoryNodePageSearchDataMapper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CategoryPageSearch
 * @group Business
 * @group DataMapper
 * @group CategoryNodePageSearchDataMapperTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\CategoryPageSearch\CategoryPageSearchCommunicationTester $tester
 */
class CategoryNodePageSearchDataMapperTest extends Unit
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
        $categoryNodePageSearchDataMapper = new CategoryNodePageSearchDataMapper();
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setLocaleName($localeName);

        // Act
        $result = $categoryNodePageSearchDataMapper->mapCategoryNodeDataToSearchData($inputData, $localeTransfer);

        // Assert
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    public function canMapRawDataToSearchDataProvider(): array
    {
        return require codecept_data_dir('Fixtures/SearchDataMap/category_node_page_data_map_data_provider.php');
    }
}

<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CategoryDataImport\Business;

use Codeception\Test\Unit;
use Spryker\Zed\CategoryDataImport\Business\Writer\CategoryStore\DataSet\CategoryStoreDataSetInterface;
use Spryker\Zed\CategoryDataImport\Business\Writer\CategoryStore\StoreRelationshipFilterStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CategoryDataImport
 * @group Business
 * @group StoreRelationshipFilterStepTest
 * Add your own group annotations below this line
 */
class StoreRelationshipFilterStepTest extends Unit
{
    /**
     * @dataProvider dataProvider
     *
     * @param array $dataSet
     * @param array $expInclude
     * @param array $expExclude
     *
     * @return void
     */
    public function testExecuteWillCorrectlyFilterStoresToWrite(array $dataSet, array $expInclude, array $expExclude): void
    {
        // Arrange
        $storeRelationshipFilterStep = new StoreRelationshipFilterStep();
        $dataSet = new DataSet($dataSet);

        // Act
        $storeRelationshipFilterStep->execute($dataSet);

        // Assert
        $this->assertEquals($dataSet[CategoryStoreDataSetInterface::COL_INCLUDED_STORE_IDS], $expInclude);
        $this->assertEquals($dataSet[CategoryStoreDataSetInterface::COL_EXCLUDED_STORE_IDS], $expExclude);
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            // "" - "" add nothing - remove nothing = nothing
            [
                [
                    CategoryStoreDataSetInterface::COL_INCLUDED_STORE_NAME => '',
                    CategoryStoreDataSetInterface::COL_EXCLUDED_STORE_NAME => '',
                    CategoryStoreDataSetInterface::COL_INCLUDED_STORE_IDS => [],
                    CategoryStoreDataSetInterface::COL_EXCLUDED_STORE_IDS => [],
                ],
                [],
                [],
            ],
            // "" - "*" remove all
            [
                [
                    CategoryStoreDataSetInterface::COL_INCLUDED_STORE_NAME => '',
                    CategoryStoreDataSetInterface::COL_EXCLUDED_STORE_NAME => '*',
                    CategoryStoreDataSetInterface::COL_INCLUDED_STORE_IDS => [],
                    CategoryStoreDataSetInterface::COL_EXCLUDED_STORE_IDS => [1, 2, 3],
                ],
                [],
                [1, 2, 3],
            ],
            // "*" - "" add all
            3 => [
                [
                    CategoryStoreDataSetInterface::COL_INCLUDED_STORE_NAME => '*',
                    CategoryStoreDataSetInterface::COL_EXCLUDED_STORE_NAME => '',
                    CategoryStoreDataSetInterface::COL_INCLUDED_STORE_IDS => [1, 2, 3],
                    CategoryStoreDataSetInterface::COL_EXCLUDED_STORE_IDS => [],
                ],
                [1, 2, 3],
                [],
            ],
            // "*" - "*" add all, remove all = remove all
            [
                [
                    CategoryStoreDataSetInterface::COL_INCLUDED_STORE_NAME => '*',
                    CategoryStoreDataSetInterface::COL_EXCLUDED_STORE_NAME => '*',
                    CategoryStoreDataSetInterface::COL_INCLUDED_STORE_IDS => [1, 2, 3],
                    CategoryStoreDataSetInterface::COL_EXCLUDED_STORE_IDS => [1, 2, 3],
                ],
                [],
                [1, 2, 3],
            ],
            // "" - "x" remove x
            [
                [
                    CategoryStoreDataSetInterface::COL_INCLUDED_STORE_NAME => '',
                    CategoryStoreDataSetInterface::COL_EXCLUDED_STORE_NAME => 'US',
                    CategoryStoreDataSetInterface::COL_INCLUDED_STORE_IDS => [],
                    CategoryStoreDataSetInterface::COL_EXCLUDED_STORE_IDS => [3],
                ],
                [],
                [3],
            ],
            // "*" - "x" add all remove x
            [
                [
                    CategoryStoreDataSetInterface::COL_INCLUDED_STORE_NAME => '*',
                    CategoryStoreDataSetInterface::COL_EXCLUDED_STORE_NAME => 'US',
                    CategoryStoreDataSetInterface::COL_INCLUDED_STORE_IDS => [1, 2, 3],
                    CategoryStoreDataSetInterface::COL_EXCLUDED_STORE_IDS => [3],
                ],
                [1, 2],
                [3],
            ],
            // "x" - "*" add x remove all others
            [
                [
                    CategoryStoreDataSetInterface::COL_INCLUDED_STORE_NAME => 'US',
                    CategoryStoreDataSetInterface::COL_EXCLUDED_STORE_NAME => '*',
                    CategoryStoreDataSetInterface::COL_INCLUDED_STORE_IDS => [3],
                    CategoryStoreDataSetInterface::COL_EXCLUDED_STORE_IDS => [1, 2, 3],
                ],
                [3],
                [1, 2],
            ],
            // "x" - "" add x
            [
                [
                    CategoryStoreDataSetInterface::COL_INCLUDED_STORE_NAME => 'US',
                    CategoryStoreDataSetInterface::COL_EXCLUDED_STORE_NAME => '',
                    CategoryStoreDataSetInterface::COL_INCLUDED_STORE_IDS => [3],
                    CategoryStoreDataSetInterface::COL_EXCLUDED_STORE_IDS => [],
                ],
                [3],
                [],
            ],
            // "x" - "y,z" add x remove y and z
            [
                [
                    CategoryStoreDataSetInterface::COL_INCLUDED_STORE_NAME => 'US',
                    CategoryStoreDataSetInterface::COL_EXCLUDED_STORE_NAME => 'DE, AT',
                    CategoryStoreDataSetInterface::COL_INCLUDED_STORE_IDS => [3],
                    CategoryStoreDataSetInterface::COL_EXCLUDED_STORE_IDS => [1, 2],
                ],
                [3],
                [1, 2],
            ],
            // "x, y" = "y" add x remove y
            [
                [
                    CategoryStoreDataSetInterface::COL_INCLUDED_STORE_NAME => 'DE, AT',
                    CategoryStoreDataSetInterface::COL_EXCLUDED_STORE_NAME => 'AT',
                    CategoryStoreDataSetInterface::COL_INCLUDED_STORE_IDS => [1, 2],
                    CategoryStoreDataSetInterface::COL_EXCLUDED_STORE_IDS => [2],
                ],
                [1],
                [2],
            ],
        ];
    }
}

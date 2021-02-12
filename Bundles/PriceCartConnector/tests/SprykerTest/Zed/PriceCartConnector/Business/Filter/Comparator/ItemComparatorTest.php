<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceCartConnector\Business\Filter\Comparator;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\PriceCartConnector\Business\Filter\Comparator\ItemComparator;
use Spryker\Zed\PriceCartConnector\Business\Filter\Comparator\ItemComparatorInterface;
use Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceCartConnector
 * @group Business
 * @group Filter
 * @group Comparator
 * @group ItemComparatorTest
 * Add your own group annotations below this line
 */
class ItemComparatorTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceCartConnector\PriceCartConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testItemsWithAllSameConfiguredFieldValuesAreSame(): void
    {
        // Arrange
        $itemInCartTransfer = (new ItemBuilder([
            ItemTransfer::SKU => 555,
            ItemTransfer::MERCHANT_REFERENCE => 'ME-001',
        ]))->build();

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::SKU => 555,
            ItemTransfer::MERCHANT_REFERENCE => 'ME-001',
        ]))->build();

        $itemFieldsForIsSameItemComparison = [
            ItemTransfer::SKU,
            ItemTransfer::MERCHANT_REFERENCE,
        ];
        $itemComparator = $this->createItemComparator($itemFieldsForIsSameItemComparison);

        //Act
        $isSame = $itemComparator->isSameItem($itemInCartTransfer, $itemTransfer);

        //Assert
        $this->assertTrue(
            $isSame,
            'Expects that items with same value for all configured comparison fields are same.'
        );
    }

    /**
     * @return void
     */
    public function testItemsWithOnlySameSkuAreNotSame(): void
    {
        // Arrange
        $itemInCartTransfer = (new ItemBuilder([
            ItemTransfer::SKU => 555,
            ItemTransfer::MERCHANT_REFERENCE => 'ME-001',
        ]))->build();

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::SKU => 555,
            ItemTransfer::MERCHANT_REFERENCE => 'ME-999',
        ]))->build();

        $itemFieldsForIsSameItemComparison = [
            ItemTransfer::SKU,
            ItemTransfer::MERCHANT_REFERENCE,
        ];
        $itemComparator = $this->createItemComparator($itemFieldsForIsSameItemComparison);

        //Act
        $isSame = $itemComparator->isSameItem($itemInCartTransfer, $itemTransfer);

        //Assert
        $this->assertFalse(
            $isSame,
            'Expects that items with only same SKU are not same.'
        );
    }

    /**
     * @param string[] $itemFieldsForIsSameItemComparison
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceCartConnector\Business\Filter\PriceProductFilter
     */
    protected function createItemComparator(
        array $itemFieldsForIsSameItemComparison
    ): ItemComparatorInterface {
        return new ItemComparator(
            $this->createPriceCartConnectorConfigMock($itemFieldsForIsSameItemComparison)
        );
    }

    /**
     * @param string[] $itemFieldsForIsSameItemComparison
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig
     */
    protected function createPriceCartConnectorConfigMock(
        array $itemFieldsForIsSameItemComparison
    ): PriceCartConnectorConfig {
        $priceCartConnectorConfigMock = $this
            ->getMockBuilder(PriceCartConnectorConfig::class)
            ->getMock();

        $priceCartConnectorConfigMock
            ->method('getItemFieldsForIsSameItemComparison')
            ->willReturn($itemFieldsForIsSameItemComparison);

        return $priceCartConnectorConfigMock;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfiguration\Business\Counter\Comparator;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Service\ProductConfiguration\ProductConfigurationServiceInterface;
use Spryker\Zed\ProductConfiguration\Business\Counter\Comparator\ItemComparator;
use Spryker\Zed\ProductConfiguration\Business\Counter\Comparator\ItemComparatorInterface;
use Spryker\Zed\ProductConfiguration\ProductConfigurationConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductConfiguration
 * @group Business
 * @group Counter
 * @group Comparator
 * @group ItemComparatorTest
 * Add your own group annotations below this line
 */
class ItemComparatorTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductConfiguration\ProductConfigurationBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testIsSame(): void
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
    public function testIsNotSame(): void
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductConfiguration\Business\Filter\PriceProductFilter
     */
    protected function createItemComparator(
        array $itemFieldsForIsSameItemComparison
    ): ItemComparatorInterface {
        return new ItemComparator(
            $this->createProductConfigurationServiceMock(),
            $this->createProductConfigurationConfigMock($itemFieldsForIsSameItemComparison)
        );
    }

    /**
     * @return \Spryker\Service\ProductConfiguration\ProductConfigurationServiceInterface
     */
    protected function createProductConfigurationServiceMock(): ProductConfigurationServiceInterface
    {
        return $this->getMockBuilder(ProductConfigurationServiceInterface::class)->getMock();
    }

    /**
     * @param string[] $itemFieldsForIsSameItemComparison
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductConfiguration\ProductConfigurationConfig
     */
    protected function createProductConfigurationConfigMock(
        array $itemFieldsForIsSameItemComparison
    ): ProductConfigurationConfig {
        $productConfigurationConfigMock = $this
            ->getMockBuilder(ProductConfigurationConfig::class)
            ->getMock();

        $productConfigurationConfigMock
            ->method('getItemFieldsForIsSameItemComparison')
            ->willReturn($itemFieldsForIsSameItemComparison);

        return $productConfigurationConfigMock;
    }
}

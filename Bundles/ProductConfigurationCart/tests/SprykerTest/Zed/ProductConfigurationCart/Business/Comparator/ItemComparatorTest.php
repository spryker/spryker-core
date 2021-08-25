<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfigurationCart\Business\Comparator;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductConfigurationCart\Business\Comparator\ItemComparator;
use Spryker\Zed\ProductConfigurationCart\Business\Comparator\ItemComparatorInterface;
use Spryker\Zed\ProductConfigurationCart\Dependency\Service\ProductConfigurationCartToProductConfigurationServiceInterface;
use Spryker\Zed\ProductConfigurationCart\ProductConfigurationCartConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductConfigurationCart
 * @group Business
 * @group Comparator
 * @group ItemComparatorTest
 * Add your own group annotations below this line
 */
class ItemComparatorTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductConfigurationCart\ProductConfigurationCartBusinessTester
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductConfigurationCart\Business\Comparator\ItemComparator
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
     * @return \Spryker\Zed\ProductConfigurationCart\Dependency\Service\ProductConfigurationCartToProductConfigurationServiceInterface
     */
    protected function createProductConfigurationServiceMock(): ProductConfigurationCartToProductConfigurationServiceInterface
    {
        return $this->getMockBuilder(ProductConfigurationCartToProductConfigurationServiceInterface::class)->getMock();
    }

    /**
     * @param string[] $itemFieldsForIsSameItemComparison
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductConfigurationCart\ProductConfigurationCartConfig
     */
    protected function createProductConfigurationConfigMock(
        array $itemFieldsForIsSameItemComparison
    ): ProductConfigurationCartConfig {
        $productConfigurationCartConfigMock = $this
            ->getMockBuilder(ProductConfigurationCartConfig::class)
            ->getMock();

        $productConfigurationCartConfigMock
            ->method('getItemFieldsForIsSameItemComparison')
            ->willReturn($itemFieldsForIsSameItemComparison);

        return $productConfigurationCartConfigMock;
    }
}

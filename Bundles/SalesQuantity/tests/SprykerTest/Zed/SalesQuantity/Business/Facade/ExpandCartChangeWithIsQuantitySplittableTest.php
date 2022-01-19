<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesQuantity\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\SalesQuantity\SalesQuantityDependencyProvider;
use Spryker\Zed\SalesQuantityExtension\Dependency\Plugin\NonSplittableItemFilterPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesQuantity
 * @group Business
 * @group Facade
 * @group ExpandCartChangeWithIsQuantitySplittableTest
 * Add your own group annotations below this line
 */
class ExpandCartChangeWithIsQuantitySplittableTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_SKU = '001_000000123';

    /**
     * @var \SprykerTest\Zed\SalesQuantity\SalesQuantityBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandCartChangeWithIsQuantitySplittableExecutesFilterPluginStack(): void
    {
        // Arrange
        $nonSplittableItemFilterPluginMock = $this->getNonSplittableItemFilterPluginMock();

        $this->tester->setDependency(SalesQuantityDependencyProvider::PLUGINS_NON_SPLITTABLE_ITEM_FILTER, [
            $nonSplittableItemFilterPluginMock,
        ]);

        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem((new ItemTransfer())->setSku(static::FAKE_SKU));

        // Assert
        $nonSplittableItemFilterPluginMock->expects($this->once())->method('filterNonSplittableItems');

        // Act
        $this->tester->getFacade()->expandCartChangeWithIsQuantitySplittable($cartChangeTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesQuantityExtension\Dependency\Plugin\NonSplittableItemFilterPluginInterface
     */
    protected function getNonSplittableItemFilterPluginMock(): NonSplittableItemFilterPluginInterface
    {
        $nonSplittableItemFilterPluginMock = $this->getMockBuilder(NonSplittableItemFilterPluginInterface::class)
            ->onlyMethods(['filterNonSplittableItems'])
            ->getMock();

        $nonSplittableItemFilterPluginMock
            ->method('filterNonSplittableItems')
            ->willReturnCallback(function (CartChangeTransfer $cartChangeTransfer) {
                return $cartChangeTransfer;
            });

        return $nonSplittableItemFilterPluginMock;
    }
}

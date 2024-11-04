<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesQuantity\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountableItemTransformerTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\SalesQuantity\Business\SalesQuantityBusinessFactory;
use Spryker\Zed\SalesQuantity\SalesQuantityConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesQuantity
 * @group Business
 * @group Facade
 * @group SalesQuantityFacadeTest
 * Add your own group annotations below this line
 */
class SalesQuantityFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const ABSTRACT_PRODUCT_SKU = 'ABSTRACT_PRODUCT_SKU';

    /**
     * @var string
     */
    protected const CONCRETE_PRODUCT_SKU = 'CONCRETE_PRODUCT_SKU';

    /**
     * @var int
     */
    protected const QUANTITY = 5;

    /**
     * @var \SprykerTest\Zed\SalesQuantity\SalesQuantityBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\SalesQuantity\SalesQuantityConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $configMock;

    /**
     * @var \Spryker\Zed\SalesQuantity\Business\SalesQuantityFacadeInterface
     */
    protected $facade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->configMock = $this->getMockBuilder(SalesQuantityConfig::class)->getMock();

        $this->facade = $this->tester->getFacade();

        $this->facade->setFactory(
            (new SalesQuantityBusinessFactory())
                ->setConfig($this->configMock),
        );
    }

    /**
     * @return void
     */
    public function testTransformNonSplittableItemShouldNotSplitItems(): void
    {
        $itemTransfer = (new ItemTransfer())->setQuantity(static::QUANTITY);
        $itemCollectionTransfer = $this->facade->transformNonSplittableItem($itemTransfer);

        $this->assertSame($itemCollectionTransfer->getItems()->count(), 1);

        foreach ($itemCollectionTransfer->getItems() as $itemTransfer) {
            $this->assertSame($itemTransfer->getQuantity(), static::QUANTITY);
        }
    }

    /**
     * @return void
     */
    public function testExpandItemsShouldPreSetBdValuesToItemTransfer(): void
    {
        $this->saveData(true);
        $item = (new ItemTransfer())->setSku(static::CONCRETE_PRODUCT_SKU);
        $cartChangeTransfer = (new CartChangeTransfer())->setItems(new ArrayObject($item));

        $resultCartChangeTransfer = $this->facade->expandCartChangeWithIsQuantitySplittable($cartChangeTransfer);

        foreach ($resultCartChangeTransfer->getItems() as $itemTransfer) {
            $this->assertSame($itemTransfer->getIsQuantitySplittable(), true);
        }

        $this->saveData(false);

        $resultCartChangeTransfer = $this->facade->expandCartChangeWithIsQuantitySplittable($cartChangeTransfer);

        foreach ($resultCartChangeTransfer->getItems() as $itemTransfer) {
            $this->assertSame($itemTransfer->getIsQuantitySplittable(), false);
        }
    }

    /**
     * @return void
     */
    public function testTransformDiscountableItemShouldBeUsedNonSplitTransformation(): void
    {
        $discountableItemTransfer = $this->createDiscountableItemTransfer();
        $discountableItemTransformerTransfer = $this->createDiscountableItemTransformerTransfer($discountableItemTransfer);

        $this->facade->transformNonSplittableDiscountableItem($discountableItemTransformerTransfer);

        $this->assertSame($discountableItemTransfer->getOriginalItemCalculatedDiscounts()->count(), 1);

        foreach ($discountableItemTransfer->getOriginalItemCalculatedDiscounts() as $resultedDiscountableItemTransfer) {
            $this->assertSame($resultedDiscountableItemTransfer->getUnitAmount(), 10);
            $this->assertSame($resultedDiscountableItemTransfer->getSumAmount(), 50);
            $this->assertSame($resultedDiscountableItemTransfer->getQuantity(), static::QUANTITY);
        }
    }

    /**
     * @see SalesQuantityConfig::findItemQuantityThreshold()
     *
     * @return void
     */
    public function testIsItemQuantitySplittableItemsWithBundleItemIdentifierAreNotAffectedByRegularItemsThreshold(): void
    {
        // Arrange
        $regularItemsThreshold = 5;
        $this->configMock->expects($this->any())->method('findItemQuantityThreshold')->willReturn($regularItemsThreshold);

        $expectedResult = true;

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setIsQuantitySplittable(false);
        $itemTransfer->setBundleItemIdentifier('test-id');
        $itemTransfer->setQuantity($regularItemsThreshold);

        // Act
        $actualResult = $this->facade->isItemQuantitySplittable($itemTransfer);

        // Assert
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @see SalesQuantityConfig::findItemQuantityThreshold()
     *
     * @return void
     */
    public function testIsItemQuantitySplittableItemsWithRelatedBundleItemIdentifierAreNotAffectedByRegularItemsThreshold(): void
    {
        // Arrange
        $regularItemsThreshold = 5;
        $this->configMock->expects($this->any())->method('findItemQuantityThreshold')->willReturn($regularItemsThreshold);
        $expectedResult = true;

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setIsQuantitySplittable(false);
        $itemTransfer->setRelatedBundleItemIdentifier('test-id');
        $itemTransfer->setQuantity($regularItemsThreshold);

        // Act
        $actualResult = $this->facade->isItemQuantitySplittable($itemTransfer);

        // Assert
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @see SalesQuantityConfig::findItemQuantityThreshold()
     *
     * @return void
     */
    public function testIsItemQuantitySplittableReturnsFalseForNonSplittableItems(): void
    {
        // Assign
        $threshold = null;
        $this->configMock->expects($this->any())->method('findItemQuantityThreshold')->willReturn($threshold);
        $expectedResult = false;

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setIsQuantitySplittable(false);

        // Act
        $actualResult = $this->facade->isItemQuantitySplittable($itemTransfer);

        // Assert
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @see SalesQuantityConfig::getBundledItemNonSplitQuantityThreshold()
     *
     * @return void
     */
    public function testIsItemQuantitySplittableRegularItemsAreNotAffectedByBundledItemsThreshold(): void
    {
        // Arrange
        $bundledItemsThreshold = 5;
        $this->configMock
            ->expects($this->any())
            ->method('getBundledItemNonSplitQuantityThreshold')
            ->willReturn($bundledItemsThreshold);
        $expectedResult = true;

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity($bundledItemsThreshold);

        // Act
        $actualResult = $this->facade->isItemQuantitySplittable($itemTransfer);

        // Assert
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @dataProvider thresholds
     *
     * @see SalesQuantityConfig::findItemQuantityThreshold()
     *
     * @param bool $expectedResult
     * @param int $quantity
     * @param int|null $threshold
     *
     * @return void
     */
    public function testIsItemQuantitySplittableRespectsThreshold(bool $expectedResult, int $quantity, ?int $threshold): void
    {
        // Assign
        $this->configMock->expects($this->any())->method('findItemQuantityThreshold')->willReturn($threshold);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity($quantity);

        // Act
        $actualResult = $this->facade->isItemQuantitySplittable($itemTransfer);

        // Assert
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @dataProvider thresholds
     *
     * @see SalesQuantityConfig::getBundledItemNonSplitQuantityThreshold()
     *
     * @param bool $expectedResult
     * @param int $quantity
     * @param int|null $threshold
     *
     * @return void
     */
    public function testIsItemQuantitySplittableRespectsBundledItemsThreshold(bool $expectedResult, int $quantity, ?int $threshold): void
    {
        // Arrange
        $this->configMock
            ->expects($this->any())
            ->method('getBundledItemNonSplitQuantityThreshold')
            ->willReturn($threshold);

        $itemTransfer = (new ItemTransfer())
            ->setQuantity($quantity)
            ->setRelatedBundleItemIdentifier('test-id');

        // Act
        $actualResult = $this->facade->isItemQuantitySplittable($itemTransfer);

        // Assert
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @return array
     */
    public function thresholds(): array
    {
        return [
            'Test that item is splittable if threshold is not set' => [true, 5, null],
            'Test that item is splittable if quantity is below the threshold' => [true, 5, 6],
            'Test that item is not splittable if quantity equals to the threshold' => [false, 5, 5],
            'Test that item is not splittable if quantity is higher than the threshold' => [false, 5, 4],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransformerTransfer
     */
    protected function createDiscountableItemTransformerTransfer(DiscountableItemTransfer $discountableItemTransfer): DiscountableItemTransformerTransfer
    {
        $discountTransfer = (new DiscountTransfer())->setIdDiscount(1);
        $totalDiscountAmount = 10;
        $totalAmount = 100;

        $discountableItemTransformerTransfer = new DiscountableItemTransformerTransfer();
        $discountableItemTransformerTransfer->setDiscountableItem($discountableItemTransfer)
            ->setDiscount($discountTransfer)
            ->setTotalDiscountAmount($totalDiscountAmount)
            ->setTotalAmount($totalAmount)
            ->setQuantity(static::QUANTITY);

        return $discountableItemTransformerTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer
     */
    protected function createDiscountableItemTransfer(): DiscountableItemTransfer
    {
        $discountableItemTransfer = new DiscountableItemTransfer();
        $discountableItemTransfer->setUnitPrice(100)
            ->setQuantity(static::QUANTITY);

        return $discountableItemTransfer;
    }

    /**
     * @param bool $isQuantitySplittable
     *
     * @return void
     */
    protected function saveData(bool $isQuantitySplittable): void
    {
        $productAbstract = SpyProductAbstractQuery::create()
            ->filterBySku(static::ABSTRACT_PRODUCT_SKU)
            ->findOne();

        if ($productAbstract === null) {
            $productAbstract = new SpyProductAbstract();

            $productAbstract
                ->setAttributes('{}')
                ->setSku(static::ABSTRACT_PRODUCT_SKU);
        }

        $productAbstract->save();

        $product = SpyProductQuery::create()
            ->filterBySku(static::CONCRETE_PRODUCT_SKU)
            ->findOne();

        if ($product === null) {
            $product = new SpyProduct();
            $product->setAttributes('{}')
                ->setSku(static::CONCRETE_PRODUCT_SKU);
        }

        $product->setFkProductAbstract($productAbstract->getIdProductAbstract())
            ->setIsQuantitySplittable($isQuantitySplittable)
            ->save();
    }
}

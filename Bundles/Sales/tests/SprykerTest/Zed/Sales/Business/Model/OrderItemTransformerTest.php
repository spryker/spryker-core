<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Model;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use PHPUnit\Framework\Assert;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Model
 * @group OrderItemTransformerTest
 * Add your own group annotations below this line
 */
class OrderItemTransformerTest extends Unit
{
    /**
     * @var string
     */
    public const PRODUCT_SKU = 'sku-123-321';

    /**
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider transformSplittableItemDataProvider
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $expectedCount
     * @param callable|null $assertions
     *
     * @return void
     */
    public function testTransformSplittableItem(ItemTransfer $itemTransfer, int $expectedCount, ?callable $assertions = null): void
    {
        // Arrange
        $orderItemTransformer = $this->tester
            ->getFactory()
            ->createOrderItemTransformer();

        // Act
        $transformedItemsCollection = $orderItemTransformer->transformSplittableItem($itemTransfer);

        // Assert
        $this->assertCount($expectedCount, $transformedItemsCollection->getItems());
        $optionObjectIds = [];
        foreach ($transformedItemsCollection->getItems() as $transformedItemTransfer) {
            $this->assertSame(1, $transformedItemTransfer->getQuantity());
            $this->assertSame(static::PRODUCT_SKU, $transformedItemTransfer->getSku());
            $options = $transformedItemTransfer->getProductOptions();
            if ($options !== null) {
                $optionObjectIds[] = spl_object_id($options);
            }
            if ($assertions) {
                $assertions($transformedItemTransfer);
            }
        }
        if (count($optionObjectIds) > 1) {
            $this->assertCount(count(array_unique($optionObjectIds)), $optionObjectIds, 'Each item must have its own productOptions object');
        }
    }

    /**
     * @return array<string, array{
     *   int, array, int, callable|null
     * }>
     */
    public function transformSplittableItemDataProvider(): array
    {
        $option1 = (new ProductOptionTransfer())
            ->setIdProductOptionValue(123)
            ->setQuantity(2);
        $option2 = (new ProductOptionTransfer())
            ->setIdProductOptionValue(456)
            ->setQuantity(3);

        return [
            'quantity_1_no_options' => [
                (new ItemTransfer())
                    ->setSku(static::PRODUCT_SKU)
                    ->setQuantity(1)
                    ->setProductOptions(new ArrayObject()),
                1,
                function ($itemTransfer) {
                    Assert::assertEmpty($itemTransfer->getProductOptions());
                },
            ],
            'quantity_3_no_options' => [
                (new ItemTransfer())
                    ->setSku(static::PRODUCT_SKU)
                    ->setQuantity(3)
                    ->setProductOptions(new ArrayObject()),
                3,
                function ($itemTransfer) {
                    Assert::assertEmpty($itemTransfer->getProductOptions());
                },
            ],
            'quantity_0' => [
                (new ItemTransfer())
                    ->setSku(static::PRODUCT_SKU)
                    ->setQuantity(0)
                    ->setProductOptions(new ArrayObject()),
                0,
                null,
            ],
            'quantity_negative' => [
                (new ItemTransfer())
                    ->setSku(static::PRODUCT_SKU)
                    ->setQuantity(-2)
                    ->setProductOptions(new ArrayObject()),
                0,
                null,
            ],
            'quantity_2_one_option' => [
                (new ItemTransfer())
                    ->setSku(static::PRODUCT_SKU)
                    ->setQuantity(2)
                    ->setProductOptions(new ArrayObject([$option1])),
                2,
                null,
            ],
            'quantity_2_multi_options' => [
                (new ItemTransfer())
                    ->setSku(static::PRODUCT_SKU)
                    ->setQuantity(2)
                    ->setProductOptions(new ArrayObject([$option1, $option2])),
                2,
                null,
            ],
        ];
    }
}

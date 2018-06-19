<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\Distributor;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CollectedDiscountTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\Discount\Business\DiscountFacade;
use Spryker\Zed\Discount\Business\Distributor\Distributor;
use Spryker\Zed\Discount\Communication\Plugin\DiscountExtension\SingleQuantityBasedDiscountableItemTransformerStrategyPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group Distributor
 * @group DistributorTest
 * Add your own group annotations below this line
 */
class DistributorTest extends Unit
{
    const ITEM_GROSS_PRICE_ZERO = 0;
    const ITEM_GROSS_PRICE_1000 = 1000;
    const ITEM_GROSS_PRICE_2000 = 2000;
    const ITEM_GROSS_PRICE_4000 = 4000;

    const DISCOUNT_AMOUNT_100 = 100;
    const DISCOUNT_AMOUNT_200 = 200;
    const DISCOUNT_AMOUNT_300 = 300;
    const DISCOUNT_AMOUNT_400 = 400;
    const DISCOUNT_AMOUNT_700 = 700;
    const DISCOUNT_AMOUNT_4000 = 4000;
    const DISCOUNT_AMOUNT_13333 = 133;
    const DISCOUNT_AMOUNT_13334 = 134;
    const DISCOUNT_AMOUNT_NEGATIVE = -100;

    /**
     * @var \Spryker\Zed\Discount\Business\DiscountFacade
     */
    protected $discountFacade;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->discountFacade = new DiscountFacade();
    }

    /**
     * @return void
     */
    public function testDistributeAmountLimitTheDiscountAmountToTheObjectGrossPrice()
    {
        $items = $this->getItems(
            [
                self::ITEM_GROSS_PRICE_1000,
                self::ITEM_GROSS_PRICE_1000,
                self::ITEM_GROSS_PRICE_1000,
            ]
        );

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setAmount(self::DISCOUNT_AMOUNT_4000);

        $collectedDiscountTransfer = new CollectedDiscountTransfer();
        $collectedDiscountTransfer->setDiscount($discountTransfer);
        $collectedDiscountTransfer->setDiscountableItems($items);

        $this->discountFacade->distributeAmount($collectedDiscountTransfer);

        $this->assertEquals($items[0]->getUnitPrice(), current($items[0]->getOriginalItemCalculatedDiscounts())->getUnitAmount());
        $this->assertEquals($items[1]->getUnitPrice(), current($items[1]->getOriginalItemCalculatedDiscounts())->getUnitAmount());
        $this->assertEquals($items[2]->getUnitPrice(), current($items[2]->getOriginalItemCalculatedDiscounts())->getUnitAmount());
    }

    /**
     * @return void
     */
    public function testDistributeShouldDistributeAmountEquallyToEqualExpensiveObjects()
    {
        $items = $this->getItems(
            [
                self::ITEM_GROSS_PRICE_1000,
                self::ITEM_GROSS_PRICE_1000,
                self::ITEM_GROSS_PRICE_1000,
            ]
        );

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setAmount(self::DISCOUNT_AMOUNT_300);

        $collectedDiscountTransfer = new CollectedDiscountTransfer();
        $collectedDiscountTransfer->setDiscount($discountTransfer);
        $collectedDiscountTransfer->setDiscountableItems($items);

        $this->discountFacade->distributeAmount($collectedDiscountTransfer);

        $this->assertEquals(self::DISCOUNT_AMOUNT_300 / 3, current($items[0]->getOriginalItemCalculatedDiscounts())->getUnitAmount());
        $this->assertEquals(self::DISCOUNT_AMOUNT_300 / 3, current($items[1]->getOriginalItemCalculatedDiscounts())->getUnitAmount());
        $this->assertEquals(self::DISCOUNT_AMOUNT_300 / 3, current($items[2]->getOriginalItemCalculatedDiscounts())->getUnitAmount());
    }

    /**
     * @return void
     */
    public function testDistributeShouldDistributeAmountWithRoundingErrorCorrection()
    {
        $items = $this->getItems(
            [
                self::ITEM_GROSS_PRICE_1000,
                self::ITEM_GROSS_PRICE_1000,
                self::ITEM_GROSS_PRICE_1000,
            ]
        );

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setAmount(self::DISCOUNT_AMOUNT_400);

        $collectedDiscountTransfer = new CollectedDiscountTransfer();
        $collectedDiscountTransfer->setDiscount($discountTransfer);
        $collectedDiscountTransfer->setDiscountableItems($items);

        $this->discountFacade->distributeAmount($collectedDiscountTransfer);

        $this->assertEquals(self::DISCOUNT_AMOUNT_13333, current($items[0]->getOriginalItemCalculatedDiscounts())->getUnitAmount());
        $this->assertEquals(self::DISCOUNT_AMOUNT_13334, current($items[1]->getOriginalItemCalculatedDiscounts())->getUnitAmount());
        $this->assertEquals(self::DISCOUNT_AMOUNT_13333, current($items[2]->getOriginalItemCalculatedDiscounts())->getUnitAmount());
    }

    /**
     * @return void
     */
    public function testDistributeShouldDistributeDiscountAmountInRelationToObjectGrossPrice()
    {
        $items = $this->getItems(
            [
                self::ITEM_GROSS_PRICE_1000,
                self::ITEM_GROSS_PRICE_2000,
                self::ITEM_GROSS_PRICE_4000,
            ]
        );

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setAmount(self::DISCOUNT_AMOUNT_700);

        $collectedDiscountTransfer = new CollectedDiscountTransfer();
        $collectedDiscountTransfer->setDiscount($discountTransfer);
        $collectedDiscountTransfer->setDiscountableItems($items);

        $this->discountFacade->distributeAmount($collectedDiscountTransfer);

        $this->assertEquals(self::DISCOUNT_AMOUNT_100, current($items[0]->getOriginalItemCalculatedDiscounts())->getUnitAmount());
        $this->assertEquals(self::DISCOUNT_AMOUNT_200, current($items[1]->getOriginalItemCalculatedDiscounts())->getUnitAmount());
        $this->assertEquals(self::DISCOUNT_AMOUNT_400, current($items[2]->getOriginalItemCalculatedDiscounts())->getUnitAmount());
    }

    /**
     * @return void
     */
    public function testDistributionForNegativeDiscountAmountShouldNotDistributeAnyDiscounts()
    {
        $items = $this->getItems(
            [
                self::ITEM_GROSS_PRICE_1000,
                self::ITEM_GROSS_PRICE_2000,
                self::ITEM_GROSS_PRICE_4000,
            ]
        );

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setAmount(self::DISCOUNT_AMOUNT_NEGATIVE);

        $collectedDiscountTransfer = new CollectedDiscountTransfer();
        $collectedDiscountTransfer->setDiscount($discountTransfer);
        $collectedDiscountTransfer->setDiscountableItems($items);

        $this->discountFacade->distributeAmount($collectedDiscountTransfer);

        $this->assertEquals(0, $items[0]->getOriginalItemCalculatedDiscounts()->count());
        $this->assertEquals(0, $items[1]->getOriginalItemCalculatedDiscounts()->count());
        $this->assertEquals(0, $items[2]->getOriginalItemCalculatedDiscounts()->count());
    }

    /**
     * @return void
     */
    public function testDistributeShouldNotDistributeDiscountsForObjectsWithZeroGrossPrices()
    {
        $items = $this->getItems(
            [
                self::ITEM_GROSS_PRICE_ZERO,
                self::ITEM_GROSS_PRICE_ZERO,
                self::ITEM_GROSS_PRICE_ZERO,
            ]
        );

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setAmount(self::DISCOUNT_AMOUNT_100);

        $collectedDiscountTransfer = new CollectedDiscountTransfer();
        $collectedDiscountTransfer->setDiscount($discountTransfer);
        $collectedDiscountTransfer->setDiscountableItems($items);

        $this->discountFacade->distributeAmount($collectedDiscountTransfer);

        $this->assertEquals(0, $items[0]->getOriginalItemCalculatedDiscounts()->count());
        $this->assertEquals(0, $items[1]->getOriginalItemCalculatedDiscounts()->count());
        $this->assertEquals(0, $items[2]->getOriginalItemCalculatedDiscounts()->count());
    }

    /**
     * @param array $grossPrices
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    protected function getItems(array $grossPrices)
    {
        $items = new ArrayObject();

        foreach ($grossPrices as $grossPrice) {
            $discountableItemTransfer = new DiscountableItemTransfer();
            $discountableItemTransfer->setUnitPrice($grossPrice);
            $discountableItemTransfer->setQuantity(1);
            $discountableItemTransfer->setOriginalItemCalculatedDiscounts(new ArrayObject());
            $items->append($discountableItemTransfer);
        }

        return $items;
    }

    /**
     * @return void
     */
    public function testWhenDiscountAmountCouldNotEvenlySplitShouldAdjustDistributedAmount()
    {
        $distributor = $this->createDistributor();
        $discountableObjects = $this->createDiscountableObjects([
            [
                'unit_gross_price' => 10,
            ],
            [
                'unit_gross_price' => 10,
            ],
            [
                'unit_gross_price' => 10,
            ],
        ]);

        $discountAmount = 10;
        $discountTransfer = $this->createDiscountTransfer($discountAmount);

        $collectedDiscountTransfer = new CollectedDiscountTransfer();
        $collectedDiscountTransfer->setDiscount($discountTransfer);
        $collectedDiscountTransfer->setDiscountableItems($discountableObjects);

        $distributor->distributeDiscountAmountToDiscountableItems($collectedDiscountTransfer);

        $totalAmount = 0;
        foreach ($discountableObjects as $discountableObject) {
            $totalAmount += $discountableObject->getOriginalItemCalculatedDiscounts()[0]->getUnitAmount();
        }

        $this->assertSame($discountAmount, $totalAmount);
    }

    /**
     * @return void
     */
    public function testWhenTotalAmountIsNegativeShouldTerminateDistribution()
    {
        $distributor = $this->createDistributor();

        $discountableObjects = $this->createDiscountableObjects([
            [
                'unit_gross_price' => -10,
            ],
            [
                'unit_gross_price' => -10,
            ],
            [
                'unit_gross_price' => -10,
            ],
        ]);

        $collectedDiscountTransfer = new CollectedDiscountTransfer();
        $collectedDiscountTransfer->setDiscountableItems($discountableObjects);

        $distributor->distributeDiscountAmountToDiscountableItems($collectedDiscountTransfer);

        $totalAmount = 0;
        foreach ($discountableObjects as $discountableObject) {
            if (count($discountableObject->getOriginalItemCalculatedDiscounts()) === 0) {
                continue;
            }
            $totalAmount += $discountableObject->getOriginalItemCalculatedDiscounts()[0]->getUnitAmount();
        }

        $this->assertEmpty($totalAmount);
    }

    /**
     * @return void
     */
    public function testWhenTotalDiscountAmountIsNegativeShouldTerminateDistribution()
    {
        $distributor = $this->createDistributor();

        $discountableObjects = $this->createDiscountableObjects([
            [
                'unit_gross_price' => 10,
            ],
        ]);

        $collectedDiscountTransfer = new CollectedDiscountTransfer();
        $discountTransfer = $this->createDiscountTransfer(-100);
        $collectedDiscountTransfer->setDiscount($discountTransfer);
        $collectedDiscountTransfer->setDiscountableItems($discountableObjects);

        $distributor->distributeDiscountAmountToDiscountableItems($collectedDiscountTransfer);

        $totalAmount = 0;
        foreach ($discountableObjects as $discountableObject) {
            if (count($discountableObject->getOriginalItemCalculatedDiscounts()) === 0) {
                continue;
            }
            $totalAmount += $discountableObject->getOriginalItemCalculatedDiscounts()[0]->getUnitAmount();
        }

        $this->assertEmpty($totalAmount);
    }

    /**
     * @return void
     */
    public function testWhenTotalDiscountAmountIsMoreThanTotalGrossAmountShouldUseTotalGrossAmount()
    {
        $distributor = $this->createDistributor();

        $discountableObjects = $this->createDiscountableObjects([
            [
                'unit_gross_price' => 10,
            ],
        ]);

        $collectedDiscountTransfer = new CollectedDiscountTransfer();
        $discountTransfer = $this->createDiscountTransfer(5000);
        $collectedDiscountTransfer->setDiscount($discountTransfer);
        $collectedDiscountTransfer->setDiscountableItems($discountableObjects);

        $distributor->distributeDiscountAmountToDiscountableItems($collectedDiscountTransfer);

        $totalAmount = 0;
        foreach ($discountableObjects as $discountableObject) {
            if (count($discountableObject->getOriginalItemCalculatedDiscounts()) === 0) {
                continue;
            }
            $totalAmount += $discountableObject->getOriginalItemCalculatedDiscounts()[0]->getUnitAmount();
        }

        $this->assertSame(10, $totalAmount);
    }

    /**
     * @return void
     */
    public function testWhenDiscountableItemWhenQuantityIsMissingShouldUseOneByDefault()
    {
        $distributor = $this->createDistributor();

        $discountableItemTransfer = new DiscountableItemTransfer();
        $discountableItemTransfer->setUnitPrice(50);

        $discountableObjects = [];
        $discountableObjects[] = $discountableItemTransfer;

        $collectedDiscountTransfer = new CollectedDiscountTransfer();
        $discountTransfer = $this->createDiscountTransfer(50);
        $collectedDiscountTransfer->setDiscount($discountTransfer);
        $collectedDiscountTransfer->setDiscountableItems(new ArrayObject($discountableObjects));

        $distributor->distributeDiscountAmountToDiscountableItems($collectedDiscountTransfer);

        $totalAmount = 0;
        foreach ($discountableObjects as $discountableObject) {
            if (count($discountableObject->getOriginalItemCalculatedDiscounts()) === 0) {
                continue;
            }
            $totalAmount += $discountableObject->getOriginalItemCalculatedDiscounts()[0]->getUnitAmount();
        }

        $this->assertSame(50, $totalAmount);
    }

    /**
     * @return void
     */
    public function testDistributeWithRoundingErrorShouldMoveCentToNextItem()
    {
        $distributor = $this->createDistributor();
        $discountableObjects = $this->createDiscountableObjects([
            ['unit_gross_price' => 50],
            ['unit_gross_price' => 50],
            ['unit_gross_price' => 50],
        ]);

        $collectedDiscountTransfer = new CollectedDiscountTransfer();
        $discountTransfer = $this->createDiscountTransfer(100);
        $collectedDiscountTransfer->setDiscount($discountTransfer);
        $collectedDiscountTransfer->setDiscountableItems($discountableObjects);

        $distributor->distributeDiscountAmountToDiscountableItems($collectedDiscountTransfer);

        $totalAmount = 0;
        foreach ($discountableObjects as $discountableObject) {
            if (count($discountableObject->getOriginalItemCalculatedDiscounts()) === 0) {
                continue;
            }
            $totalAmount += $discountableObject->getOriginalItemCalculatedDiscounts()[0]->getUnitAmount();
        }

        $discountableItemTransfer = $discountableObjects[0];
        $unitGrossPrice = $discountableItemTransfer->getOriginalItemCalculatedDiscounts()[0]->getUnitAmount();
        $this->assertSame(33, $unitGrossPrice);

        $discountableItemTransfer = $discountableObjects[1];
        $unitGrossPrice = $discountableItemTransfer->getOriginalItemCalculatedDiscounts()[0]->getUnitAmount();
        $this->assertSame(34, $unitGrossPrice);

        $discountableItemTransfer = $discountableObjects[2];
        $unitGrossPrice = $discountableItemTransfer->getOriginalItemCalculatedDiscounts()[0]->getUnitAmount();
        $this->assertSame(33, $unitGrossPrice);

        $this->assertEquals(100, $totalAmount);
    }

    /**
     * @param array $items
     *
     * @return \ArrayObject
     */
    protected function createDiscountableObjects($items = [])
    {
        $discountableObjects = new ArrayObject();
        foreach ($items as $item) {
            $discountableItemTransfer = new DiscountableItemTransfer();
            $discountableItemTransfer->setUnitPrice($item['unit_gross_price']);
            $discountableItemTransfer->setQuantity(1);
            $discountableItemTransfer->setOriginalItemCalculatedDiscounts(new ArrayObject());
            $discountableObjects->append($discountableItemTransfer);
        }

        return $discountableObjects;
    }

    /**
     * @param int $discountAmount
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    protected function createDiscountTransfer($discountAmount)
    {
        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setAmount($discountAmount);

        return $discountTransfer;
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Distributor\Distributor
     */
    protected function createDistributor()
    {
        return new Distributor($this->createDiscountableItemTransformerStrategyPlugins());
    }

    /**
     * @return \Spryker\Zed\DiscountExtension\Dependency\Plugin\DiscountableItemTransformerStrategyPluginInterface[]
     */
    protected function createDiscountableItemTransformerStrategyPlugins(): array
    {
        // BC default plugin
        return [
            new SingleQuantityBasedDiscountableItemTransformerStrategyPlugin(),
        ];
    }
}

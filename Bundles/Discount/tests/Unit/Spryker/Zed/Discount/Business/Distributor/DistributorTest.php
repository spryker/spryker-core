<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Business\Distributor;

use Generated\Shared\Transfer\CollectedDiscountTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\Discount\Business\Distributor\Distributor;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Discount
 * @group Business
 * @group Distributor
 * @group DistributorTest
 */
class DistributorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testWhenDiscountAmountCouldNotEvenlySplitShouldAdjustDistributedAmount()
    {
        $distributor = $this->createDistributor();
        $discountableObjects = $this->createDiscountableObjects([
            [
                'unit_gross_price' => 10
            ],
            [
                'unit_gross_price' => 10
            ],
            [
                'unit_gross_price' => 10
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
            $totalAmount += $discountableObject->getOriginalItemCalculatedDiscounts()[0]->getUnitGrossAmount();
        }

        $this->assertEquals($discountAmount, $totalAmount);
    }

    /**
     * @return void
     */
    public function testWhenTotalAmountIsNegativeShouldTerminateDistribution()
    {
        $distributor = $this->createDistributor();

        $discountableObjects = $this->createDiscountableObjects([
            [
                'unit_gross_price' => -10
            ],
            [
                'unit_gross_price' => -10
            ],
            [
                'unit_gross_price' => -10
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
            $totalAmount += $discountableObject->getOriginalItemCalculatedDiscounts()[0]->getUnitGrossAmount();
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
                'unit_gross_price' => 10
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
            $totalAmount += $discountableObject->getOriginalItemCalculatedDiscounts()[0]->getUnitGrossAmount();
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
                'unit_gross_price' => 10
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
            $totalAmount += $discountableObject->getOriginalItemCalculatedDiscounts()[0]->getUnitGrossAmount();
        }

        $this->assertEquals(10, $totalAmount);
    }

    /**
     * @return void
     */
    public function testWhenDiscountableItemWhenQuantityIsMissingShouldUseOneByDefault()
    {
        $distributor = $this->createDistributor();

        $discountableItemTransfer = new DiscountableItemTransfer();
        $discountableItemTransfer->setUnitGrossPrice(50);

        $discountableObjects = [];
        $discountableObjects[] = $discountableItemTransfer;

        $collectedDiscountTransfer = new CollectedDiscountTransfer();
        $discountTransfer = $this->createDiscountTransfer(50);
        $collectedDiscountTransfer->setDiscount($discountTransfer);
        $collectedDiscountTransfer->setDiscountableItems(new \ArrayObject($discountableObjects));

        $distributor->distributeDiscountAmountToDiscountableItems($collectedDiscountTransfer);

        $totalAmount = 0;
        foreach ($discountableObjects as $discountableObject) {
            if (count($discountableObject->getOriginalItemCalculatedDiscounts()) === 0) {
                continue;
            }
            $totalAmount += $discountableObject->getOriginalItemCalculatedDiscounts()[0]->getUnitGrossAmount();
        }

        $this->assertEquals(50, $totalAmount);
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
            $totalAmount += $discountableObject->getOriginalItemCalculatedDiscounts()[0]->getUnitGrossAmount();
        }

        $discountableItemTransfer = $discountableObjects[0];
        $unitGrossPrice = $discountableItemTransfer->getOriginalItemCalculatedDiscounts()[0]->getUnitGrossAmount();
        $this->assertSame(33, $unitGrossPrice);

        $discountableItemTransfer = $discountableObjects[1];
        $unitGrossPrice = $discountableItemTransfer->getOriginalItemCalculatedDiscounts()[0]->getUnitGrossAmount();
        $this->assertSame(34, $unitGrossPrice);

        $discountableItemTransfer = $discountableObjects[2];
        $unitGrossPrice = $discountableItemTransfer->getOriginalItemCalculatedDiscounts()[0]->getUnitGrossAmount();
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
        $discountableObjects = new \ArrayObject();
        foreach ($items as $item) {
            $discountableItemTransfer = new DiscountableItemTransfer();
            $discountableItemTransfer->setUnitGrossPrice($item['unit_gross_price']);
            $discountableItemTransfer->setQuantity(1);
            $discountableItemTransfer->setOriginalItemCalculatedDiscounts(new \ArrayObject());
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
        return new Distributor();
    }

}

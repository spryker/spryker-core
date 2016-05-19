<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Business\DecisionRule;

use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\Discount\Business\Distributor\Distributor;

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

        $distributor->distribute($discountableObjects, $discountTransfer);

        $totalAmount = 0;
        foreach ($discountableObjects as $discountableObject) {
            $totalAmount += $discountableObject->getOriginalItemCalculatedDiscounts()[0]->getUnitGrossAmount();
        }

        $this->assertEquals($discountAmount, $totalAmount);
    }

    /**
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    protected function createDiscountableObjects($items = [])
    {
        $discountableObjects = [];
        foreach ($items as $item) {
            $discountableItems = new DiscountableItemTransfer();
            $discountableItems->setUnitGrossPrice($item['unit_gross_price']);
            $discountableItems->setQuantity(1);
            $discountableItems->setOriginalItemCalculatedDiscounts(new \ArrayObject());
            $discountableObjects[] = $discountableItems;
        }

        return $discountableObjects;
    }

    /**
     * @param int $discountAmount
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    protected function createDiscountTransfer($discountAmount)
    {
        $discountTransfer =  new DiscountTransfer();
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

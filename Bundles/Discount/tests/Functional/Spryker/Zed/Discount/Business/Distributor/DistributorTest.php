<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Discount\Business\Distributor;

use ArrayObject;
use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CollectedDiscountTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\Discount\Business\DiscountFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Discount
 * @group Business
 * @group Distributor
 * @group DistributorTest
 */
class DistributorTest extends Test
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

        $this->assertEquals($items[0]->getUnitGrossPrice(), current($items[0]->getOriginalItemCalculatedDiscounts())->getUnitGrossAmount());
        $this->assertEquals($items[1]->getUnitGrossPrice(), current($items[1]->getOriginalItemCalculatedDiscounts())->getUnitGrossAmount());
        $this->assertEquals($items[2]->getUnitGrossPrice(), current($items[2]->getOriginalItemCalculatedDiscounts())->getUnitGrossAmount());
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

        $this->assertEquals(self::DISCOUNT_AMOUNT_300 / 3, current($items[0]->getOriginalItemCalculatedDiscounts())->getUnitGrossAmount());
        $this->assertEquals(self::DISCOUNT_AMOUNT_300 / 3, current($items[1]->getOriginalItemCalculatedDiscounts())->getUnitGrossAmount());
        $this->assertEquals(self::DISCOUNT_AMOUNT_300 / 3, current($items[2]->getOriginalItemCalculatedDiscounts())->getUnitGrossAmount());
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

        $this->assertEquals(self::DISCOUNT_AMOUNT_13333, current($items[0]->getOriginalItemCalculatedDiscounts())->getUnitGrossAmount());
        $this->assertEquals(self::DISCOUNT_AMOUNT_13334, current($items[1]->getOriginalItemCalculatedDiscounts())->getUnitGrossAmount());
        $this->assertEquals(self::DISCOUNT_AMOUNT_13333, current($items[2]->getOriginalItemCalculatedDiscounts())->getUnitGrossAmount());
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

        $this->assertEquals(self::DISCOUNT_AMOUNT_100, current($items[0]->getOriginalItemCalculatedDiscounts())->getUnitGrossAmount());
        $this->assertEquals(self::DISCOUNT_AMOUNT_200, current($items[1]->getOriginalItemCalculatedDiscounts())->getUnitGrossAmount());
        $this->assertEquals(self::DISCOUNT_AMOUNT_400, current($items[2]->getOriginalItemCalculatedDiscounts())->getUnitGrossAmount());
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
            $discountableItemTransfer->setUnitGrossPrice($grossPrice);
            $discountableItemTransfer->setQuantity(1);
            $discountableItemTransfer->setOriginalItemCalculatedDiscounts(new ArrayObject());
            $items->append($discountableItemTransfer);
        }

        return $items;
    }

}

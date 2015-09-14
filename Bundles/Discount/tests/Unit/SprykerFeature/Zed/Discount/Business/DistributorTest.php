<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Discount\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Zed\Discount\Business\DiscountFacade;
use SprykerEngine\Zed\Kernel\Locator;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Discount
 * @group Business
 * @group Distributor
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
    const DISCOUNT_AMOUNT_13333 = 133.33;
    const DISCOUNT_AMOUNT_13334 = 133.34;
    const DISCOUNT_AMOUNT_NEGATIVE = -100;

    /**
     * @var DiscountFacade
     */
    protected $discountFacade;

    /**
     * @var Locator|AutoCompletion
     */
    protected $locator;

    protected function setUp()
    {
        parent::setUp();

        $this->locator = Locator::getInstance();
        $this->discountFacade = $this->locator->discount()->facade();
    }

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

        $this->discountFacade->distributeAmount($items, $discountTransfer);

        $this->assertEquals($items[0]->getGrossPrice(), current($items[0]->getDiscounts())->getAmount());
        $this->assertEquals($items[1]->getGrossPrice(), current($items[1]->getDiscounts())->getAmount());
        $this->assertEquals($items[2]->getGrossPrice(), current($items[2]->getDiscounts())->getAmount());
    }

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

        $this->discountFacade->distributeAmount($items, $discountTransfer);

        $this->assertEquals(self::DISCOUNT_AMOUNT_300 / 3, current($items[0]->getDiscounts())->getAmount());
        $this->assertEquals(self::DISCOUNT_AMOUNT_300 / 3, current($items[1]->getDiscounts())->getAmount());
        $this->assertEquals(self::DISCOUNT_AMOUNT_300 / 3, current($items[2]->getDiscounts())->getAmount());
    }

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

        $this->discountFacade->distributeAmount($items, $discountTransfer);

        $this->assertEquals(self::DISCOUNT_AMOUNT_13333, current($items[0]->getDiscounts())->getAmount());
        $this->assertEquals(self::DISCOUNT_AMOUNT_13334, current($items[1]->getDiscounts())->getAmount());
        $this->assertEquals(self::DISCOUNT_AMOUNT_13333, current($items[2]->getDiscounts())->getAmount());
    }

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

        $this->discountFacade->distributeAmount($items, $discountTransfer);

        $this->assertEquals(self::DISCOUNT_AMOUNT_100, current($items[0]->getDiscounts())->getAmount());
        $this->assertEquals(self::DISCOUNT_AMOUNT_200, current($items[1]->getDiscounts())->getAmount());
        $this->assertEquals(self::DISCOUNT_AMOUNT_400, current($items[2]->getDiscounts())->getAmount());
    }

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

        $this->discountFacade->distributeAmount($items, $discountTransfer);

        $this->assertEquals(0, $items[0]->getDiscounts()->count());
        $this->assertEquals(0, $items[1]->getDiscounts()->count());
        $this->assertEquals(0, $items[2]->getDiscounts()->count());
    }

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

        $this->discountFacade->distributeAmount($items, $discountTransfer);

        $this->assertEquals(0, $items[0]->getDiscounts()->count());
        $this->assertEquals(0, $items[1]->getDiscounts()->count());
        $this->assertEquals(0, $items[2]->getDiscounts()->count());
    }

    /**
     * @param array $grossPrices
     *
     * @return array|ItemTransfer[]
     */
    protected function getItems(array $grossPrices)
    {
        $items = [];

        foreach ($grossPrices as $grossPrice) {
            $item = new ItemTransfer();
            $item->setGrossPrice($grossPrice);
            $items[] = $item;
        }

        return $items;
    }

}

<?php

namespace Unit\SprykerFeature\Zed\Discount\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\SalesOrderItemTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Zed\Discount\Business\DiscountFacade;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Shared\Sales\Transfer\OrderItem;

/**
 * Class DistributorTest
 * @group DiscountDistributorTest
 * @group Discount
 * @package Unit\SprykerFeature\Zed\Discount\Business
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

        $this->discountFacade->distributeAmount($items, self::DISCOUNT_AMOUNT_4000);
        $this->assertEquals($items[0]->getGrossPrice(), $items[0]->getDiscounts()[-1]->getAmount());
        $this->assertEquals($items[1]->getGrossPrice(), $items[1]->getDiscounts()[-1]->getAmount());
        $this->assertEquals($items[2]->getGrossPrice(), $items[2]->getDiscounts()[-1]->getAmount());
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

        $this->discountFacade->distributeAmount($items, self::DISCOUNT_AMOUNT_300);
        $this->assertEquals(self::DISCOUNT_AMOUNT_300 / 3, $items[0]->getDiscounts()[-1]->getAmount());
        $this->assertEquals(self::DISCOUNT_AMOUNT_300 / 3, $items[1]->getDiscounts()[-1]->getAmount());
        $this->assertEquals(self::DISCOUNT_AMOUNT_300 / 3, $items[2]->getDiscounts()[-1]->getAmount());
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

        $this->discountFacade->distributeAmount($items, self::DISCOUNT_AMOUNT_400);
        $this->assertEquals(self::DISCOUNT_AMOUNT_13333, $items[0]->getDiscounts()[-1]->getAmount());
        $this->assertEquals(self::DISCOUNT_AMOUNT_13334, $items[1]->getDiscounts()[-1]->getAmount());
        $this->assertEquals(self::DISCOUNT_AMOUNT_13333, $items[2]->getDiscounts()[-1]->getAmount());
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

        $this->discountFacade->distributeAmount($items, self::DISCOUNT_AMOUNT_700);
        $this->assertEquals(self::DISCOUNT_AMOUNT_100, $items[0]->getDiscounts()[-1]->getAmount());
        $this->assertEquals(self::DISCOUNT_AMOUNT_200, $items[1]->getDiscounts()[-1]->getAmount());
        $this->assertEquals(self::DISCOUNT_AMOUNT_400, $items[2]->getDiscounts()[-1]->getAmount());
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

        $this->discountFacade->distributeAmount($items, self::DISCOUNT_AMOUNT_NEGATIVE);

//        $this->assertEquals(0, $items[0]->getDiscounts()->count());
//        $this->assertEquals(0, $items[1]->getDiscounts()->count());
//        $this->assertEquals(0, $items[2]->getDiscounts()->count());
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

        $this->discountFacade->distributeAmount($items, self::DISCOUNT_AMOUNT_100);

//        $this->assertEquals(0, $items[0]->getDiscounts()->count());
//        $this->assertEquals(0, $items[1]->getDiscounts()->count());
//        $this->assertEquals(0, $items[2]->getDiscounts()->count());
    }

    /**
     * @param array $grossPrices
     * @return array|SalesOrderItemTransfer[]
     */
    protected function getItems(array $grossPrices)
    {
        $items = [];

        foreach ($grossPrices as $grossPrice) {
            $item = new SalesOrderItemTransfer();
            $item->setGrossPrice($grossPrice);
            $items[] = $item;
        }

        return $items;
    }
}

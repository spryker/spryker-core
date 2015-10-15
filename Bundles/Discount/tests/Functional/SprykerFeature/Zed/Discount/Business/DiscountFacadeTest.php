<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Discount\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\VoucherPoolCategoryTransfer;
use Generated\Shared\Transfer\VoucherPoolTransfer;
use Generated\Shared\Transfer\VoucherTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\AbstractLocatorLocator;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderItemsTransfer;
use SprykerFeature\Zed\Discount\Communication\Plugin\Calculator\Fixed;
use SprykerFeature\Zed\Discount\Communication\Plugin\Calculator\Percentage;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Discount\Business\DiscountFacade;
use SprykerFeature\Zed\Discount\DiscountConfig;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountCollector;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRule;
use SprykerFeature\Zed\Sales\Business\Model\CalculableContainer;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Discount
 * @group Business
 */
class DiscountFacadeTest extends Test
{

    const VOUCHER_CODE_TEST_1 = 'TEST-CODE-1';
    const VOUCHER_CODE_TEST_2 = 'TEST-CODE-2';
    const VOUCHER_CODE_TEST_3 = 'TEST-CODE-3';
    const VOUCHER_CODE_TEST_4 = 'TEST-CODE-4';
    const VOUCHER_CODE_TEST_5 = 'TEST-CODE-5';
    const VOUCHER_CODE_TEST_6 = 'TEST-CODE-6';
    const VOUCHER_POOL_NAME = 'TEST POOL';
    const MINIMUM_CART_AMOUNT_1000 = 1000;
    const DECISION_RULE_MINIMUM_CART_SUBTOTAL_AMOUNT = 'Minimum Cart Subtotal Amount';
    const DISCOUNT_TYPE_FIXED = 'fixed';
    const DISCOUNT_NAME_MINIMUM_CART_SUBTOTAL = 'Minimum Cart Subtotal';
    const DISCOUNT_AMOUNT_PERCENTAGE_50 = 50;
    const ITEM_GROSS_PRICE = 1000;
    const EXPENSE_GROSS_PRICE = self::DECISION_RULE_VALUE_500;
    const DISCOUNT_AMOUNT_4000 = 4000;
    const DISCOUNT_AMOUNT_FIXED_100 = self::DISCOUNT_AMOUNT_100;
    const TEST_VOUCHER_CODE = self::DISCOUNT_VOUCHER_CODE;
    const AMOUNT_OF_VOUCHERS_TO_CREATE_10 = 10;
    const DISCOUNT_DISPLAY_NAME = 'discount-display-name';
    const DISCOUNT_COLLECTOR_PLUGIN = 'discount-collector-plugin';
    const DISCOUNT_AMOUNT_100 = 100;
    const DECISION_RULE_VALUE_500 = 500;
    const DECISION_RULE_PLUGIN = 'decision-rule-plugin';
    const DECISION_RULE_NAME = 'decision-rule-name';
    const DISCOUNT_VOUCHER_CODE = 'test-voucher-code';
    const DISCOUNT_VOUCHER_POOL_NAME = 'discount-voucher-pool-name';
    const DISCOUNT_VOUCHER_POOL_CATEGORY = 'discount-voucher-pool-category';

    /**
     * @var DiscountFacade
     */
    protected $discountFacade;

    /**
     * @var AutoCompletion
     */
    protected $locator;

    protected function setUp()
    {
        parent::setUp();

        $this->locator = Locator::getInstance();
        $this->discountFacade = $this->locator->discount()->facade();
    }

    public function testIsVoucherUsable()
    {
        $voucherPool = $this->initializeDatabaseWithTestVoucher(self::VOUCHER_CODE_TEST_1);
        $result = $this->discountFacade->isVoucherUsable(self::VOUCHER_CODE_TEST_1);
        $this->assertTrue($result->isSuccess());
    }

    public function testIsVoucherUsableForInactivePool()
    {
        $voucherPool = $this->initializeDatabaseWithTestVoucher(self::VOUCHER_CODE_TEST_2, true, false);
        $result = $this->discountFacade->isVoucherUsable(self::VOUCHER_CODE_TEST_2);
        $this->assertFalse($result->isSuccess());
    }

    public function testIsVoucherUsableForInactiveVoucher()
    {
        $voucherPool = $this->initializeDatabaseWithTestVoucher(self::VOUCHER_CODE_TEST_3, false, true);
        $result = $this->discountFacade->isVoucherUsable(self::VOUCHER_CODE_TEST_3);
        $this->assertFalse($result->isSuccess());
    }

    public function testIsVoucherUsableForInactiveVoucherAndInactivePool()
    {
        $voucherPool = $this->initializeDatabaseWithTestVoucher(self::VOUCHER_CODE_TEST_4, false, false);
        $result = $this->discountFacade->isVoucherUsable(self::VOUCHER_CODE_TEST_4);
        $this->assertFalse($result->isSuccess());
    }

    public function testIsVoucherUsableForNonExistingVoucher()
    {
        $voucherPool = $this->initializeDatabaseWithTestVoucher(self::VOUCHER_CODE_TEST_5, true, true, false);
        $result = $this->discountFacade->isVoucherUsable(self::VOUCHER_CODE_TEST_5);
        $this->assertFalse($result->isSuccess());
    }

    public function testCalculateDiscounts()
    {
        $order = $this->getOrderWithFixtureData();
        $this->discountFacade->calculateDiscounts($order);
    }

    public function testCalculateDiscountsWithOneActiveDiscountAndPercentageDiscount()
    {
        $voucherPool = $this->initializeDatabaseWithTestVoucher(self::VOUCHER_CODE_TEST_6);
        $discount = $this->initializeDiscount(
            'TEST-DISCOUNT',
            DiscountConfig::PLUGIN_CALCULATOR_PERCENTAGE,
            self::DISCOUNT_AMOUNT_PERCENTAGE_50,
            true,
            DiscountConfig::PLUGIN_COLLECTOR_ITEM
        );

        $discount->setVoucherPool($voucherPool);
        $discount->save();

        $order = $this->getOrderWithFixtureData();
        $order->getCalculableObject()->setCouponCodes([self::VOUCHER_CODE_TEST_6]);

        $result = $this->discountFacade->calculateDiscounts($order);
        $this->assertGreaterThan(0, count($result));
    }

    public function testIsMinimumCartSubtotalReachedWithPercentageDiscount()
    {
        $discount = $this->initializeDiscount(
            self::DISCOUNT_NAME_MINIMUM_CART_SUBTOTAL,
            DiscountConfig::PLUGIN_CALCULATOR_PERCENTAGE,
            self::DISCOUNT_AMOUNT_PERCENTAGE_50,
            true,
            DiscountConfig::PLUGIN_COLLECTOR_ITEM
        );

        $decisionRule = new SpyDiscountDecisionRule();
        $decisionRule
            ->setName(self::DECISION_RULE_MINIMUM_CART_SUBTOTAL_AMOUNT)
            ->setValue(self::MINIMUM_CART_AMOUNT_1000)
            ->setDiscount($discount)
            ->setDecisionRulePlugin(DiscountConfig::PLUGIN_DECISION_RULE_VOUCHER)
            ->save();

        $order = $this->getOrderWithFixtureData();
        $order->getCalculableObject()->setTotals(new TotalsTransfer());

        $result = $this->discountFacade->isMinimumCartSubtotalReached($order, $decisionRule);
        $this->assertFalse($result->isSuccess());

        $order->getCalculableObject()->getTotals()->setSubtotalWithoutItemExpenses(self::MINIMUM_CART_AMOUNT_1000);
        $result = $this->discountFacade->isMinimumCartSubtotalReached($order, $decisionRule);
        $this->assertTrue($result->isSuccess());

        $order->getCalculableObject()->getTotals()->setSubtotalWithoutItemExpenses(self::MINIMUM_CART_AMOUNT_1000 - 1);
        $result = $this->discountFacade->isMinimumCartSubtotalReached($order, $decisionRule);
        $this->assertFalse($result->isSuccess());
    }

    public function testCalculatePercentage()
    {
        $items = $this->getItems(
            [
                self::ITEM_GROSS_PRICE,
                self::ITEM_GROSS_PRICE,
                self::ITEM_GROSS_PRICE,
            ]
        );

        $discountAmount = $this->discountFacade->calculatePercentage($items, self::DISCOUNT_AMOUNT_PERCENTAGE_50);

        $this->assertEquals((self::ITEM_GROSS_PRICE * 3) / 2, $discountAmount);
    }

    public function testCalculateFixed()
    {
        $items = $this->getItems(
            [
                self::ITEM_GROSS_PRICE,
                self::ITEM_GROSS_PRICE,
                self::ITEM_GROSS_PRICE,
            ]
        );

        $discountAmount = $this->discountFacade->calculateFixed($items, self::DISCOUNT_AMOUNT_FIXED_100);

        $this->assertEquals(self::DISCOUNT_AMOUNT_FIXED_100, $discountAmount);
    }

    public function testDistributeAmountLimitTheDiscountAmountToTheObjectGrossPrice()
    {
        $items = $this->getItems(
            [
                self::ITEM_GROSS_PRICE,
                self::ITEM_GROSS_PRICE,
                self::ITEM_GROSS_PRICE,
            ]
        );

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setAmount(self::DISCOUNT_AMOUNT_4000);

        $this->discountFacade->distributeAmount($items, $discountTransfer);

        $this->assertEquals($items[0]->getGrossPrice(), current($items[0]->getDiscounts())->getAmount());
        $this->assertEquals($items[1]->getGrossPrice(), current($items[1]->getDiscounts())->getAmount());
        $this->assertEquals($items[2]->getGrossPrice(), current($items[2]->getDiscounts())->getAmount());
    }

    public function testShouldCreateOneVoucherCode()
    {
        $voucherPoolEntity = (new \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPool())
            ->setName(self::VOUCHER_POOL_NAME);
        $voucherPoolEntity->save();

        $voucherCreateTransfer = new VoucherTransfer();
        $voucherCreateTransfer->setCode(self::TEST_VOUCHER_CODE);
        $voucherCreateTransfer->setFkDiscountVoucherPool($voucherPoolEntity->getPrimaryKey());

        $this->discountFacade->createVoucherCode($voucherCreateTransfer);

        $voucherEntity = (new \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherQuery())
            ->findOneByCode(self::TEST_VOUCHER_CODE);

        $this->assertNotNull($voucherEntity);
        $voucherEntity->delete();
        $voucherPoolEntity->delete();
    }

    public function testShouldCreateMultipleVouchersForOneVoucherPoolWithTemplate()
    {
        $voucherPoolEntity = (new \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPool())
            ->setName(self::VOUCHER_POOL_NAME);
        $voucherPoolEntity->save();

        $voucherCreateTransfer = new VoucherTransfer();
        $voucherCreateTransfer->setQuantity(self::AMOUNT_OF_VOUCHERS_TO_CREATE_10);
        $voucherCreateTransfer->setFkDiscountVoucherPool($voucherPoolEntity->getPrimaryKey());
        $voucherCreateTransfer->setCodeLength(10);
        $this->discountFacade->createVoucherCodes($voucherCreateTransfer);

        $voucherEntities = (new \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherQuery())
            ->filterByFkDiscountVoucherPool($voucherPoolEntity->getPrimaryKey())->find();

        $this->assertEquals(self::AMOUNT_OF_VOUCHERS_TO_CREATE_10, $voucherEntities->count());

        (new \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherQuery())->deleteAll();
        $voucherPoolEntity->delete();
    }

    public function testSaveDiscount()
    {
        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setDisplayName(self::DISCOUNT_DISPLAY_NAME);
        $discountTransfer->setAmount(self::DISCOUNT_AMOUNT_100);
        $result = $this->discountFacade->createDiscount($discountTransfer);

        $this->assertInstanceOf('SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount', $result);
    }

    public function testSaveDiscountDecisionRule()
    {
        $discountDecisionRuleTransfer = new \Generated\Shared\Transfer\DecisionRuleTransfer();
        $discountDecisionRuleTransfer->setName(self::DECISION_RULE_NAME);
        $discountDecisionRuleTransfer->setDecisionRulePlugin(self::DECISION_RULE_PLUGIN);
        $discountDecisionRuleTransfer->setValue(self::DECISION_RULE_VALUE_500);
        $result = $this->discountFacade->createDiscountDecisionRule($discountDecisionRuleTransfer);

        $this->assertInstanceOf('SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRule', $result);
    }

    public function testSaveDiscountVoucher()
    {
        $discountVoucherTransfer = new \Generated\Shared\Transfer\VoucherTransfer();
        $discountVoucherTransfer->setCode(self::DISCOUNT_VOUCHER_CODE);
        $result = $this->discountFacade->createDiscountVoucher($discountVoucherTransfer);

        $this->assertInstanceOf('SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucher', $result);
    }

    public function testSaveDiscountVoucherPool()
    {
        $discountVoucherPoolTransfer = new VoucherPoolTransfer();
        $discountVoucherPoolTransfer->setName(self::DISCOUNT_VOUCHER_POOL_NAME);
        $result = $this->discountFacade->createDiscountVoucherPool($discountVoucherPoolTransfer);

        $this->assertInstanceOf('SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPool', $result);
    }

    public function testSaveDiscountVoucherPoolCategory()
    {
        $discountVoucherPoolCategoryTransfer = new VoucherPoolCategoryTransfer();
        $discountVoucherPoolCategoryTransfer->setName(self::DISCOUNT_VOUCHER_POOL_CATEGORY);
        $result = $this->discountFacade->createDiscountVoucherPoolCategory($discountVoucherPoolCategoryTransfer);

        $this->assertInstanceOf('SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolCategory', $result);
    }

    public function testGetDecisionRulePluginNames()
    {
        $decisionRulePluginNames = $this->discountFacade->getDecisionRulePluginNames();

        $this->assertGreaterThanOrEqual(0, count($decisionRulePluginNames));
    }

    public function testGetCalculatorPluginByName()
    {
        $plugin = $this->discountFacade
            ->getCalculatorPluginByName(DiscountConfig::PLUGIN_CALCULATOR_FIXED);

        $this->assertEquals($plugin->getMinValue(), Fixed::MIN_VALUE);

        $plugin = $this->discountFacade
            ->getCalculatorPluginByName(DiscountConfig::PLUGIN_CALCULATOR_PERCENTAGE);

        $this->assertEquals($plugin->getMinValue(), Percentage::MIN_VALUE);
        $this->assertEquals($plugin->getMaxValue(), Percentage::MAX_VALUE);
    }

    public function testGetDiscountableItems()
    {
        $order = $this->getOrderWithFixtureData();

        $item = new ItemTransfer();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);
        $order->getCalculableObject()->addItem($item);

        $result = $this->discountFacade->getDiscountableItems($order, new DiscountCollectorTransfer());
        $this->assertEquals(1, count($result));
    }

    public function testGetDiscountableItemExpenses()
    {
        $order = $this->getOrderWithFixtureData();

        $item = new ItemTransfer();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);

        $expense = new ExpenseTransfer();
        $expense->setGrossPrice(self::EXPENSE_GROSS_PRICE);

        $item->addExpense($expense);
        $order->getCalculableObject()->addItem($item);

        $result = $this->discountFacade->getDiscountableItemExpenses($order, new DiscountCollectorTransfer());
        $this->assertEquals(1, count($result));
    }

    public function testGetDiscountableOrderExpenses()
    {
        $order = $this->getOrderWithFixtureData();

        $expense = new ExpenseTransfer();
        $expense->setGrossPrice(self::EXPENSE_GROSS_PRICE);
        $order->getCalculableObject()->addExpense($expense);

        $itemCollection = new OrderItemsTransfer();
        $item = new ItemTransfer();
        $item->setGrossPrice(self::ITEM_GROSS_PRICE);

        $expense = new ExpenseTransfer();
        $expense->setGrossPrice(self::EXPENSE_GROSS_PRICE);

        $item->addExpense($expense);
        $itemCollection->addOrderItem($item);
        $order->getCalculableObject()->setItems($itemCollection);

        $result = $this->discountFacade->getDiscountableOrderExpenses($order, new DiscountCollectorTransfer());
        $this->assertEquals(1, count($result));
    }

    public function testUseVoucherCodesWhenCounterPresentShouldIncreaseNumberOfUses()
    {
        $voucherPoolEntity = $this->initializeDatabaseWithTestVoucher(
            self::VOUCHER_CODE_TEST_6,
            true,
            true,
            true,
            5,
            0
        );

        $this->discountFacade->useVoucherCodes([self::VOUCHER_CODE_TEST_6]);
        $discountVoucherEntity = $voucherPoolEntity->getDiscountVouchers()[0];
        $this->assertEquals(1, $discountVoucherEntity->getNumberOfUses());
    }

    public function testReleaseUsedCodesWhenCounterPresentShouldDecreaseNumberOfUses()
    {
        $voucherPoolEntity = $this->initializeDatabaseWithTestVoucher(
            self::VOUCHER_CODE_TEST_6,
            true,
            true,
            true,
            5,
            1
        );

        $this->discountFacade->releaseUsedVoucherCodes([self::VOUCHER_CODE_TEST_6]);
        $discountVoucherEntity = $voucherPoolEntity->getDiscountVouchers()[0];
        $this->assertEquals(0, $discountVoucherEntity->getNumberOfUses());
    }

    public function testValidateVoucherWhenVoucherUsedLimitThenItShouldFail()
    {
        $this->initializeDatabaseWithTestVoucher(
            self::VOUCHER_CODE_TEST_6,
            true,
            true,
            true,
            5,
            5
        );

        $validationErrors = $this->discountFacade->isVoucherUsable([self::VOUCHER_CODE_TEST_6]);

        $this->assertCount(1, $validationErrors->getErrors());
    }

    /**
     * @param string $displayName
     * @param string $type
     * @param int $amount
     * @param bool $isActive
     * @param string $collectorPlugin
     *
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount
     */
    protected function initializeDiscount($displayName, $type, $amount, $isActive, $collectorPlugin)
    {
        $discount = new \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount();
        $discount->setDisplayName($displayName);
        $discount->setAmount($amount);
        $discount->setIsActive($isActive);
        $discount->setCalculatorPlugin($type);
        $discountCollectorEntity = new SpyDiscountCollector();
        $discountCollectorEntity->setCollectorPlugin($collectorPlugin);
        $discount->addDiscountCollector($discountCollectorEntity);
        $discount->save();

        return $discount;
    }

    /**
     * @param string $code
     * @param bool $voucherIsActive
     * @param bool $voucherPoolIsActive
     * @param bool $createVoucher
     *
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPool
     */
    protected function initializeDatabaseWithTestVoucher(
        $code,
        $voucherIsActive = true,
        $voucherPoolIsActive = true,
        $createVoucher = true,
        $maxNumberOfUses = null,
        $numberOfUses = null
    ) {
        $voucherPool = new \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPool();
        $voucherPool->setIsActive($voucherPoolIsActive);
        $voucherPool->setName(self::VOUCHER_POOL_NAME);
        $voucherPool->save();

        if ($createVoucher) {
            $voucher = new \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucher();
            $voucher->setCode($code);
            $voucher->setMaxNumberOfUses($maxNumberOfUses);
            $voucher->setNumberOfUses($numberOfUses);
            $voucher->setIsActive($voucherIsActive);
            $voucher->setVoucherPool($voucherPool);
            $voucher->save();
        }

        return $voucherPool;
    }

    /**
     * @return CalculableContainer
     */
    protected function getOrderWithFixtureData()
    {
        $order = new OrderTransfer();

        return new CalculableContainer($order);
    }

    /**
     * @param array $grossPrices
     *
     * @return ItemTransfer[]
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

    /**
     * @return AbstractLocatorLocator|AutoCompletion
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }

}

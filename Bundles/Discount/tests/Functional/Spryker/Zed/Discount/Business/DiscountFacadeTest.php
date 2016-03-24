<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Discount\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\DecisionRuleTransfer;
use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Generated\Shared\Transfer\VoucherPoolCategoryTransfer;
use Generated\Shared\Transfer\VoucherPoolTransfer;
use Generated\Shared\Transfer\VoucherTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Orm\Zed\Discount\Persistence\SpyDiscountCollector;
use Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucher;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Kernel\Locator;

/**
 * @group Spryker
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
     * @var \Spryker\Zed\Discount\Business\DiscountFacade
     */
    protected $discountFacade;

    /**
     * @var \Generated\Zed\Ide\AutoCompletion
     */
    protected $locator;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->locator = Locator::getInstance();

        $this->discountFacade = $this->locator->discount()->facade();
    }

    /**
     * @return void
     */
    public function testIsVoucherUsable()
    {
        $this->initializeDatabaseWithTestVoucher(self::VOUCHER_CODE_TEST_1);
        $result = $this->discountFacade->isVoucherUsable(self::VOUCHER_CODE_TEST_1);
        $this->assertTrue($result->isSuccess());
    }

    /**
     * @return void
     */
    public function testIsVoucherUsableForInactivePool()
    {
        $this->initializeDatabaseWithTestVoucher(self::VOUCHER_CODE_TEST_2, true, false);
        $result = $this->discountFacade->isVoucherUsable(self::VOUCHER_CODE_TEST_2);
        $this->assertFalse($result->isSuccess());
    }

    /**
     * @return void
     */
    public function testIsVoucherUsableForInactiveVoucher()
    {
        $this->initializeDatabaseWithTestVoucher(self::VOUCHER_CODE_TEST_3, false, true);
        $result = $this->discountFacade->isVoucherUsable(self::VOUCHER_CODE_TEST_3);
        $this->assertFalse($result->isSuccess());
    }

    /**
     * @return void
     */
    public function testIsVoucherUsableForInactiveVoucherAndInactivePool()
    {
        $this->initializeDatabaseWithTestVoucher(self::VOUCHER_CODE_TEST_4, false, false);
        $result = $this->discountFacade->isVoucherUsable(self::VOUCHER_CODE_TEST_4);
        $this->assertFalse($result->isSuccess());
    }

    /**
     * @return void
     */
    public function testIsVoucherUsableForNonExistingVoucher()
    {
        $this->initializeDatabaseWithTestVoucher(self::VOUCHER_CODE_TEST_5, true, true, false);
        $result = $this->discountFacade->isVoucherUsable(self::VOUCHER_CODE_TEST_5);
        $this->assertFalse($result->isSuccess());
    }

    /**
     * @return void
     */
    public function testCalculateDiscounts()
    {
        $order = $this->createQuoteTransfer();
        $result = $this->discountFacade->calculateDiscounts($order);
        $this->assertSame(['discounts', 'errors'], array_keys($result));
    }

    /**
     * @return void
     */
    public function testCalculateDiscountsWithOneActiveDiscountAndPercentageDiscount()
    {
        $voucherPool = $this->initializeDatabaseWithTestVoucher(self::VOUCHER_CODE_TEST_6);
        $discount = $this->initializeDiscount(
            'TEST-DISCOUNT',
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            self::DISCOUNT_AMOUNT_PERCENTAGE_50,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM
        );

        $discount->setVoucherPool($voucherPool);
        $discount->save();

        $quoteTransfer = $this->createQuoteTransfer();
        $voucherDiscountTransfer = new DiscountTransfer();
        $voucherDiscountTransfer->setVoucherCode(self::VOUCHER_CODE_TEST_6);
        $quoteTransfer->addVoucherDiscount($voucherDiscountTransfer);

        $result = $this->discountFacade->calculateDiscounts($quoteTransfer);
        $this->assertGreaterThan(0, count($result));
    }

    /**
     * @return void
     */
    public function testIsMinimumCartSubtotalReachedWithPercentageDiscount()
    {
        $discount = $this->initializeDiscount(
            self::DISCOUNT_NAME_MINIMUM_CART_SUBTOTAL,
            DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            self::DISCOUNT_AMOUNT_PERCENTAGE_50,
            true,
            DiscountDependencyProvider::PLUGIN_COLLECTOR_ITEM
        );

        $decisionRule = new SpyDiscountDecisionRule();
        $decisionRule
            ->setName(self::DECISION_RULE_MINIMUM_CART_SUBTOTAL_AMOUNT)
            ->setValue(self::MINIMUM_CART_AMOUNT_1000)
            ->setDiscount($discount)
            ->setDecisionRulePlugin(DiscountDependencyProvider::PLUGIN_DECISION_RULE_MINIMUM_CART_SUB_TOTAL)
            ->save();

        $quoteTransfer = $this->createQuoteTransfer();
        $quoteTransfer->setTotals(new TotalsTransfer());

        $result = $this->discountFacade->isMinimumCartSubtotalReached($quoteTransfer, $decisionRule);
        $this->assertFalse($result->isSuccess());

        $quoteTransfer->getTotals()->setSubtotal(self::MINIMUM_CART_AMOUNT_1000);
        $result = $this->discountFacade->isMinimumCartSubtotalReached($quoteTransfer, $decisionRule);
        $this->assertTrue($result->isSuccess());
    }

    /**
     * @return void
     */
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

    /**
     * @return void
     */
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

    /**
     * @return void
     */
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

        $this->assertEquals($items[0]->getUnitGrossPrice(), current($items[0]->getCalculatedDiscounts())->getUnitGrossAmount());
        $this->assertEquals($items[1]->getUnitGrossPrice(), current($items[1]->getCalculatedDiscounts())->getUnitGrossAmount());
        $this->assertEquals($items[2]->getUnitGrossPrice(), current($items[2]->getCalculatedDiscounts())->getUnitGrossAmount());
    }

    /**
     * @return void
     */
    public function testShouldCreateOneVoucherCode()
    {
        $voucherPoolEntity = (new SpyDiscountVoucherPool())
            ->setName(self::VOUCHER_POOL_NAME);
        $voucherPoolEntity->save();

        $voucherCreateTransfer = new VoucherTransfer();
        $voucherCreateTransfer->setCode(self::TEST_VOUCHER_CODE);
        $voucherCreateTransfer->setFkDiscountVoucherPool($voucherPoolEntity->getPrimaryKey());

        $this->discountFacade->createVoucherCode($voucherCreateTransfer);

        $voucherEntity = (new SpyDiscountVoucherQuery())
            ->findOneByCode(self::TEST_VOUCHER_CODE);

        $this->assertNotNull($voucherEntity);
        $voucherEntity->delete();
        $voucherPoolEntity->delete();
    }

    /**
     * @return void
     */
    public function testShouldCreateMultipleVouchersForOneVoucherPoolWithTemplate()
    {
        $voucherPoolEntity = (new SpyDiscountVoucherPool())
            ->setName(self::VOUCHER_POOL_NAME);
        $voucherPoolEntity->save();

        $voucherTransfer = new VoucherTransfer();
        $voucherTransfer->setQuantity(self::AMOUNT_OF_VOUCHERS_TO_CREATE_10);
        $voucherTransfer->setFkDiscountVoucherPool($voucherPoolEntity->getPrimaryKey());
        $voucherTransfer->setCustomCode('spryker-[code]');
        $voucherTransfer->setCodeLength(10);
        $this->discountFacade->createVoucherCodes($voucherTransfer);

        $voucherEntities = (new SpyDiscountVoucherQuery())
            ->filterByFkDiscountVoucherPool($voucherPoolEntity->getPrimaryKey())->find();

        $this->assertEquals(self::AMOUNT_OF_VOUCHERS_TO_CREATE_10, $voucherEntities->count());

        (new SpyDiscountVoucherQuery())->deleteAll();
        $voucherPoolEntity->delete();
    }

    /**
     * @return void
     */
    public function testSaveDiscount()
    {
        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setDisplayName(self::DISCOUNT_DISPLAY_NAME);
        $discountTransfer->setAmount(self::DISCOUNT_AMOUNT_100);
        $result = $this->discountFacade->createDiscount($discountTransfer);

        $this->assertInstanceOf('Orm\Zed\Discount\Persistence\SpyDiscount', $result);
    }

    /**
     * @return void
     */
    public function testSaveDiscountDecisionRule()
    {
        $discountDecisionRuleTransfer = new DecisionRuleTransfer();
        $discountDecisionRuleTransfer->setName(self::DECISION_RULE_NAME);
        $discountDecisionRuleTransfer->setDecisionRulePlugin(self::DECISION_RULE_PLUGIN);
        $discountDecisionRuleTransfer->setValue(self::DECISION_RULE_VALUE_500);
        $result = $this->discountFacade->createDiscountDecisionRule($discountDecisionRuleTransfer);

        $this->assertInstanceOf('Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule', $result);
    }

    /**
     * @return void
     */
    public function testSaveDiscountVoucher()
    {
        $discountVoucherTransfer = new VoucherTransfer();
        $discountVoucherTransfer->setCode(self::DISCOUNT_VOUCHER_CODE);
        $result = $this->discountFacade->createDiscountVoucher($discountVoucherTransfer);

        $this->assertInstanceOf('Orm\Zed\Discount\Persistence\SpyDiscountVoucher', $result);
    }

    /**
     * @return void
     */
    public function testSaveDiscountVoucherPool()
    {
        $discountVoucherPoolTransfer = new VoucherPoolTransfer();
        $discountVoucherPoolTransfer->setName(self::DISCOUNT_VOUCHER_POOL_NAME);
        $result = $this->discountFacade->createDiscountVoucherPool($discountVoucherPoolTransfer);

        $this->assertInstanceOf('Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool', $result);
    }

    /**
     * @return void
     */
    public function testSaveDiscountVoucherPoolCategory()
    {
        $discountVoucherPoolCategoryTransfer = new VoucherPoolCategoryTransfer();
        $discountVoucherPoolCategoryTransfer->setName(self::DISCOUNT_VOUCHER_POOL_CATEGORY);
        $result = $this->discountFacade->createDiscountVoucherPoolCategory($discountVoucherPoolCategoryTransfer);

        $this->assertInstanceOf('Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolCategory', $result);
    }

    /**
     * @return void
     */
    public function testGetDecisionRulePluginNames()
    {
        $decisionRulePluginNames = $this->discountFacade->getDecisionRulePluginNames();

        $this->assertGreaterThanOrEqual(0, count($decisionRulePluginNames));
    }

    /**
     * @return void
     */
    public function testGetDiscountableItems()
    {
        $quoteTransfer = $this->createQuoteTransfer();

        $item = new ItemTransfer();
        $item->setUnitGrossPrice(self::ITEM_GROSS_PRICE);
        $quoteTransfer->addItem($item);

        $result = $this->discountFacade->getDiscountableItems($quoteTransfer, new DiscountCollectorTransfer());
        $this->assertEquals(1, count($result));
    }

    /**
     * @return void
     */
    public function testGetDiscountableOrderExpenses()
    {
        $quoteTransfer = $this->createQuoteTransfer();

        $expense = new ExpenseTransfer();
        $expense->setUnitGrossPrice(self::EXPENSE_GROSS_PRICE);
        $quoteTransfer->addExpense($expense);

        $item = new ItemTransfer();
        $item->setUnitGrossPrice(self::ITEM_GROSS_PRICE);

        $quoteTransfer->addItem($item);

        $result = $this->discountFacade->getDiscountableOrderExpenses($quoteTransfer, new DiscountCollectorTransfer());
        $this->assertEquals(1, count($result));
    }

    /**
     * @return void
     */
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

    /**
     * @return void
     */
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

    /**
     * @return void
     */
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
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount
     */
    protected function initializeDiscount($displayName, $type, $amount, $isActive, $collectorPlugin)
    {
        $discount = new SpyDiscount();
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
     * @param int $maxNumberOfUses
     * @param int $numberOfUses
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool
     */
    protected function initializeDatabaseWithTestVoucher(
        $code,
        $voucherIsActive = true,
        $voucherPoolIsActive = true,
        $createVoucher = true,
        $maxNumberOfUses = null,
        $numberOfUses = null
    ) {
        $voucherPool = new SpyDiscountVoucherPool();
        $voucherPool->setIsActive($voucherPoolIsActive);
        $voucherPool->setName(self::VOUCHER_POOL_NAME);
        $voucherPool->save();

        if ($createVoucher) {
            $voucher = new SpyDiscountVoucher();
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
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setTotals(new TotalsTransfer());

        return $quoteTransfer;
    }

    /**
     * @param array $grossPrices
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getItems(array $grossPrices)
    {
        $items = [];

        foreach ($grossPrices as $grossPrice) {
            $item = new ItemTransfer();
            $item->setUnitGrossPrice($grossPrice);
            $item->setQuantity(1);
            $items[] = $item;
        }

        return $items;
    }

}

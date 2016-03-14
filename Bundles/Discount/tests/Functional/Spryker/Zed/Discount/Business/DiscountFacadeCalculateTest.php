<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Discount\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Discount\Persistence\Base\SpyDiscountQuery;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Orm\Zed\Discount\Persistence\SpyDiscountCollector;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucher;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool;
use Spryker\Zed\Discount\Business\DiscountFacade;
use Spryker\Zed\Discount\DiscountDependencyProvider;

class DiscountFacadeCalculateTest extends Test
{

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     */
    protected function setUp()
    {
        parent::setUp();

        //disable all development discounts to remove false negatives.
        $discounts = SpyDiscountQuery::create()->find();
        foreach ($discounts as $discountEntity) {
            $discountEntity->setIsActive(false);
            $discountEntity->save();
        }
    }

    /**
     * @return void
     */
    public function testWhenVoucherCodeWithFixedCalculatorUsedShouldSplitFixedAmountThroughDiscoutableItems()
    {
        $voucherCode = 'voucher-test-functional';

        $decisionRuleQueryString = ':subtotal > 300';

        $discountAmount = 50;
        $this->createDiscountDatabaseFixtures(
            $discountAmount,
            $calculatorPlugin = DiscountDependencyProvider::PLUGIN_CALCULATOR_FIXED,
            $collectorPluginNames = [DiscountDependencyProvider::PLUGIN_COLLECTOR_AGGREGATE],
            $decisionRuleQueryString,
            $voucherCode
        );

        $quoteTransfer = $this->createQuoteTransfer();

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setVoucherCode($voucherCode);
        $quoteTransfer->addVoucherDiscount($discountTransfer);

        $this->createDiscountFacade()->calculateDiscounts($quoteTransfer);

        $totalCalculatedDiscountAmount = $this->assertExpectedCalculatedUnitGrossAmount($quoteTransfer->getItems()[0], 15.15);

        $itemTransfer2 = $quoteTransfer->getItems()[1];
        $totalCalculatedDiscountAmount += $this->assertExpectedCalculatedUnitGrossAmount($itemTransfer2, 30.3);
        $totalCalculatedDiscountAmount += $this->assertExpectedCalculatedUnitGrossAmount($itemTransfer2->getProductOptions()[0], 1.52);
        $totalCalculatedDiscountAmount += $this->assertExpectedCalculatedUnitGrossAmount($itemTransfer2->getProductOptions()[1], 3.03);

        $this->assertEquals($discountAmount, $totalCalculatedDiscountAmount);

    }

    /**
     * @return void
     */
    public function testWhenVoucherCodeWithPercentageCalculatorUsedShouldCalculatedPercentageAmountForEachItem()
    {
        $voucherCode = 'voucher-test-functional';
        $percentageAmount = 25;
        $decisionRuleQueryString = ':subtotal > 300';
        $this->createDiscountDatabaseFixtures(
            $percentageAmount,
            $calculatorPlugin = DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            $collectorPluginNames = [DiscountDependencyProvider::PLUGIN_COLLECTOR_AGGREGATE],
            $decisionRuleQueryString,
            $voucherCode
        );

        $quoteTransfer = $this->createQuoteTransfer();

        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setVoucherCode($voucherCode);
        $quoteTransfer->addVoucherDiscount($discountTransfer);

        $this->createDiscountFacade()->calculateDiscounts($quoteTransfer);

        $totalCalculatedDiscountAmount = $this->assertExpectedCalculatedUnitGrossAmount($quoteTransfer->getItems()[0], 25);

        $itemTransfer2 = $quoteTransfer->getItems()[1];
        $totalCalculatedDiscountAmount += $this->assertExpectedCalculatedUnitGrossAmount($itemTransfer2, 50);
        $totalCalculatedDiscountAmount += $this->assertExpectedCalculatedUnitGrossAmount($itemTransfer2->getProductOptions()[0], 2.5);
        $totalCalculatedDiscountAmount += $this->assertExpectedCalculatedUnitGrossAmount($itemTransfer2->getProductOptions()[1], 5.0);

        $totalDiscountFromPercentage = 330 * $percentageAmount / 100;
        $this->assertEquals($totalDiscountFromPercentage, $totalCalculatedDiscountAmount);

    }

    /**
     * @return void
     */
    public function testWhenDecisionRuleWithFixedAmountIsUsedShouldSplitAmountThroughDiscountableItems()
    {
        $discountAmount = 50;
        $decisionRuleQueryString = ':subtotal > 300';
        $this->createDiscountDatabaseFixtures(
            $discountAmount,
            $calculatorPlugin = DiscountDependencyProvider::PLUGIN_CALCULATOR_FIXED,
            $collectorPluginNames = [DiscountDependencyProvider::PLUGIN_COLLECTOR_AGGREGATE],
            $decisionRuleQueryString
        );

        $quoteTransfer = $this->createQuoteTransfer();
        $this->createDiscountFacade()->calculateDiscounts($quoteTransfer);

        $totalCalculatedDiscountAmount = $this->assertExpectedCalculatedUnitGrossAmount($quoteTransfer->getItems()[0], 15.15);

        $itemTransfer2 = $quoteTransfer->getItems()[1];
        $totalCalculatedDiscountAmount += $this->assertExpectedCalculatedUnitGrossAmount($itemTransfer2, 30.3);
        $totalCalculatedDiscountAmount += $this->assertExpectedCalculatedUnitGrossAmount($itemTransfer2->getProductOptions()[0], 1.52);
        $totalCalculatedDiscountAmount += $this->assertExpectedCalculatedUnitGrossAmount($itemTransfer2->getProductOptions()[1], 3.03);

        $this->assertEquals($discountAmount, $totalCalculatedDiscountAmount);
    }

    /**
     * @return void
     */
    public function testWhenDecisionRuleWithPercentageAmountIsUsedShouldCalculatedDiscountPercentageAmountForEachItem()
    {
        $percentageAmount = 25;
        $decisionRuleQueryString = ':subtotal > 300 and :grandtotal > 500';
        $this->createDiscountDatabaseFixtures(
            $percentageAmount,
            $calculatorPlugin = DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE,
            $collectorPluginNames = [DiscountDependencyProvider::PLUGIN_COLLECTOR_AGGREGATE],
            $decisionRuleQueryString
        );

        $quoteTransfer = $this->createQuoteTransfer();
        $this->createDiscountFacade()->calculateDiscounts($quoteTransfer);

        $totalCalculatedDiscountAmount = $this->assertExpectedCalculatedUnitGrossAmount($quoteTransfer->getItems()[0], 25);

        $itemTransfer2 = $quoteTransfer->getItems()[1];
        $totalCalculatedDiscountAmount += $this->assertExpectedCalculatedUnitGrossAmount($itemTransfer2, 50);
        $totalCalculatedDiscountAmount += $this->assertExpectedCalculatedUnitGrossAmount($itemTransfer2->getProductOptions()[0], 2.5);
        $totalCalculatedDiscountAmount += $this->assertExpectedCalculatedUnitGrossAmount($itemTransfer2->getProductOptions()[1], 5.0);

        $totalDiscountFromPercentage = 330 * $percentageAmount / 100;
        $this->assertEquals($totalDiscountFromPercentage, $totalCalculatedDiscountAmount);
    }


    /**
     * @param \Generated\Shared\Transfer\ItemTransfer|\Generated\Shared\Transfer\ProductOptionTransfer $calculableItem
     * @param float $expectedUnitGrossAmount
     * @return float
     */
    protected function assertExpectedCalculatedUnitGrossAmount($calculableItem, $expectedUnitGrossAmount)
    {
        $unitGrossAmount = $calculableItem->getCalculatedDiscounts()[0]->getUnitGrossAmount();

        $this->assertEquals($expectedUnitGrossAmount, $unitGrossAmount);

        return $unitGrossAmount;
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DiscountFacade
     */
    protected function createDiscountFacade()
    {
        $discountFacade = new DiscountFacade();

        return $discountFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setUnitGrossPrice(100);

        $quoteTransfer->addItem($itemTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setUnitGrossPrice(200);

        $this->addProductOptionTransfer($itemTransfer, 10);
        $this->addProductOptionTransfer($itemTransfer, 20);

        $quoteTransfer->addItem($itemTransfer);

        $totalTransfer = new TotalsTransfer();
        $totalTransfer->setSubtotal(330);
        $totalTransfer->setGrandTotal(550);
        $quoteTransfer->setTotals($totalTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $unitGrossAmount
     *
     * @return void
     */
    protected function addProductOptionTransfer(ItemTransfer $itemTransfer, $unitGrossAmount)
    {
        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setUnitGrossPrice($unitGrossAmount);
        $itemTransfer->addProductOption($productOptionTransfer);
    }

    /**
     * @param int $amount
     * @param string $calculatorPluginName
     * @param array $collectorPluginNames
     * @param string $decisionRuleQueryString
     * @param string $voucherCode
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount
     */
    protected function createDiscountDatabaseFixtures(
        $amount,
        $calculatorPluginName,
        array $collectorPluginNames,
        $decisionRuleQueryString,
        $voucherCode = null
    ) {

        $discountVoucherPoolEntity = null;
        if (!empty($voucherCode)) {
            $discountVoucherPoolEntity = new SpyDiscountVoucherPool();
            $discountVoucherPoolEntity->setName('test-pool-functional');
            $discountVoucherPoolEntity->setIsActive(true);
            $discountVoucherPoolEntity->save();

            $discountVoucherEntity = new SpyDiscountVoucher();
            $discountVoucherEntity->setCode($voucherCode);
            $discountVoucherEntity->setIsActive(true);
            $discountVoucherEntity->setFkDiscountVoucherPool($discountVoucherPoolEntity->getIdDiscountVoucherPool());
            $discountVoucherEntity->save();
        }

        $discountEntity = new SpyDiscount();
        $discountEntity->setAmount($amount);
        $discountEntity->setDisplayName('Discount test');
        $discountEntity->setIsActive(1);
        $discountEntity->setValidFrom(new \DateTime('1985-07-01'));
        $discountEntity->setValidTo(new \DateTime('2050-07-01'));
        $discountEntity->setCollectorLogicalOperator('AND');
        $discountEntity->setDecisionRuleQueryString($decisionRuleQueryString);
        $discountEntity->setCalculatorPlugin($calculatorPluginName);
        if ($discountVoucherPoolEntity) {
            $discountEntity->setFkDiscountVoucherPool($discountVoucherPoolEntity->getIdDiscountVoucherPool());
        }
        $discountEntity->save();

        foreach ($collectorPluginNames as $collectorPluginName) {
            $collectorEntity = new SpyDiscountCollector();
            $collectorEntity->setCollectorPlugin($collectorPluginName);
            $collectorEntity->setFkDiscount($discountEntity->getIdDiscount());
            $collectorEntity->save();
        }

        $discountEntity->reload(true);
        $pool = $discountEntity->getVoucherPool();
        if ($pool) {
            $pool->getDiscountVouchers();
        }

        return $discountEntity;
    }

}

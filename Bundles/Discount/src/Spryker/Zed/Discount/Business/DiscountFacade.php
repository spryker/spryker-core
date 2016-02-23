<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business;

use Generated\Shared\Transfer\CartRuleTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\DecisionRuleTransfer;
use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\CartRuleTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\DecisionRuleTransfer;
use Generated\Shared\Transfer\RuleConditionTransfer;
use Generated\Shared\Transfer\VoucherCodesTransfer;
use Generated\Shared\Transfer\VoucherPoolCategoryTransfer;
use Generated\Shared\Transfer\VoucherPoolTransfer;
use Generated\Shared\Transfer\VoucherTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule as DecisionRule;
use Spryker\Zed\Discount\Dependency\Facade\DiscountFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountBusinessFactory getFactory()
 */
class DiscountFacade extends AbstractFacade implements DiscountFacadeInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount[]
     */
    public function calculateDiscounts(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createDiscount()->calculate($quoteTransfer);
    }

    /**
     * @api
     *
     * @param string $code
     *
     * @return \Spryker\Zed\Kernel\Business\ModelResult
     */
    public function isVoucherUsable($code)
    {
        return $this->getFactory()->getDecisionRuleVoucher()->isUsable($code);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule $decisionRule
     *
     * @return \Spryker\Zed\Kernel\Business\ModelResult
     */
    public function isMinimumCartSubtotalReached(QuoteTransfer $quoteTransfer, DecisionRule $decisionRule)
    {
        return $this->getFactory()
            ->getDecisionRuleMinimumCartSubtotal()
            ->isMinimumCartSubtotalReached($quoteTransfer, $decisionRule);
    }

    /**
     * @param \Generated\Shared\Transfer\RuleConditionTransfer $ruleConditionTransfer
     *
     * @return bool
     */
    public function isSubTotalDecisionRuleSatisfiedBy(RuleConditionTransfer $ruleConditionTransfer)
    {
        return $this->getFactory()->createSubTotalDecisionRuleRule()->isSatisfiedBy($ruleConditionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RuleConditionTransfer $ruleConditionTransfer
     *
     * @return bool
     */
    public function isGrandTotalDecisionRuleSatisfiedBy(RuleConditionTransfer $ruleConditionTransfer)
    {
        return $this->getFactory()->createGrandtotalDecisionRuleRule()->isSatisfiedBy($ruleConditionTransfer);
    }

    /**
     * @api
     *
     * @param \Spryker\Zed\Discount\Business\Model\DiscountableInterface[] $discountableObjects
     * @param float $percentage
     *
     * @return float
     */
    public function calculatePercentage(array $discountableObjects, $percentage)
    {
        return $this->getFactory()->createCalculatorPercentage()->calculate($discountableObjects, $percentage);
    }

    /**
     * @api
     *
     * @param \Spryker\Zed\Discount\Business\Model\DiscountableInterface[] $discountableObjects
     * @param float $amount
     *
     * @return float
     */
    public function calculateFixed(array $discountableObjects, $amount)
    {
        return $this->getFactory()->createCalculatorFixed()->calculate($discountableObjects, $amount);
    }

    /**
     * @api
     *
     * @param \Spryker\Zed\Discount\Business\Model\DiscountableInterface[] $discountableObjects
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return void
     */
    public function distributeAmount(array $discountableObjects, DiscountTransfer $discountTransfer)
    {
        $this->getFactory()->createDistributor()->distribute($discountableObjects, $discountTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\VoucherTransfer $voucherTransfer
     *
     * @return \Generated\Shared\Transfer\VoucherCreateInfoTransfer
     */
    public function createVoucherCodes(VoucherTransfer $voucherTransfer)
    {
        return $this->getFactory()->createVoucherEngine()->createVoucherCodes($voucherTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\VoucherTransfer $voucherTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    public function createVoucherCode(VoucherTransfer $voucherTransfer)
    {
        return $this->getFactory()->createVoucherEngine()->createVoucherCode($voucherTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\VoucherCodesTransfer $voucherCodesTransfer
     *
     * @return $this
     */
    public function saveVoucherCode(VoucherCodesTransfer $voucherCodesTransfer)
    {
        return $this->getFactory()->createVoucherCodesWriter()->saveVoucherCode($voucherCodesTransfer);
    }

    /**
     * @api
     *
     * @return array
     */
    public function getDecisionRulePluginNames()
    {
        return $this->getFactory()->getDecisionRulePlugins();
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount
     */
    public function createDiscount(DiscountTransfer $discountTransfer)
    {
        return $this->getFactory()->createDiscountWriter()->create($discountTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount
     */
    public function updateDiscount(DiscountTransfer $discountTransfer)
    {
        return $this->getFactory()->createDiscountWriter()->update($discountTransfer);
    }

    /**
     * @api
     *
     * @return array
     */
    public function getVoucherPoolCategories()
    {
        return $this->getFactory()
            ->createVoucherPoolCategory()
            ->getAvailableVoucherPoolCategories();
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\DecisionRuleTransfer $decisionRuleTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule
     */
    public function saveDiscountDecisionRule(DecisionRuleTransfer $decisionRuleTransfer)
    {
        return $this->getFactory()->createDiscountDecisionRuleWriter()->saveDiscountDecisionRule($decisionRuleTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CartRuleTransfer $cartRuleFormTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    public function saveCartRules(CartRuleTransfer $cartRuleFormTransfer)
    {
        return $this->getFactory()->createCartRule()->saveCartRule($cartRuleFormTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\DecisionRuleTransfer $discountDecisionRuleTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule
     */
    public function createDiscountDecisionRule(DecisionRuleTransfer $discountDecisionRuleTransfer)
    {
        return $this->getFactory()->createDiscountDecisionRuleWriter()->create($discountDecisionRuleTransfer);
    }

    /**
     * @api
     *
     * @param int $idDiscount
     *
     * @return array
     */
    public function getCurrentCartRulesDetailsByIdDiscount($idDiscount)
    {
        return $this->getFactory()
            ->createCartRule()
            ->getCurrentCartRulesDetailsByIdDiscount($idDiscount);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\DecisionRuleTransfer $discountDecisionRuleTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule
     */
    public function updateDiscountDecisionRule(DecisionRuleTransfer $discountDecisionRuleTransfer)
    {
        return $this->getFactory()->createDiscountDecisionRuleWriter()->update($discountDecisionRuleTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\VoucherTransfer $discountVoucherTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    public function createDiscountVoucher(VoucherTransfer $discountVoucherTransfer)
    {
        return $this->getFactory()->createDiscountVoucherWriter()->create($discountVoucherTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\VoucherTransfer $discountVoucherTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    public function updateDiscountVoucher(VoucherTransfer $discountVoucherTransfer)
    {
        return $this->getFactory()->createDiscountVoucherWriter()->update($discountVoucherTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\VoucherPoolTransfer $discountVoucherPoolTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    public function createDiscountVoucherPool(VoucherPoolTransfer $discountVoucherPoolTransfer)
    {
        return $this->getFactory()->createDiscountVoucherPoolWriter()->create($discountVoucherPoolTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\VoucherPoolTransfer $discountVoucherPoolTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    public function updateDiscountVoucherPool(VoucherPoolTransfer $discountVoucherPoolTransfer)
    {
        return $this->getFactory()->createDiscountVoucherPoolWriter()->update($discountVoucherPoolTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolCategory
     */
    public function createDiscountVoucherPoolCategory(VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer)
    {
        return $this->getFactory()->createDiscountVoucherPoolCategoryWriter()
            ->create($discountVoucherPoolCategoryTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolCategory
     */
    public function updateDiscountVoucherPoolCategory(VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer)
    {
        return $this->getFactory()->createDiscountVoucherPoolCategoryWriter()
            ->update($discountVoucherPoolCategoryTransfer);
    }

    /**
     * @api
     *
     * @param string $poolCategoryName
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolCategory
     */
    public function getOrCreateDiscountVoucherPoolCategoryByName($poolCategoryName)
    {
        return $this->getFactory()->createDiscountVoucherPoolCategoryWriter()
            ->getOrCreateByName($poolCategoryName);
    }

    /**
     * @api
     *
     * @param string $pluginName
     *
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface
     */
    public function getCalculatorPluginByName($pluginName)
    {
        return $this->getFactory()->getCalculatorPlugins()[$pluginName];
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return array
     */
    public function getDiscountableItems(
        QuoteTransfer $quoteTransfer,
        DiscountCollectorTransfer $discountCollectorTransfer
    ) {
        return $this
            ->getFactory()
            ->createItemCollector()
            ->collect($quoteTransfer, $discountCollectorTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function getDiscountableOrderExpenses(
        QuoteTransfer $quoteTransfer,
        DiscountCollectorTransfer $discountCollectorTransfer
    ) {
        return $this->getFactory()->createOrderExpenseCollector()
            ->collect($quoteTransfer, $discountCollectorTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function getDiscountableItemProductOptions(
        QuoteTransfer $quoteTransfer,
        DiscountCollectorTransfer $discountCollectorTransfer
    ) {
        return $this->getFactory()->createItemProductOptionCollector()
            ->collect($quoteTransfer, $discountCollectorTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function getDiscountableItemsFromCollectorAggregate(
        QuoteTransfer $quoteTransfer,
        DiscountCollectorTransfer $discountCollectorTransfer
    ) {
        return $this->getFactory()->createAggregateCollector()
            ->collect($quoteTransfer, $discountCollectorTransfer);
    }

    /**
     * @api
     *
     * @return array
     */
    public function getDiscountCollectors()
    {
        return array_keys($this->getFactory()->getCollectorPlugins());
    }

    /**
     * @api
     *
     * @return array
     */
    public function getDiscountCalculators()
    {
        return array_keys($this->getFactory()->getCalculatorPlugins());
    }

    /**
     * @api
     *
     * @param array|string[] $codes
     *
     * @return bool
     */
    public function releaseUsedVoucherCodes(array $codes)
    {
        return $this->getFactory()->createVoucherCode()->releaseUsedCodes($codes);
    }

    /**
     * @api
     *
     * @param array|string[] $codes
     *
     * @return bool
     */
    public function useVoucherCodes(array $codes)
    {
        return $this->getFactory()->createVoucherCode()->useCodes($codes);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrderDiscounts(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $this->getFactory()->createDiscountSaver()->saveDiscounts($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderTotalDiscountAmount(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createOrderDiscountTotalAmount()->aggregate($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderCalculatedDiscounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createSalesOrderTotalsAggregator()->aggregate($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateItemDiscounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createItemTotalOrderAggregator()->aggregate($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateGrandTotalWithDiscounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createSalesOrderGrandTotalAggregator()->aggregate($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderExpenseTaxWithDiscounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createOrderExpenseTaxWithDiscountsAggregator()->aggregate($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderExpensesWithDiscounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createOrderExpenseWithDiscountsAggregator()->aggregate($orderTransfer);
    }

}

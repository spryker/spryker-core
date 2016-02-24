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
use Generated\Shared\Transfer\VoucherCodesTransfer;
use Generated\Shared\Transfer\VoucherPoolCategoryTransfer;
use Generated\Shared\Transfer\VoucherPoolTransfer;
use Generated\Shared\Transfer\VoucherTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule as DecisionRule;


/**
 * @method \Spryker\Zed\Discount\Business\DiscountBusinessFactory getFactory()
 */
interface DiscountFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount[]
     */
    public function calculateDiscounts(QuoteTransfer $quoteTransfer);

    /**
     * @api
     *
     * @param string $code
     *
     * @return \Spryker\Zed\Kernel\Business\ModelResult
     */
    public function isVoucherUsable($code);

    /**
     * @api
     *
     * @param \Spryker\Zed\Discount\Business\Model\DiscountableInterface[] $discountableObjects
     * @param float $percentage
     *
     * @return float
     */
    public function calculatePercentage(array $discountableObjects, $percentage);

    /**
     * @api
     *
     * @param \Spryker\Zed\Discount\Business\Model\DiscountableInterface[] $discountableObjects
     * @param float $amount
     *
     * @return float
     */
    public function calculateFixed(array $discountableObjects, $amount);

    /**
     * @api
     *
     * @param \Spryker\Zed\Discount\Business\Model\DiscountableInterface[] $discountableObjects
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return void
     */
    public function distributeAmount(array $discountableObjects, DiscountTransfer $discountTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\VoucherTransfer $voucherTransfer
     *
     * @return \Generated\Shared\Transfer\VoucherCreateInfoTransfer
     */
    public function createVoucherCodes(VoucherTransfer $voucherTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\VoucherTransfer $voucherTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    public function createVoucherCode(VoucherTransfer $voucherTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\VoucherCodesTransfer $voucherCodesTransfer
     *
     * @return self
     */
    public function saveVoucherCode(VoucherCodesTransfer $voucherCodesTransfer);

    /**
     * @api
     *
     * @return array
     */
    public function getDecisionRulePluginNames();

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount
     */
    public function createDiscount(DiscountTransfer $discountTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount
     */
    public function updateDiscount(DiscountTransfer $discountTransfer);

    /**
     * @api
     *
     * @return array
     */
    public function getVoucherPoolCategories();

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\DecisionRuleTransfer $decisionRuleTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule
     */
    public function saveDiscountDecisionRule(DecisionRuleTransfer $decisionRuleTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CartRuleTransfer $cartRuleFormTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    public function saveCartRules(CartRuleTransfer $cartRuleFormTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\DecisionRuleTransfer $discountDecisionRuleTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule
     */
    public function createDiscountDecisionRule(DecisionRuleTransfer $discountDecisionRuleTransfer);

    /**
     * @api
     *
     * @param int $idDiscount
     *
     * @return array
     */
    public function getCurrentCartRulesDetailsByIdDiscount($idDiscount);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\DecisionRuleTransfer $discountDecisionRuleTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule
     */
    public function updateDiscountDecisionRule(DecisionRuleTransfer $discountDecisionRuleTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\VoucherTransfer $discountVoucherTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    public function createDiscountVoucher(VoucherTransfer $discountVoucherTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\VoucherTransfer $discountVoucherTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    public function updateDiscountVoucher(VoucherTransfer $discountVoucherTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\VoucherPoolTransfer $discountVoucherPoolTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    public function createDiscountVoucherPool(VoucherPoolTransfer $discountVoucherPoolTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\VoucherPoolTransfer $discountVoucherPoolTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    public function updateDiscountVoucherPool(VoucherPoolTransfer $discountVoucherPoolTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolCategory
     */
    public function createDiscountVoucherPoolCategory(VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolCategory
     */
    public function updateDiscountVoucherPoolCategory(VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer);

    /**
     * @api
     *
     * @param string $poolCategoryName
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolCategory
     */
    public function getOrCreateDiscountVoucherPoolCategoryByName($poolCategoryName);

    /**
     * @api
     *
     * @param string $pluginName
     *
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface
     */
    public function getCalculatorPluginByName($pluginName);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return array
     */
    public function getDiscountableItems(QuoteTransfer $quoteTransfer, DiscountCollectorTransfer $discountCollectorTransfer);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function getDiscountableOrderExpenses(QuoteTransfer $quoteTransfer, DiscountCollectorTransfer $discountCollectorTransfer);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function getDiscountableItemProductOptions(QuoteTransfer $quoteTransfer, DiscountCollectorTransfer $discountCollectorTransfer);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function getDiscountableItemsFromCollectorAggregate(QuoteTransfer $quoteTransfer, DiscountCollectorTransfer $discountCollectorTransfer);

    /**
     * @api
     *
     * @return array
     */
    public function getDiscountCollectors();

    /**
     * @api
     *
     * @return array
     */
    public function getDiscountCalculators();

    /**
     * @api
     *
     * @param array|string[] $codes
     *
     * @return bool
     */
    public function releaseUsedVoucherCodes(array $codes);

    /**
     * @api
     *
     * @param array|string[] $codes
     *
     * @return bool
     */
    public function useVoucherCodes(array $codes);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrderDiscounts(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderTotalDiscountAmount(OrderTransfer $orderTransfer);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderCalculatedDiscounts(OrderTransfer $orderTransfer);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateItemDiscounts(OrderTransfer $orderTransfer);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateGrandTotalWithDiscounts(OrderTransfer $orderTransfer);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderExpenseTaxWithDiscounts(OrderTransfer $orderTransfer);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderExpensesWithDiscounts(OrderTransfer $orderTransfer);
}

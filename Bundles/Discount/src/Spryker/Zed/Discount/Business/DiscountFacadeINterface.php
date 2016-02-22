<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business;

use Generated\Shared\Transfer\CartRuleTransfer;
use Generated\Shared\Transfer\DecisionRuleTransfer;
use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\VoucherCodesTransfer;
use Generated\Shared\Transfer\VoucherPoolCategoryTransfer;
use Generated\Shared\Transfer\VoucherPoolTransfer;
use Generated\Shared\Transfer\VoucherTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule as DecisionRule;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;

interface DiscountFacadeINterface
{

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount[]
     */
    public function calculateDiscounts(CalculableInterface $container);

    /**
     * @param string $code
     *
     * @return \Spryker\Zed\Kernel\Business\ModelResult
     */
    public function isVoucherUsable($code);

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule $decisionRule
     *
     * @return \Spryker\Zed\Kernel\Business\ModelResult
     */
    public function isMinimumCartSubtotalReached(CalculableInterface $container, DecisionRule $decisionRule);

    /**
     * @param \Spryker\Zed\Discount\Business\Model\DiscountableInterface[] $discountableObjects
     * @param float $percentage
     *
     * @return float
     */
    public function calculatePercentage(array $discountableObjects, $percentage);

    /**
     * @param \Spryker\Zed\Discount\Business\Model\DiscountableInterface[] $discountableObjects
     * @param float $amount
     *
     * @return float
     */
    public function calculateFixed(array $discountableObjects, $amount);

    /**
     * @param \Spryker\Zed\Discount\Business\Model\DiscountableInterface[] $discountableObjects
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return void
     */
    public function distributeAmount(array $discountableObjects, DiscountTransfer $discountTransfer);

    /**
     * @param \Generated\Shared\Transfer\VoucherTransfer $voucherTransfer
     *
     * @return \Generated\Shared\Transfer\VoucherCreateInfoTransfer
     */
    public function createVoucherCodes(VoucherTransfer $voucherTransfer);

    /**
     * @param \Generated\Shared\Transfer\VoucherTransfer $voucherTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    public function createVoucherCode(VoucherTransfer $voucherTransfer);

    /**
     * @param \Generated\Shared\Transfer\VoucherCodesTransfer $voucherCodesTransfer
     *
     * @return \Generated\Shared\Transfer\VoucherPoolTransfer
     */
    public function saveVoucherCode(VoucherCodesTransfer $voucherCodesTransfer);

    /**
     * @return array
     */
    public function getDecisionRulePluginNames();

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount
     */
    public function createDiscount(DiscountTransfer $discountTransfer);

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount
     */
    public function updateDiscount(DiscountTransfer $discountTransfer);

    /**
     * @return array
     */
    public function getVoucherPoolCategories();

    /**
     * @param \Generated\Shared\Transfer\DecisionRuleTransfer $decisionRuleTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule
     */
    public function saveDiscountDecisionRule(DecisionRuleTransfer $decisionRuleTransfer);

    /**
     * @param \Generated\Shared\Transfer\CartRuleTransfer $cartRuleFormTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    public function saveCartRules(CartRuleTransfer $cartRuleFormTransfer);

    /**
     * @param \Generated\Shared\Transfer\DecisionRuleTransfer $discountDecisionRuleTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule
     */
    public function createDiscountDecisionRule(DecisionRuleTransfer $discountDecisionRuleTransfer);

    /**
     * @param int $idDiscount
     *
     * @return array
     */
    public function getCurrentCartRulesDetailsByIdDiscount($idDiscount);

    /**
     * @param \Generated\Shared\Transfer\DecisionRuleTransfer $discountDecisionRuleTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule
     */
    public function updateDiscountDecisionRule(DecisionRuleTransfer $discountDecisionRuleTransfer);

    /**
     * @param \Generated\Shared\Transfer\VoucherTransfer $discountVoucherTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    public function createDiscountVoucher(VoucherTransfer $discountVoucherTransfer);

    /**
     * @param \Generated\Shared\Transfer\VoucherTransfer $discountVoucherTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    public function updateDiscountVoucher(VoucherTransfer $discountVoucherTransfer);

    /**
     * @param \Generated\Shared\Transfer\VoucherPoolTransfer $discountVoucherPoolTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    public function createDiscountVoucherPool(VoucherPoolTransfer $discountVoucherPoolTransfer);

    /**
     * @param \Generated\Shared\Transfer\VoucherPoolTransfer $discountVoucherPoolTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    public function updateDiscountVoucherPool(VoucherPoolTransfer $discountVoucherPoolTransfer);

    /**
     * @param \Generated\Shared\Transfer\VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolCategory
     */
    public function createDiscountVoucherPoolCategory(VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer);

    /**
     * @param \Generated\Shared\Transfer\VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolCategory
     */
    public function updateDiscountVoucherPoolCategory(VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer);

    /**
     * @param string $poolCategoryName
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolCategory
     */
    public function getOrCreateDiscountVoucherPoolCategoryByName($poolCategoryName);

    /**
     * @param string $pluginName
     *
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface
     */
    public function getCalculatorPluginByName($pluginName);

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return array
     */
    public function getDiscountableItems(CalculableInterface $container, DiscountCollectorTransfer $discountCollectorTransfer);

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function getDiscountableItemExpenses(CalculableInterface $container, DiscountCollectorTransfer $discountCollectorTransfer);

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function getDiscountableOrderExpenses(CalculableInterface $container, DiscountCollectorTransfer $discountCollectorTransfer);

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function getDiscountableItemProductOptions(CalculableInterface $container, DiscountCollectorTransfer $discountCollectorTransfer);

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function getDiscountableItemsFromCollectorAggregate(CalculableInterface $container, DiscountCollectorTransfer $discountCollectorTransfer);

    /**
     * @return array
     */
    public function getDiscountCollectors();

    /**
     * @return array
     */
    public function getDiscountCalculators();

    /**
     * @param array|string[] $codes
     *
     * @return bool
     */
    public function releaseUsedVoucherCodes(array $codes);

    /**
     * @param array|string[] $codes
     *
     * @return bool
     */
    public function useVoucherCodes(array $codes);

}

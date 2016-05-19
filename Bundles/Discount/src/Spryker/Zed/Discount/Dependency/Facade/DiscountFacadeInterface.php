<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Dependency\Facade;

use Generated\Shared\Transfer\DecisionRuleTransfer;
use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\VoucherPoolCategoryTransfer;
use Generated\Shared\Transfer\VoucherPoolTransfer;
use Generated\Shared\Transfer\VoucherTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule as DecisionRule;

interface DiscountFacadeInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function calculateDiscounts(QuoteTransfer $quoteTransfer);

    /**
     * @param string $code
     *
     * @return \Spryker\Zed\Kernel\Business\ModelResult
     */
    public function isVoucherUsable($code);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule $decisionRule
     *
     * @return \Spryker\Zed\Kernel\Business\ModelResult
     */
    public function isMinimumCartSubtotalReached(QuoteTransfer $quoteTransfer, DecisionRule $decisionRule);

    /**
     * @param \Spryker\Zed\Discount\Business\Model\DiscountableItemInterface[] $discountableObjects
     * @param float $percentage
     *
     * @return float
     */
    public function calculatePercentage(array $discountableObjects, $percentage);

    /**
     * @param \Spryker\Zed\Discount\Business\Model\DiscountableItemInterface[] $discountableObjects
     * @param float $amount
     *
     * @return float
     */
    public function calculateFixed(array $discountableObjects, $amount);

    /**
     * @param \Spryker\Zed\Discount\Business\Model\DiscountableItemInterface[] $discountableObjects
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return void
     */
    public function distributeAmount(array $discountableObjects, DiscountTransfer $discountTransfer);

    /**
     * @param \Generated\Shared\Transfer\VoucherTransfer $voucherTransfer
     *
     * @return void
     */
    public function createVoucherCodes(VoucherTransfer $voucherTransfer);

    /**
     * @param \Generated\Shared\Transfer\VoucherTransfer $voucherTransfer
     *
     * @return void
     */
    public function createVoucherCode(VoucherTransfer $voucherTransfer);

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
     * @param \Generated\Shared\Transfer\DecisionRuleTransfer $discountDecisionRuleTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule
     */
    public function createDiscountDecisionRule(DecisionRuleTransfer $discountDecisionRuleTransfer);

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
     * @param string $pluginName
     *
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface
     */
    public function getCalculatorPluginByName($pluginName);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return \Spryker\Zed\Discount\Business\Model\DiscountableItemInterface[]
     */
    public function getDiscountableItems(
        QuoteTransfer $quoteTransfer,
        DiscountCollectorTransfer $discountCollectorTransfer
    );

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return \Spryker\Zed\Discount\Business\Model\DiscountableItemInterface[]
     */
    public function getDiscountableOrderExpenses(
        QuoteTransfer $quoteTransfer,
        DiscountCollectorTransfer $discountCollectorTransfer
    );

    /**
     * @return array
     */
    public function getDiscountCollectors();

    /**
     * @return array
     */
    public function getDiscountCalculators();

    /**
     * @param array $codes
     *
     * @return bool
     */
    public function releaseUsedVoucherCodes(array $codes);

    /**
     * @param string[] $codes
     *
     * @return bool
     */
    public function useVoucherCodes(array $codes);

}

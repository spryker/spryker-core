<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Dependency\Facade;

use Generated\Shared\Discount\VoucherCreateInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\VoucherTransfer;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRule as DecisionRule;
use Generated\Shared\Transfer\VoucherPoolTransfer;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use Generated\Shared\Transfer\VoucherPoolCategoryTransfer;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRule;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucher;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolCategory;
use SprykerEngine\Zed\Kernel\Business\ModelResult;
use Generated\Shared\Transfer\DecisionRuleTransfer;

interface DiscountFacadeInterface
{

    /**
     * @param CalculableInterface $container
     *
     * @return array
     */
    public function calculateDiscounts(CalculableInterface $container);

    /**
     * @param string $code
     * @param int $idDiscountVoucherPool
     *
     * @return ModelResult
     */
    public function isVoucherUsable($code, $idDiscountVoucherPool);

    /**
     * @param CalculableInterface $container
     * @param DecisionRule $decisionRule
     *
     * @return ModelResult
     */
    public function isMinimumCartSubtotalReached(CalculableInterface $container, DecisionRule $decisionRule);

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param float $percentage
     *
     * @return float
     */
    public function calculatePercentage(array $discountableObjects, $percentage);

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param float $amount
     *
     * @return float
     */
    public function calculateFixed(array $discountableObjects, $amount);

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param DiscountTransfer $discountTransfer
     */
    public function distributeAmount(array $discountableObjects, DiscountTransfer $discountTransfer);

    /**
     * @param VoucherCreateInterface $voucherTransfer
     */
    public function createVoucherCodes(VoucherCreateInterface $voucherTransfer);

    /**
     * @param VoucherCreateInterface $voucherTransfer
     */
    public function createVoucherCode(VoucherCreateInterface $voucherTransfer);

    /**
     * @return array
     */
    public function getDecisionRulePluginNames();

    /**
     * @param DiscountTransfer $discountTransfer
     *
     * @return SpyDiscount
     */
    public function createDiscount(DiscountTransfer $discountTransfer);

    /**
     * @param DiscountTransfer $discountTransfer
     *
     * @return SpyDiscount
     */
    public function updateDiscount(DiscountTransfer $discountTransfer);

    /**
     * @param DecisionRuleTransfer $discountDecisionRuleTransfer
     *
     * @return SpyDiscountDecisionRule
     */
    public function createDiscountDecisionRule(DecisionRuleTransfer $discountDecisionRuleTransfer);

    /**
     * @param DecisionRuleTransfer $discountDecisionRuleTransfer
     *
     * @return SpyDiscountDecisionRule
     */
    public function updateDiscountDecisionRule(DecisionRuleTransfer $discountDecisionRuleTransfer);

    /**
     * @param VoucherTransfer $discountVoucherTransfer
     *
     * @return SpyDiscountVoucher
     */
    public function createDiscountVoucher(VoucherTransfer $discountVoucherTransfer);

    /**
     * @param VoucherTransfer $discountVoucherTransfer
     *
     * @return SpyDiscountVoucher
     */
    public function updateDiscountVoucher(VoucherTransfer $discountVoucherTransfer);

    /**
     * @param VoucherPoolTransfer $discountVoucherPoolTransfer
     *
     * @return SpyDiscountVoucher
     */
    public function createDiscountVoucherPool(VoucherPoolTransfer $discountVoucherPoolTransfer);

    /**
     * @param VoucherPoolTransfer $discountVoucherPoolTransfer
     *
     * @return SpyDiscountVoucher
     */
    public function updateDiscountVoucherPool(VoucherPoolTransfer $discountVoucherPoolTransfer);

    /**
     * @param VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer
     *
     * @return SpyDiscountVoucherPoolCategory
     */
    public function createDiscountVoucherPoolCategory(VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer);

    /**
     * @param VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer
     *
     * @return SpyDiscountVoucherPoolCategory
     */
    public function updateDiscountVoucherPoolCategory(VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer);

    /**
     * @param string $pluginName
     *
     * @return DiscountCalculatorPluginInterface
     */
    public function getCalculatorPluginByName($pluginName);

    /**
     * @param CalculableInterface $container
     *
     * @return DiscountableInterface[]
     */
    public function getDiscountableItems(CalculableInterface $container);

    /**
     * @param CalculableInterface $container
     *
     * @return DiscountableInterface[]
     */
    public function getDiscountableItemExpenses(CalculableInterface $container);

    /**
     * @param CalculableInterface $container
     *
     * @return DiscountableInterface[]
     */
    public function getDiscountableOrderExpenses(CalculableInterface $container);

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
    public function releaseVoucherCodes(array $codes);

    /**
     * @param array|string[] $codes
     *
     * @return bool
     */
    public function useVoucherCodes(array $codes);

}

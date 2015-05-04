<?php

namespace SprykerFeature\Zed\Discount\Dependency\Facade;

use SprykerFeature\Shared\Calculation\Transfer\Discount;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;
use SprykerFeature\Shared\Discount\Transfer\DiscountDecisionRule;
use SprykerFeature\Shared\Discount\Transfer\DiscountVoucher;
use SprykerFeature\Shared\Discount\Transfer\DiscountVoucherPool;
use SprykerFeature\Shared\Discount\Transfer\DiscountVoucherPoolCategory;
use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;
use Generated\Shared\Transfer\DiscountDiscountTransfer;
use Generated\Shared\Transfer\DiscountDiscountVoucherTransfer;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRule as DecisionRule;
use Generated\Shared\Transfer\DiscountDiscountVoucherPoolTransfer;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use Generated\Shared\Transfer\DiscountDiscountVoucherPoolCategoryTransfer;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRule;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucher;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolCategory;
use SprykerEngine\Zed\Kernel\Business\ModelResult;
use Generated\Shared\Transfer\DiscountDiscountDecisionRuleTransfer;

interface DiscountFacadeInterface
{
    /**
     * @param DiscountableContainerInterface $container
     * @return array
     */
    public function calculateDiscounts(DiscountableContainerInterface $container);

    /**
     * @param string $code
     * @param int $idDiscountVoucherPool
     * @return ModelResult
     */
    public function isVoucherUsable($code, $idDiscountVoucherPool);

    /**
     * @param DiscountableContainerInterface $container
     * @param DecisionRule $decisionRule
     * @return ModelResult
     */
    public function isMinimumCartSubtotalReached(DiscountableContainerInterface $container, DecisionRule $decisionRule);

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param float $percentage
     * @return float
     */
    public function calculatePercentage(array $discountableObjects, $percentage);

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param float $amount
     * @return float
     */
    public function calculateFixed(array $discountableObjects, $amount);

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param float $amount
     */
    public function distributeAmount(array $discountableObjects, $amount);

    /**
     * @param int $amount
     * @param int $idVoucherPool
     * @param bool $includeTemplate
     */
    public function createVoucherCodes($amount, $idVoucherPool, $includeTemplate = true);

    /**
     * @param string $code
     * @param int $idVoucherPool
     * @return SpyDiscountVoucher
     */
    public function createVoucherCode($code, $idVoucherPool);

    /**
     * @return array
     */
    public function getDecisionRulePluginNames();

    /**
     * @param Discount $discountTransfer
     * @return SpyDiscount
     */
    public function createDiscount(Discount $discountTransfer);

    /**
     * @param Discount $discountTransfer
     * @return SpyDiscount
     */
    public function updateDiscount(Discount $discountTransfer);

    /**
     * @param DiscountDecisionRule $discountDecisionRuleTransfer
     * @return SpyDiscountDecisionRule
     */
    public function createDiscountDecisionRule(DiscountDecisionRule $discountDecisionRuleTransfer);

    /**
     * @param DiscountDecisionRule $discountDecisionRuleTransfer
     * @return SpyDiscountDecisionRule
     */
    public function updateDiscountDecisionRule(DiscountDecisionRule $discountDecisionRuleTransfer);

    /**
     * @param DiscountVoucher $discountVoucherTransfer
     * @return SpyDiscountVoucher
     */
    public function createDiscountVoucher(DiscountVoucher $discountVoucherTransfer);

    /**
     * @param DiscountVoucher $discountVoucherTransfer
     * @return SpyDiscountVoucher
     */
    public function updateDiscountVoucher(DiscountVoucher $discountVoucherTransfer);

    /**
     * @param DiscountVoucherPool $discountVoucherPoolTransfer
     * @return SpyDiscountVoucher
     */
    public function createDiscountVoucherPool(DiscountVoucherPool $discountVoucherPoolTransfer);

    /**
     * @param DiscountVoucherPool $discountVoucherPoolTransfer
     * @return SpyDiscountVoucher
     */
    public function updateDiscountVoucherPool(DiscountVoucherPool $discountVoucherPoolTransfer);

    /**
     * @param DiscountVoucherPoolCategory $discountVoucherPoolCategoryTransfer
     * @return SpyDiscountVoucherPoolCategory
     */
    public function createDiscountVoucherPoolCategory(DiscountVoucherPoolCategory $discountVoucherPoolCategoryTransfer);

    /**
     * @param DiscountVoucherPoolCategory $discountVoucherPoolCategoryTransfer
     * @return SpyDiscountVoucherPoolCategory
     */
    public function updateDiscountVoucherPoolCategory(DiscountVoucherPoolCategory $discountVoucherPoolCategoryTransfer);

    /**
     * @param string $pluginName
     * @return DiscountCalculatorPluginInterface
     */
    public function getCalculatorPluginByName($pluginName);

    /**
     * @param DiscountableContainerInterface $container
     * @return DiscountableInterface[]
     */
    public function getDiscountableItems(DiscountableContainerInterface $container);

    /**
     * @param DiscountableContainerInterface $container
     * @return DiscountableInterface[]
     */
    public function getDiscountableItemExpenses(DiscountableContainerInterface $container);

    /**
     * @param DiscountableContainerInterface $container
     * @return DiscountableInterface[]
     */
    public function getDiscountableOrderExpenses(DiscountableContainerInterface $container);

    /**
     * @return array
     */
    public function getDiscountCollectors();

    /**
     * @return array
     */
    public function getDiscountCalculators();
}

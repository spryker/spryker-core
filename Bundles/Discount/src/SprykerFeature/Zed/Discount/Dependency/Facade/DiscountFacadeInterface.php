<?php

namespace SprykerFeature\Zed\Discount\Dependency\Facade;

use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;
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
     * @param DiscountDiscountTransfer $discountTransfer
     * @return SpyDiscount
     */
    public function createDiscount(DiscountDiscountTransfer $discountTransfer);

    /**
     * @param DiscountDiscountTransfer $discountTransfer
     * @return SpyDiscount
     */
    public function updateDiscount(DiscountDiscountTransfer $discountTransfer);

    /**
     * @param DiscountDiscountDecisionRuleTransfer $discountDecisionRuleTransfer
     * @return SpyDiscountDecisionRule
     */
    public function createDiscountDecisionRule(DiscountDiscountDecisionRuleTransfer $discountDecisionRuleTransfer);

    /**
     * @param DiscountDiscountDecisionRuleTransfer $discountDecisionRuleTransfer
     * @return SpyDiscountDecisionRule
     */
    public function updateDiscountDecisionRule(DiscountDiscountDecisionRuleTransfer $discountDecisionRuleTransfer);

    /**
     * @param DiscountDiscountVoucherTransfer $discountVoucherTransfer
     * @return SpyDiscountVoucher
     */
    public function createDiscountVoucher(DiscountDiscountVoucherTransfer $discountVoucherTransfer);

    /**
     * @param DiscountDiscountVoucherTransfer $discountVoucherTransfer
     * @return SpyDiscountVoucher
     */
    public function updateDiscountVoucher(DiscountDiscountVoucherTransfer $discountVoucherTransfer);

    /**
     * @param DiscountDiscountVoucherPoolTransfer $discountVoucherPoolTransfer
     * @return SpyDiscountVoucher
     */
    public function createDiscountVoucherPool(DiscountDiscountVoucherPoolTransfer $discountVoucherPoolTransfer);

    /**
     * @param DiscountDiscountVoucherPoolTransfer $discountVoucherPoolTransfer
     * @return SpyDiscountVoucher
     */
    public function updateDiscountVoucherPool(DiscountDiscountVoucherPoolTransfer $discountVoucherPoolTransfer);

    /**
     * @param DiscountDiscountVoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer
     * @return SpyDiscountVoucherPoolCategory
     */
    public function createDiscountVoucherPoolCategory(DiscountDiscountVoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer);

    /**
     * @param DiscountDiscountVoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer
     * @return SpyDiscountVoucherPoolCategory
     */
    public function updateDiscountVoucherPoolCategory(DiscountDiscountVoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer);

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

<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business;

use Generated\Shared\Discount\DiscountCollectorInterface;
use Generated\Shared\Discount\OrderInterface;
use Generated\Shared\Discount\VoucherCreateInterface;
use Generated\Shared\Transfer\CartRuleTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\DecisionRuleTransfer;
use Generated\Shared\Transfer\VoucherCodesTransfer;
use Generated\Shared\Transfer\VoucherTransfer;
use Generated\Shared\Transfer\VoucherPoolTransfer;
use Generated\Shared\Transfer\VoucherPoolCategoryTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Dependency\Facade\DiscountFacadeInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRule;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucher;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolCategory;
use SprykerEngine\Zed\Kernel\Business\ModelResult;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRule as DecisionRule;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 */
class DiscountFacade extends AbstractFacade implements DiscountFacadeInterface
{

    /**
     * @param CalculableInterface $container
     *
     * @return SpyDiscount[]
     */
    public function calculateDiscounts(CalculableInterface $container)
    {
        return $this->getDependencyContainer()->createDiscount($container)->calculate();
    }

    /**
     * @param string $code
     *
     * @return ModelResult
     */
    public function isCodeUsable($code)
    {
        return $this->getDependencyContainer()->getDecisionRuleVoucher()->isCodeUsable($code);
    }

    /**
     * @param string $code
     * @param int $idDiscountVoucherPool
     *
     * @return ModelResult
     */
    public function isVoucherUsable($code, $idDiscountVoucherPool)
    {
        return $this->getDependencyContainer()->getDecisionRuleVoucher()->isUsable($code, $idDiscountVoucherPool);
    }

    /**
     * @param CalculableInterface $container
     * @param DecisionRule $decisionRule
     *
     * @return ModelResult
     */
    public function isMinimumCartSubtotalReached(CalculableInterface $container, DecisionRule $decisionRule)
    {
        return $this->getDependencyContainer()
            ->getDecisionRuleMinimumCartSubtotal()
            ->isMinimumCartSubtotalReached($container, $decisionRule)
        ;
    }

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param float $percentage
     *
     * @return float
     */
    public function calculatePercentage(array $discountableObjects, $percentage)
    {
        return $this->getDependencyContainer()->createCalculatorPercentage()->calculate($discountableObjects, $percentage);
    }

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param float $amount
     *
     * @return float
     */
    public function calculateFixed(array $discountableObjects, $amount)
    {
        return $this->getDependencyContainer()->createCalculatorFixed()->calculate($discountableObjects, $amount);
    }

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param DiscountTransfer $discountTransfer
     */
    public function distributeAmount(array $discountableObjects, DiscountTransfer $discountTransfer)
    {
        $this->getDependencyContainer()->createDistributor()->distribute($discountableObjects, $discountTransfer);
    }

    /**
     * @param VoucherCreateInterface $voucherTransfer
     */
    public function createVoucherCodes(VoucherCreateInterface $voucherTransfer)
    {
        $this->getDependencyContainer()->createVoucherEngine()->createVoucherCodes($voucherTransfer);
    }

    /**
     * @param VoucherCreateInterface $voucherTransfer
     */
    public function createVoucherCode(VoucherCreateInterface $voucherTransfer)
    {
        return $this->getDependencyContainer()->createVoucherEngine()->createVoucherCode($voucherTransfer);
    }

    /**
     * @param VoucherCodesTransfer $voucherCodesTransfer
     *
     * @return $this
     */
    public function saveVoucherCode(VoucherCodesTransfer $voucherCodesTransfer)
    {
        return $this->getDependencyContainer()->createVoucherCodesWriter()->saveVoucherCode($voucherCodesTransfer);
    }

    /**
     * @return array
     */
    public function getDecisionRulePluginNames()
    {
        return $this->getDependencyContainer()->getConfig()->getDecisionPluginNames();
    }

    /**
     * @param DiscountTransfer $discountTransfer
     *
     * @return SpyDiscount
     */
    public function createDiscount(DiscountTransfer $discountTransfer)
    {
        return $this->getDependencyContainer()->createDiscountWriter()->create($discountTransfer);
    }

    /**
     * @param DiscountTransfer $discountTransfer
     *
     * @return SpyDiscount
     */
    public function updateDiscount(DiscountTransfer $discountTransfer)
    {
        return $this->getDependencyContainer()->createDiscountWriter()->update($discountTransfer);
    }

    /**
     * @return array
     */
    public function getVoucherPoolCategories()
    {
        return $this->getDependencyContainer()
            ->createVoucherPoolCategory()
            ->getAvailableVoucherPoolCategories()
        ;
    }

    /**
     * @param DecisionRuleTransfer $decisionRuleTransfer
     *
     * @return DecisionRule
     */
    public function saveDiscountDecisionRule(DecisionRuleTransfer $decisionRuleTransfer)
    {
        return $this->getDependencyContainer()->createDiscountDecisionRuleWriter()->saveDiscountDecisionRule($decisionRuleTransfer);
    }

    /**
     * @param CartRuleTransfer $cartRuleFormTransfer
     *
     * @return DiscountTransfer
     */
    public function saveCartRules(CartRuleTransfer $cartRuleFormTransfer)
    {
        return $this->getDependencyContainer()->createCartRule()->saveCartRule($cartRuleFormTransfer);
    }

    /**
     * @param DecisionRuleTransfer $discountDecisionRuleTransfer
     *
     * @return SpyDiscountDecisionRule
     */
    public function createDiscountDecisionRule(DecisionRuleTransfer $discountDecisionRuleTransfer)
    {
        return $this->getDependencyContainer()->createDiscountDecisionRuleWriter()->create($discountDecisionRuleTransfer);
    }

    /**
     * @param int $idDiscount
     *
     * @return array
     */
    public function getCurrentCartRulesDetailsByIdDiscount($idDiscount)
    {
        return $this->getDependencyContainer()
            ->createCartRule()
            ->getCurrentCartRulesDetailsByIdDiscount($idDiscount)
        ;
    }

    /**
     * @param DecisionRuleTransfer $discountDecisionRuleTransfer
     *
     * @return SpyDiscountDecisionRule
     */
    public function updateDiscountDecisionRule(DecisionRuleTransfer $discountDecisionRuleTransfer)
    {
        return $this->getDependencyContainer()->createDiscountDecisionRuleWriter()->update($discountDecisionRuleTransfer);
    }

    /**
     * @param VoucherTransfer $discountVoucherTransfer
     *
     * @return SpyDiscountVoucher
     */
    public function createDiscountVoucher(VoucherTransfer $discountVoucherTransfer)
    {
        return $this->getDependencyContainer()->createDiscountVoucherWriter()->create($discountVoucherTransfer);
    }

    /**
     * @param VoucherTransfer $discountVoucherTransfer
     *
     * @return SpyDiscountVoucher
     */
    public function updateDiscountVoucher(VoucherTransfer $discountVoucherTransfer)
    {
        return $this->getDependencyContainer()->createDiscountVoucherWriter()->update($discountVoucherTransfer);
    }

    /**
     * @param VoucherPoolTransfer $discountVoucherPoolTransfer
     *
     * @return SpyDiscountVoucher
     */
    public function createDiscountVoucherPool(VoucherPoolTransfer $discountVoucherPoolTransfer)
    {
        return $this->getDependencyContainer()->createDiscountVoucherPoolWriter()->create($discountVoucherPoolTransfer);
    }

    /**
     * @param VoucherPoolTransfer $discountVoucherPoolTransfer
     *
     * @return SpyDiscountVoucher
     */
    public function updateDiscountVoucherPool(VoucherPoolTransfer $discountVoucherPoolTransfer)
    {
        return $this->getDependencyContainer()->createDiscountVoucherPoolWriter()->update($discountVoucherPoolTransfer);
    }

    /**
     * @param VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer
     *
     * @return SpyDiscountVoucherPoolCategory
     */
    public function createDiscountVoucherPoolCategory(VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer)
    {
        return $this->getDependencyContainer()->createDiscountVoucherPoolCategoryWriter()
            ->create($discountVoucherPoolCategoryTransfer)
        ;
    }

    /**
     * @param VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer
     *
     * @return SpyDiscountVoucherPoolCategory
     */
    public function updateDiscountVoucherPoolCategory(VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer)
    {
        return $this->getDependencyContainer()->createDiscountVoucherPoolCategoryWriter()
            ->update($discountVoucherPoolCategoryTransfer)
        ;
    }

    /**
     * @param string $poolCategoryName
     *
     * @return SpyDiscountVoucherPoolCategory
     */
    public function getOrCreateDiscountVoucherPoolCategoryByName($poolCategoryName)
    {
        return $this->getDependencyContainer()->createDiscountVoucherPoolCategoryWriter()
            ->getOrCreateByName($poolCategoryName)
        ;
    }

    /**
     * @param string $pluginName
     *
     * @return DiscountCalculatorPluginInterface
     */
    public function getCalculatorPluginByName($pluginName)
    {
        return $this->getDependencyContainer()->getConfig()->getCalculatorPluginByName($pluginName);
    }

    /**
     * @param CalculableInterface        $container
     * @param DiscountCollectorInterface $discountCollectorTransfer
     *
     * @return array
     */
    public function getDiscountableItems(
        CalculableInterface $container,
        DiscountCollectorInterface $discountCollectorTransfer
    ) {
        return $this->getDependencyContainer()->createItemCollector()->collect($container, $discountCollectorTransfer);
    }

    /**
     * @param CalculableInterface        $container
     * @param DiscountCollectorInterface $discountCollectorTransfer
     *
     * @return OrderInterface[]
     */
    public function getDiscountableItemExpenses(
        CalculableInterface $container,
        DiscountCollectorInterface $discountCollectorTransfer
    ) {
        return $this->getDependencyContainer()->createItemExpenseCollector()
            ->collect($container, $discountCollectorTransfer);
    }

    /**
     * @param CalculableInterface        $container
     * @param DiscountCollectorInterface $discountCollectorTransfer
     *
     * @return OrderInterface[]
     */
    public function getDiscountableOrderExpenses(
        CalculableInterface $container,
        DiscountCollectorInterface $discountCollectorTransfer
    ) {
        return $this->getDependencyContainer()->createOrderExpenseCollector()
            ->collect($container, $discountCollectorTransfer);
    }

    /**
     * @param CalculableInterface        $container
     * @param DiscountCollectorInterface $discountCollectorTransfer
     *
     * @return OrderInterface[]
     */
    public function getDiscountableItemProductOptions(
        CalculableInterface $container,
        DiscountCollectorInterface $discountCollectorTransfer
    ) {
        return $this->getDependencyContainer()->createItemProductOptionCollector()
            ->collect($container, $discountCollectorTransfer);
    }

    /**
     * @param CalculableInterface        $container
     * @param DiscountCollectorInterface $discountCollectorTransfer
     *
     * @return OrderInterface[]
     */
    public function getDiscountableItemsFromCollectorAggregate(
        CalculableInterface $container,
        DiscountCollectorInterface $discountCollectorTransfer
    ) {
        return $this->getDependencyContainer()->createAggregateCollector()
            ->collect($container, $discountCollectorTransfer);
    }

    /**
     * @return array
     */
    public function getDiscountCollectors()
    {
        return array_keys($this->getDependencyContainer()->createAvailableCollectorPlugins());
    }

    /**
     * @return array
     */
    public function getDiscountCalculators()
    {
        return array_keys($this->getDependencyContainer()->createAvailableCalculatorPlugins());
    }

    /**
     * @param array|string[] $codes
     *
     * @return bool
     */
    public function releaseVoucherCodes(array $codes)
    {
        return $this->getDependencyContainer()->createVoucherCode()->releaseCodes($codes);
    }

    /**
     * @param array|string[] $codes
     *
     * @return bool
     */
    public function useVoucherCodes(array $codes)
    {
        return $this->getDependencyContainer()->createVoucherCode()->useCodes($codes);
    }

}

<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business;

use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\CartRuleTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\DecisionRuleTransfer;
use Generated\Shared\Transfer\VoucherCodesTransfer;
use Generated\Shared\Transfer\VoucherTransfer;
use Generated\Shared\Transfer\VoucherPoolTransfer;
use Generated\Shared\Transfer\VoucherPoolCategoryTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule as DecisionRule;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountBusinessFactory getFactory()
 */
class DiscountFacade extends AbstractFacade
{

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount[]
     */
    public function calculateDiscounts(CalculableInterface $container)
    {
        return $this->getFactory()->createDiscount($container)->calculate();
    }

    /**
     * @param string $code
     *
     * @return \Spryker\Zed\Kernel\Business\ModelResult
     */
    public function isVoucherUsable($code)
    {
        return $this->getFactory()->createDecisionRuleVoucher()->isUsable($code);
    }

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule $decisionRule
     *
     * @return \Spryker\Zed\Kernel\Business\ModelResult
     */
    public function isMinimumCartSubtotalReached(CalculableInterface $container, DecisionRule $decisionRule)
    {
        return $this->getFactory()
            ->createDecisionRuleMinimumCartSubtotal()
            ->isMinimumCartSubtotalReached($container, $decisionRule);
    }

    /**
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
     * @param \Generated\Shared\Transfer\VoucherTransfer $voucherTransfer
     *
     * @return \Generated\Shared\Transfer\VoucherCreateInfoTransfer
     */
    public function createVoucherCodes(VoucherTransfer $voucherTransfer)
    {
        return $this->getFactory()->createVoucherEngine()->createVoucherCodes($voucherTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\VoucherTransfer $voucherTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    public function createVoucherCode(VoucherTransfer $voucherTransfer)
    {
        return $this->getFactory()->createVoucherEngine()->createVoucherCode($voucherTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\VoucherCodesTransfer $voucherCodesTransfer
     *
     * @return \Generated\Shared\Transfer\VoucherPoolTransfer
     */
    public function saveVoucherCode(VoucherCodesTransfer $voucherCodesTransfer)
    {
        return $this->getFactory()->createVoucherCodesWriter()->saveVoucherCode($voucherCodesTransfer);
    }

    /**
     * @return array
     */
    public function getDecisionRulePluginNames()
    {
        return $this->getFactory()->getDecisionRulePluginNames();
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount
     */
    public function createDiscount(DiscountTransfer $discountTransfer)
    {
        return $this->getFactory()->createDiscountWriter()->create($discountTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount
     */
    public function updateDiscount(DiscountTransfer $discountTransfer)
    {
        return $this->getFactory()->createDiscountWriter()->update($discountTransfer);
    }

    /**
     * @return array
     */
    public function getVoucherPoolCategories()
    {
        return $this->getFactory()
            ->createVoucherPoolCategory()
            ->getAvailableVoucherPoolCategories();
    }

    /**
     * @param \Generated\Shared\Transfer\DecisionRuleTransfer $decisionRuleTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule
     */
    public function saveDiscountDecisionRule(DecisionRuleTransfer $decisionRuleTransfer)
    {
        return $this->getFactory()->createDiscountDecisionRuleWriter()->saveDiscountDecisionRule($decisionRuleTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartRuleTransfer $cartRuleFormTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    public function saveCartRules(CartRuleTransfer $cartRuleFormTransfer)
    {
        return $this->getFactory()->createCartRule()->saveCartRule($cartRuleFormTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DecisionRuleTransfer $discountDecisionRuleTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule
     */
    public function createDiscountDecisionRule(DecisionRuleTransfer $discountDecisionRuleTransfer)
    {
        return $this->getFactory()->createDiscountDecisionRuleWriter()->create($discountDecisionRuleTransfer);
    }

    /**
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
     * @param \Generated\Shared\Transfer\DecisionRuleTransfer $discountDecisionRuleTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule
     */
    public function updateDiscountDecisionRule(DecisionRuleTransfer $discountDecisionRuleTransfer)
    {
        return $this->getFactory()->createDiscountDecisionRuleWriter()->update($discountDecisionRuleTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\VoucherTransfer $discountVoucherTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    public function createDiscountVoucher(VoucherTransfer $discountVoucherTransfer)
    {
        return $this->getFactory()->createDiscountVoucherWriter()->create($discountVoucherTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\VoucherTransfer $discountVoucherTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    public function updateDiscountVoucher(VoucherTransfer $discountVoucherTransfer)
    {
        return $this->getFactory()->createDiscountVoucherWriter()->update($discountVoucherTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\VoucherPoolTransfer $discountVoucherPoolTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    public function createDiscountVoucherPool(VoucherPoolTransfer $discountVoucherPoolTransfer)
    {
        return $this->getFactory()->createDiscountVoucherPoolWriter()->create($discountVoucherPoolTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\VoucherPoolTransfer $discountVoucherPoolTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    public function updateDiscountVoucherPool(VoucherPoolTransfer $discountVoucherPoolTransfer)
    {
        return $this->getFactory()->createDiscountVoucherPoolWriter()->update($discountVoucherPoolTransfer);
    }

    /**
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
     * @param string $pluginName
     *
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface
     */
    public function getCalculatorPluginByName($pluginName)
    {
        return $this->getFactory()->getCalculatorPluginByName($pluginName);
    }

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return array
     */
    public function getDiscountableItems(
        CalculableInterface $container,
        DiscountCollectorTransfer $discountCollectorTransfer
    ) {
        return $this->getFactory()->createItemCollector()->collect($container, $discountCollectorTransfer);
    }

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function getDiscountableItemExpenses(
        CalculableInterface $container,
        DiscountCollectorTransfer $discountCollectorTransfer
    ) {
        return $this->getFactory()->createItemExpenseCollector()
            ->collect($container, $discountCollectorTransfer);
    }

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function getDiscountableOrderExpenses(
        CalculableInterface $container,
        DiscountCollectorTransfer $discountCollectorTransfer
    ) {
        return $this->getFactory()->createOrderExpenseCollector()
            ->collect($container, $discountCollectorTransfer);
    }

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function getDiscountableItemProductOptions(
        CalculableInterface $container,
        DiscountCollectorTransfer $discountCollectorTransfer
    ) {
        return $this->getFactory()->createItemProductOptionCollector()
            ->collect($container, $discountCollectorTransfer);
    }

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function getDiscountableItemsFromCollectorAggregate(
        CalculableInterface $container,
        DiscountCollectorTransfer $discountCollectorTransfer
    ) {
        return $this->getFactory()->createAggregateCollector()
            ->collect($container, $discountCollectorTransfer);
    }

    /**
     * @return array
     */
    public function getDiscountCollectors()
    {
        return array_keys($this->getFactory()->getAvailableCollectorPlugins());
    }

    /**
     * @return array
     */
    public function getDiscountCalculators()
    {
        return array_keys($this->getFactory()->getAvailableCalculatorPlugins());
    }

    /**
     * @param array|string[] $codes
     *
     * @return bool
     */
    public function releaseUsedVoucherCodes(array $codes)
    {
        return $this->getFactory()->createVoucherCode()->releaseUsedCodes($codes);
    }

    /**
     * @param array|string[] $codes
     *
     * @return bool
     */
    public function useVoucherCodes(array $codes)
    {
        return $this->getFactory()->createVoucherCode()->useCodes($codes);
    }

}

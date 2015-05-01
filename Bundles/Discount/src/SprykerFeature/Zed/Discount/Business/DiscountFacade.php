<?php

namespace SprykerFeature\Zed\Discount\Business;

use SprykerFeature\Shared\Discount\Transfer\Discount;
use SprykerFeature\Shared\Discount\Transfer\DiscountDecisionRule;
use SprykerFeature\Shared\Discount\Transfer\DiscountVoucher;
use SprykerFeature\Shared\Discount\Transfer\DiscountVoucherPool;
use SprykerFeature\Shared\Discount\Transfer\DiscountVoucherPoolCategory;
use SprykerFeature\Zed\Discount\Dependency\Facade\DiscountFacadeInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;
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
     * @param DiscountableContainerInterface $container
     * @return SpyDiscount[]
     */
    public function calculateDiscounts(DiscountableContainerInterface $container)
    {
        return $this->getDependencyContainer()->getDiscount($container)->calculate();
    }

    /**
     * @param string $code
     * @param int $idDiscountVoucherPool
     * @return ModelResult
     */
    public function isVoucherUsable($code, $idDiscountVoucherPool)
    {
        return $this->getDependencyContainer()->getDecisionRuleVoucher()->isUsable($code, $idDiscountVoucherPool);
    }

    /**
     * @param DiscountableContainerInterface $container
     * @param DecisionRule $decisionRule
     * @return $this|ModelResult
     */
    public function isMinimumCartSubtotalReached(DiscountableContainerInterface $container, DecisionRule $decisionRule)
    {
        return $this->getDependencyContainer()
            ->getDecisionRuleMinimumCartSubtotal()
            ->isMinimumCartSubtotalReached($container, $decisionRule);
    }

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param float $percentage
     * @return float
     */
    public function calculatePercentage(array $discountableObjects, $percentage)
    {
        return $this->getDependencyContainer()->getCalculatorPercentage()->calculate($discountableObjects, $percentage);
    }

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param float $amount
     * @return float
     */
    public function calculateFixed(array $discountableObjects, $amount)
    {
        return $this->getDependencyContainer()->getCalculatorFixed()->calculate($discountableObjects, $amount);
    }

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param float $amount
     */
    public function distributeAmount(array $discountableObjects, $amount)
    {
        $this->getDependencyContainer()->getDistributor()->distribute($discountableObjects, $amount);
    }

    /**
     * @param int $amount
     * @param int $idVoucherPool
     * @param bool $includeTemplate
     */
    public function createVoucherCodes($amount, $idVoucherPool, $includeTemplate = true)
    {
        $this->getDependencyContainer()->getVoucherEngine()->createVoucherCodes($amount, $idVoucherPool, $includeTemplate);
    }

    /**
     * @param string $code
     * @param int $idVoucherPool
     * @return SpyDiscountVoucher
     */
    public function createVoucherCode($code, $idVoucherPool)
    {
        return $this->getDependencyContainer()->getVoucherEngine()->createVoucherCode($code, $idVoucherPool);
    }

    /**
     * @return array
     */
    public function getDecisionRulePluginNames()
    {
        return $this->getDependencyContainer()->getConfig()->getDecisionPluginNames();
    }

    /**
     * @param Discount $discountTransfer
     * @return SpyDiscount
     */
    public function createDiscount(Discount $discountTransfer)
    {
        return $this->getDependencyContainer()->getDiscountWriter()->create($discountTransfer);
    }

    /**
     * @param Discount $discountTransfer
     * @return SpyDiscount
     */
    public function updateDiscount(Discount $discountTransfer)
    {
        return $this->getDependencyContainer()->getDiscountWriter()->update($discountTransfer);
    }

    /**
     * @param DiscountDecisionRule $discountDecisionRuleTransfer
     * @return SpyDiscountDecisionRule
     */
    public function createDiscountDecisionRule(DiscountDecisionRule $discountDecisionRuleTransfer)
    {
        return $this->getDependencyContainer()->getDiscountDecisionRuleWriter()->create($discountDecisionRuleTransfer);
    }

    /**
     * @param DiscountDecisionRule $discountDecisionRuleTransfer
     * @return SpyDiscountDecisionRule
     */
    public function updateDiscountDecisionRule(DiscountDecisionRule $discountDecisionRuleTransfer)
    {
        return $this->getDependencyContainer()->getDiscountDecisionRuleWriter()->update($discountDecisionRuleTransfer);
    }

    /**
     * @param DiscountVoucher $discountVoucherTransfer
     * @return SpyDiscountVoucher
     */
    public function createDiscountVoucher(DiscountVoucher $discountVoucherTransfer)
    {
        return $this->getDependencyContainer()->getDiscountVoucherWriter()->create($discountVoucherTransfer);
    }

    /**
     * @param DiscountVoucher $discountVoucherTransfer
     * @return SpyDiscountVoucher
     */
    public function updateDiscountVoucher(DiscountVoucher $discountVoucherTransfer)
    {
        return $this->getDependencyContainer()->getDiscountVoucherWriter()->update($discountVoucherTransfer);
    }

    /**
     * @param DiscountVoucherPool $discountVoucherPoolTransfer
     * @return SpyDiscountVoucher
     */
    public function createDiscountVoucherPool(DiscountVoucherPool $discountVoucherPoolTransfer)
    {
        return $this->getDependencyContainer()->getDiscountVoucherPoolWriter()->create($discountVoucherPoolTransfer);
    }

    /**
     * @param DiscountVoucherPool $discountVoucherPoolTransfer
     * @return SpyDiscountVoucher
     */
    public function updateDiscountVoucherPool(DiscountVoucherPool $discountVoucherPoolTransfer)
    {
        return $this->getDependencyContainer()->getDiscountVoucherPoolWriter()->update($discountVoucherPoolTransfer);
    }

    /**
     * @param DiscountVoucherPoolCategory $discountVoucherPoolCategoryTransfer
     * @return SpyDiscountVoucherPoolCategory
     */
    public function createDiscountVoucherPoolCategory(DiscountVoucherPoolCategory $discountVoucherPoolCategoryTransfer)
    {
        return $this->getDependencyContainer()->getDiscountVoucherPoolCategoryWriter()
            ->create($discountVoucherPoolCategoryTransfer);
    }

    /**
     * @param DiscountVoucherPoolCategory $discountVoucherPoolCategoryTransfer
     * @return SpyDiscountVoucherPoolCategory
     */
    public function updateDiscountVoucherPoolCategory(DiscountVoucherPoolCategory $discountVoucherPoolCategoryTransfer)
    {
        return $this->getDependencyContainer()->getDiscountVoucherPoolCategoryWriter()
            ->update($discountVoucherPoolCategoryTransfer);
    }

    /**
     * @param string $pluginName
     * @return DiscountCalculatorPluginInterface
     */
    public function getCalculatorPluginByName($pluginName)
    {
        return $this->getDependencyContainer()->getConfig()->getCalculatorPluginByName($pluginName);
    }

    /**
     * @param DiscountableContainerInterface $container
     * @return array
     */
    public function getDiscountableItems(DiscountableContainerInterface $container)
    {
        return $this->getDependencyContainer()->getItemCollector()->collect($container);
    }

    /**
     * @param DiscountableContainerInterface $container
     * @return DiscountableContainerInterface[]
     */
    public function getDiscountableItemExpenses(DiscountableContainerInterface $container)
    {
        return $this->getDependencyContainer()->getItemExpenseCollector()->collect($container);
    }

    /**
     * @param DiscountableContainerInterface $container
     * @return DiscountableContainerInterface[]
     */
    public function getDiscountableOrderExpenses(DiscountableContainerInterface $container)
    {
        return $this->getDependencyContainer()->getOrderExpenseCollector()->collect($container);
    }

    /**
     * @return array
     */
    public function getDiscountCollectors()
    {
        return array_keys($this->getDependencyContainer()->getAvailableCollectorPlugins());
    }

    /**
     * @return array
     */
    public function getDiscountCalculators()
    {
        return array_keys($this->getDependencyContainer()->getAvailableCalculatorPlugins());
    }
}

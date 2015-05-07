<?php

namespace SprykerFeature\Zed\Discount\Business;

use Generated\Shared\Transfer\DiscountDiscountTransfer;
use Generated\Shared\Transfer\DiscountDiscountDecisionRuleTransfer;
use Generated\Shared\Transfer\DiscountDiscountVoucherTransfer;
use Generated\Shared\Transfer\DiscountDiscountVoucherPoolTransfer;
use Generated\Shared\Transfer\DiscountDiscountVoucherPoolCategoryTransfer;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;
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
     * @param DiscountDiscountTransfer $discountTransfer
     * @return SpyDiscount
     */
    public function createDiscount(DiscountDiscountTransfer $discountTransfer)
    {
        return $this->getDependencyContainer()->getDiscountWriter()->create($discountTransfer);
    }

    /**
     * @param DiscountDiscountTransfer $discountTransfer
     * @return SpyDiscount
     */
    public function updateDiscount(DiscountDiscountTransfer $discountTransfer)
    {
        return $this->getDependencyContainer()->getDiscountWriter()->update($discountTransfer);
    }

    /**
     * @param DiscountDiscountDecisionRuleTransfer $discountDecisionRuleTransfer
     * @return SpyDiscountDecisionRule
     */
    public function createDiscountDecisionRule(DiscountDiscountDecisionRuleTransfer $discountDecisionRuleTransfer)
    {
        return $this->getDependencyContainer()->getDiscountDecisionRuleWriter()->create($discountDecisionRuleTransfer);
    }

    /**
     * @param DiscountDiscountDecisionRuleTransfer $discountDecisionRuleTransfer
     * @return SpyDiscountDecisionRule
     */
    public function updateDiscountDecisionRule(DiscountDiscountDecisionRuleTransfer $discountDecisionRuleTransfer)
    {
        return $this->getDependencyContainer()->getDiscountDecisionRuleWriter()->update($discountDecisionRuleTransfer);
    }

    /**
     * @param DiscountDiscountVoucherTransfer $discountVoucherTransfer
     * @return SpyDiscountVoucher
     */
    public function createDiscountVoucher(DiscountDiscountVoucherTransfer $discountVoucherTransfer)
    {
        return $this->getDependencyContainer()->getDiscountVoucherWriter()->create($discountVoucherTransfer);
    }

    /**
     * @param DiscountDiscountVoucherTransfer $discountVoucherTransfer
     * @return SpyDiscountVoucher
     */
    public function updateDiscountVoucher(DiscountDiscountVoucherTransfer $discountVoucherTransfer)
    {
        return $this->getDependencyContainer()->getDiscountVoucherWriter()->update($discountVoucherTransfer);
    }

    /**
     * @param DiscountDiscountVoucherPoolTransfer $discountVoucherPoolTransfer
     * @return SpyDiscountVoucher
     */
    public function createDiscountVoucherPool(DiscountDiscountVoucherPoolTransfer $discountVoucherPoolTransfer)
    {
        return $this->getDependencyContainer()->getDiscountVoucherPoolWriter()->create($discountVoucherPoolTransfer);
    }

    /**
     * @param DiscountDiscountVoucherPoolTransfer $discountVoucherPoolTransfer
     * @return SpyDiscountVoucher
     */
    public function updateDiscountVoucherPool(DiscountDiscountVoucherPoolTransfer $discountVoucherPoolTransfer)
    {
        return $this->getDependencyContainer()->getDiscountVoucherPoolWriter()->update($discountVoucherPoolTransfer);
    }

    /**
     * @param DiscountDiscountVoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer
     * @return SpyDiscountVoucherPoolCategory
     */
    public function createDiscountVoucherPoolCategory(DiscountDiscountVoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer)
    {
        return $this->getDependencyContainer()->getDiscountVoucherPoolCategoryWriter()
            ->create($discountVoucherPoolCategoryTransfer);
    }

    /**
     * @param DiscountDiscountVoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer
     * @return SpyDiscountVoucherPoolCategory
     */
    public function updateDiscountVoucherPoolCategory(DiscountDiscountVoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer)
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

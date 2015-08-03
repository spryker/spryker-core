<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business;

use Generated\Shared\Discount\OrderInterface;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\DecisionRuleTransfer;
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
use Symfony\Component\HttpFoundation\Response;

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
        return $this->getDependencyContainer()->getDiscount($container)->calculate();
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
     * @return $this|ModelResult
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
        return $this->getDependencyContainer()->getCalculatorPercentage()->calculate($discountableObjects, $percentage);
    }

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param float $amount
     *
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
     *
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
     * @param DiscountTransfer $discountTransfer
     *
     * @return SpyDiscount
     */
    public function createDiscount(DiscountTransfer $discountTransfer)
    {
        return $this->getDependencyContainer()->getDiscountWriter()->create($discountTransfer);
    }

    /**
     * @param DiscountTransfer $discountTransfer
     *
     * @return SpyDiscount
     */
    public function updateDiscount(DiscountTransfer $discountTransfer)
    {
        return $this->getDependencyContainer()->getDiscountWriter()->update($discountTransfer);
    }

    /**
     * @param int $idDiscount
     *
     * @return array
     */
    public function toggleDiscountActiveStatus($idDiscount)
    {
        $response = $this->getDependencyContainer()->getDiscountWriter()->toggleActiveStatus($idDiscount);

        return [
            'code' => Response::HTTP_OK,
            'id' => $response->getIdDiscount(),
            'newStaus' => $response->getIsActive(),
        ];
    }

    /**
     * @param DecisionRuleTransfer $discountDecisionRuleTransfer
     *
     * @return SpyDiscountDecisionRule
     */
    public function createDiscountDecisionRule(DecisionRuleTransfer $discountDecisionRuleTransfer)
    {
        return $this->getDependencyContainer()->getDiscountDecisionRuleWriter()->create($discountDecisionRuleTransfer);
    }

    /**
     * @param DecisionRuleTransfer $discountDecisionRuleTransfer
     *
     * @return SpyDiscountDecisionRule
     */
    public function updateDiscountDecisionRule(DecisionRuleTransfer $discountDecisionRuleTransfer)
    {
        return $this->getDependencyContainer()->getDiscountDecisionRuleWriter()->update($discountDecisionRuleTransfer);
    }

    /**
     * @param VoucherTransfer $discountVoucherTransfer
     *
     * @return SpyDiscountVoucher
     */
    public function createDiscountVoucher(VoucherTransfer $discountVoucherTransfer)
    {
        return $this->getDependencyContainer()->getDiscountVoucherWriter()->create($discountVoucherTransfer);
    }

    /**
     * @param VoucherTransfer $discountVoucherTransfer
     *
     * @return SpyDiscountVoucher
     */
    public function updateDiscountVoucher(VoucherTransfer $discountVoucherTransfer)
    {
        return $this->getDependencyContainer()->getDiscountVoucherWriter()->update($discountVoucherTransfer);
    }

    /**
     * @param VoucherPoolTransfer $discountVoucherPoolTransfer
     *
     * @return SpyDiscountVoucher
     */
    public function createDiscountVoucherPool(VoucherPoolTransfer $discountVoucherPoolTransfer)
    {
        return $this->getDependencyContainer()->getDiscountVoucherPoolWriter()->create($discountVoucherPoolTransfer);
    }

    /**
     * @param VoucherPoolTransfer $discountVoucherPoolTransfer
     *
     * @return SpyDiscountVoucher
     */
    public function updateDiscountVoucherPool(VoucherPoolTransfer $discountVoucherPoolTransfer)
    {
        return $this->getDependencyContainer()->getDiscountVoucherPoolWriter()->update($discountVoucherPoolTransfer);
    }

    /**
     * @param VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer
     *
     * @return SpyDiscountVoucherPoolCategory
     */
    public function createDiscountVoucherPoolCategory(VoucherPoolCategoryTransfer $discountVoucherPoolCategoryTransfer)
    {
        return $this->getDependencyContainer()->getDiscountVoucherPoolCategoryWriter()
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
        return $this->getDependencyContainer()->getDiscountVoucherPoolCategoryWriter()
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
        return $this->getDependencyContainer()->getDiscountVoucherPoolCategoryWriter()
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
     * @param CalculableInterface $container
     *
     * @return array
     */
    public function getDiscountableItems(CalculableInterface $container)
    {
        return $this->getDependencyContainer()->getItemCollector()->collect($container);
    }

    /**
     * @param CalculableInterface $container
     *
     * @return OrderInterface[]
     */
    public function getDiscountableItemExpenses(CalculableInterface $container)
    {
        return $this->getDependencyContainer()->getItemExpenseCollector()->collect($container);
    }

    /**
     * @param CalculableInterface $container
     *
     * @return OrderInterface[]
     */
    public function getDiscountableOrderExpenses(CalculableInterface $container)
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

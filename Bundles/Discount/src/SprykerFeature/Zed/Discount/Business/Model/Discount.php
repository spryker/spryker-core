<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Model;

use Generated\Shared\Discount\OrderInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Communication\Plugin\DecisionRule\AbstractDecisionRule;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;
use SprykerFeature\Zed\Discount\DiscountConfig;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRule;

class Discount
{

    const KEY_DISCOUNTS = 'discounts';
    const KEY_ERRORS = 'errors';

    /**
     * @var DiscountQueryContainer
     */
    protected $queryContainer;

    /**
     * @var DecisionRuleEngine
     */
    protected $decisionRule;

    /**
     * @ var OrderInterface
     *
     * @var CalculableInterface
     */
    protected $discountContainer;

    /**
     * @var DiscountCollectorPluginInterface[]
     */
    protected $collectorPlugins;

    /**
     * @var DiscountCalculatorPluginInterface[]
     */
    protected $calculatorPlugins;

    /**
     * @var DiscountConfig
     */
    protected $discountSettings;

    /**
     * @var CalculatorInterface
     */
    protected $calculator;

    /**
     * @var DistributorInterface
     */
    protected $distributor;

    /**
     * @ param OrderInterface $discountContainer
     *
     * @param CalculableInterface $discountContainer
     * @param DiscountQueryContainer $queryContainer
     * @param DecisionRuleInterface $decisionRule
     * @param DiscountConfig $discountSettings
     * @param CalculatorInterface $calculator
     * @param DistributorInterface $distributor
     */
    public function __construct(
        //OrderInterface $discountContainer,
        CalculableInterface $discountContainer,
        DiscountQueryContainer $queryContainer,
        DecisionRuleInterface $decisionRule,
        DiscountConfig $discountSettings,
        CalculatorInterface $calculator,
        DistributorInterface $distributor
    )
    {
        $this->queryContainer = $queryContainer;
        $this->decisionRule = $decisionRule;
        $this->discountContainer = $discountContainer;
        $this->discountSettings = $discountSettings;
        $this->calculator = $calculator;
        $this->distributor = $distributor;
    }

    /**
     * @return SpyDiscount[]
     */
    public function calculate()
    {
        $result = $this->retrieveDiscountsToBeCalculated();
        $discountsToBeCalculated = $result[self::KEY_DISCOUNTS];

        $this->calculator->calculate(
            $discountsToBeCalculated,
            $this->discountContainer,
            $this->discountSettings,
            $this->distributor
        );

        return $result;
    }

    /**
     * @return SpyDiscount[]
     */
    public function retrieveActiveAndRunningDiscounts()
    {
        return $this->queryContainer->queryActiveAndRunningDiscounts()->find();
    }

    /**
     * @return array
     */
    protected function retrieveDiscountsToBeCalculated()
    {
        $discounts = $this->retrieveActiveAndRunningDiscounts();
        $discountsToBeCalculated = [];
        $errors = [];

        foreach ($discounts as $discount) {
            $result = $this->decisionRule->evaluate(
                $discount,
                $this->discountContainer,
                $this->getDecisionRulePlugins($discount->getPrimaryKey())
            );

            if ($result->isSuccess()) {
                $discountsToBeCalculated[] = $discount;
            } else {
                $errors = array_merge($errors, $result->getErrors());
            }
        }

        return [
            self::KEY_DISCOUNTS => $discountsToBeCalculated,
            self::KEY_ERRORS => $errors,
        ];
    }

    /**
     * @param int $idDiscount
     *
     * @return DiscountDecisionRulePluginInterface[]
     */
    protected function getDecisionRulePlugins($idDiscount)
    {
        $plugins = [];

        $defaultVoucherDecisionRulePlugin = $this->getDefaultVoucherDecisionRulePluginIfNeeded($idDiscount);

        if ($defaultVoucherDecisionRulePlugin) {
            $plugins[] = $defaultVoucherDecisionRulePlugin;
        }

        $decisionRules = $this->retrieveDecisionRules($idDiscount);
        foreach ($decisionRules as $decisionRule) {
            $decisionRulePlugin = $this->discountSettings->getDecisionRulePluginByName(
                $decisionRule->getDecisionRulePlugin()
            );

            $decisionRulePlugin->setContext(
                [
                    AbstractDecisionRule::KEY_ENTITY => $decisionRule,
                ]
            );

            $plugins[] = $decisionRulePlugin;
        }

        return $plugins;
    }

    /**
     * @param int $idDiscount
     *
     * @return SpyDiscountDecisionRule[]
     */
    protected function retrieveDecisionRules($idDiscount)
    {
        $decisionRules = $this->queryContainer->queryDecisionRules($idDiscount)->find();

        return $decisionRules;
    }

    /**
     * @param int $idDiscount
     *
     * @return null|DiscountDecisionRulePluginInterface
     */
    protected function getDefaultVoucherDecisionRulePluginIfNeeded($idDiscount)
    {
        if (count($this->discountContainer->getCalculableObject()->getCouponCodes()) === 0) {
            return;
        }

        $discount = $this->queryContainer->queryDiscount()->findPk($idDiscount);

        if ($discount->getFkDiscountVoucherPool()) {
            $plugin = $this->discountSettings->getDefaultVoucherDecisionRulePlugin();
            $plugin->setContext(
                [
                    AbstractDecisionRule::KEY_DATA => $discount->getFkDiscountVoucherPool(),
                ]
            );

            return $plugin;
        }

        return;
    }

}

<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\DiscountTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Business\Distributor\DistributorInterface;
use SprykerFeature\Zed\Discount\Communication\Plugin\DecisionRule\AbstractDecisionRule;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;
use SprykerFeature\Zed\Discount\DiscountConfig;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRule;

class Discount
{

    const KEY_DISCOUNTS = 'discounts';
    const KEY_ERRORS = 'errors';

    /**
     * @var DiscountQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var DecisionRuleEngine
     */
    protected $decisionRule;

    /**
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
     * @param CalculableInterface $discountContainer
     * @param DiscountQueryContainer $queryContainer
     * @param DecisionRuleInterface $decisionRule
     * @param DiscountConfig $discountSettings
     * @param CalculatorInterface $calculator
     * @param DistributorInterface $distributor
     */
    public function __construct(
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
     * @param array|string[] $couponCodes
     *
     * @return SpyDiscount[]
     */
    public function retrieveActiveCartAndVoucherDiscounts(array $couponCodes = [])
    {
        return $this->queryContainer->queryCartRulesIncludingSpecifiedVouchers($couponCodes)->find();
    }

    /**
     * @return array
     */
    protected function retrieveDiscountsToBeCalculated()
    {
        $discounts = $this->retrieveActiveCartAndVoucherDiscounts(
            $this->getCouponCodes()
        );
        $discountsToBeCalculated = [];
        $errors = [];

        foreach ($discounts as $discount) {
            $discountTransfer = new DiscountTransfer();
            $discountTransfer->fromArray($discount->toArray(), true);
            if ($discount->getUsedVoucherCode() !== null) {
                $discountTransfer->addUsedCode($discount->getUsedVoucherCode());
            }

            $result = $this->decisionRule->evaluate(
                $discountTransfer,
                $this->discountContainer,
                $this->getDecisionRulePlugins($discount->getPrimaryKey())
            );

            if ($result->isSuccess()) {
                $discountsToBeCalculated[] = $discountTransfer;
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
        foreach ($decisionRules as $decisionRuleEntity) {
            $decisionRulePlugin = $this->discountSettings->getDecisionRulePluginByName(
                $decisionRuleEntity->getDecisionRulePlugin()
            );

            $decisionRulePlugin->setContext(
                [
                    AbstractDecisionRule::KEY_ENTITY => $decisionRuleEntity,
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
            return null;
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

        return null;
    }

    /**
     * @return array
     */
    protected function getCouponCodes()
    {
        $couponCodes = $this->discountContainer->getCalculableObject()->getCouponCodes();

        if (0 === count($couponCodes)) {
            return [];
        }

        return $couponCodes;
    }

}

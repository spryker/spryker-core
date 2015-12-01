<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use SprykerEngine\Zed\FlashMessenger\Business\FlashMessengerFacade;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Business\Distributor\DistributorInterface;
use SprykerFeature\Zed\Discount\Communication\Plugin\DecisionRule\AbstractDecisionRule;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;
use SprykerFeature\Zed\Discount\DiscountConfig;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule;

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
     * @var FlashMessengerFacade
     */
    protected $flashMessengerFacade;

    /**
     * @param CalculableInterface $discountContainer
     * @param DiscountQueryContainer $queryContainer
     * @param DecisionRuleInterface $decisionRule
     * @param DiscountConfig $discountSettings
     * @param CalculatorInterface $calculator
     * @param DistributorInterface $distributor
     * @param FlashMessengerFacade $flashMessengerFacade
     */
    public function __construct(
        CalculableInterface $discountContainer,
        DiscountQueryContainer $queryContainer,
        DecisionRuleInterface $decisionRule,
        DiscountConfig $discountSettings,
        CalculatorInterface $calculator,
        DistributorInterface $distributor,
        FlashMessengerFacade $flashMessengerFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->decisionRule = $decisionRule;
        $this->discountContainer = $discountContainer;
        $this->discountSettings = $discountSettings;
        $this->calculator = $calculator;
        $this->distributor = $distributor;
        $this->flashMessengerFacade = $flashMessengerFacade;
    }

    /**
     * @return SpyDiscount[]
     */
    public function calculate()
    {
        $result = $this->retrieveDiscountsToBeCalculated();
        $discountsToBeCalculated = $result[self::KEY_DISCOUNTS];
        $this->setValidationMessages($result[self::KEY_ERRORS]);

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
        $discounts = $this->retrieveActiveCartAndVoucherDiscounts($this->getCouponCodes());

        $discountsToBeCalculated = [];
        $decisionRuleValidationErrors = [];

        foreach ($discounts as $discountEntity) {
            $discountTransfer = $this->hydrateDiscountTransfer($discountEntity);
            $decisionRulePlugins = $this->getDecisionRulePlugins($discountEntity->getPrimaryKey());

            $result = $this->decisionRule->evaluate($discountTransfer, $this->discountContainer, $decisionRulePlugins);

            if ($result->isSuccess()) {
                $discountsToBeCalculated[] = $discountTransfer;
            } else {
                $decisionRuleValidationErrors = array_merge($decisionRuleValidationErrors, $result->getErrors());
            }
        }

        return [
            self::KEY_DISCOUNTS => $discountsToBeCalculated,
            self::KEY_ERRORS => $decisionRuleValidationErrors,
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
     * @return array
     */
    protected function getCouponCodes()
    {
        $couponCodes = $this->discountContainer->getCalculableObject()->getCouponCodes();

        if (count($couponCodes) === 0) {
            return [];
        }

        return $couponCodes;
    }

    /**
     * @param SpyDiscount $discountEntity
     *
     * @return DiscountTransfer
     */
    protected function hydrateDiscountTransfer(SpyDiscount $discountEntity)
    {
        $discountTransfer = new DiscountTransfer();
        $discountTransfer->fromArray($discountEntity->toArray(), true);

        if ($discountEntity->getUsedVoucherCode() !== null) {
            $discountTransfer->addUsedCode($discountEntity->getUsedVoucherCode());
        }

        foreach ($discountEntity->getDiscountCollectors() as $discountCollectorEntity) {
            $discountCollectorTransfer = new DiscountCollectorTransfer();
            $discountCollectorTransfer->fromArray($discountCollectorEntity->toArray(), false);
            $discountTransfer->addDiscountCollectors($discountCollectorTransfer);
        }

        return $discountTransfer;
    }

    /**
     * @param array|string[] $errors
     *
     * @return void
     */
    protected function setValidationMessages(array $errors = [])
    {
        foreach ($errors as $errorMessage) {
            $this->flashMessengerFacade->addErrorMessage($errorMessage);
        }
    }

}

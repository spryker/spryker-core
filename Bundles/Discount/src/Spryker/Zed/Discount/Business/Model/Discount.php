<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Discount\Business\Distributor\DistributorInterface;
use Spryker\Zed\Discount\Communication\Plugin\DecisionRule\AbstractDecisionRule;
use Spryker\Zed\Discount\DiscountConfig;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainer;
use Orm\Zed\Discount\Persistence\SpyDiscount;

class Discount
{

    const KEY_DISCOUNTS = 'discounts';
    const KEY_ERRORS = 'errors';

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Discount\Business\Model\DecisionRuleEngine
     */
    protected $decisionRule;

    /**
     * @var \Spryker\Zed\Calculation\Business\Model\CalculableInterface
     */
    protected $discountContainer;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface[]
     */
    protected $collectorPlugins;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[]
     */
    protected $calculatorPlugins;

    /**
     * @var \Spryker\Zed\Discount\DiscountConfig
     */
    protected $discountSettings;

    /**
     * @var \Spryker\Zed\Discount\Business\Model\CalculatorInterface
     */
    protected $calculator;

    /**
     * @var \Spryker\Zed\Discount\Business\Distributor\DistributorInterface
     */
    protected $distributor;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $discountContainer
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainer $queryContainer
     * @param \Spryker\Zed\Discount\Business\Model\DecisionRuleInterface $decisionRule
     * @param \Spryker\Zed\Discount\DiscountConfig $discountSettings
     * @param \Spryker\Zed\Discount\Business\Model\CalculatorInterface $calculator
     * @param \Spryker\Zed\Discount\Business\Distributor\DistributorInterface $distributor
     * @param \Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface $messengerFacade
     */
    public function __construct(
        CalculableInterface $discountContainer,
        DiscountQueryContainer $queryContainer,
        DecisionRuleInterface $decisionRule,
        DiscountConfig $discountSettings,
        CalculatorInterface $calculator,
        DistributorInterface $distributor,
        DiscountToMessengerInterface $messengerFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->decisionRule = $decisionRule;
        $this->discountContainer = $discountContainer;
        $this->discountSettings = $discountSettings;
        $this->calculator = $calculator;
        $this->distributor = $distributor;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount[]
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
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount[]
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
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface[]
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
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule[]
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
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
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
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setValue($errorMessage);

            $this->messengerFacade->addErrorMessage($messageTransfer);
        }
    }

}

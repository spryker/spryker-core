<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Spryker\Zed\Discount\Business\Distributor\DistributorInterface;
use Spryker\Zed\Discount\Communication\Plugin\DecisionRule\AbstractDecisionRule;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainer;

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
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

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
     * @var \Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface[]
     */
    protected $decisionRulePlugins;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainer $queryContainer
     * @param \Spryker\Zed\Discount\Business\Model\DecisionRuleInterface $decisionRule
     * @param \Spryker\Zed\Discount\Business\Model\CalculatorInterface $calculator
     * @param \Spryker\Zed\Discount\Business\Distributor\DistributorInterface $distributor
     * @param \Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface $messengerFacade
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface[] $decisionRulePlugins
     */
    public function __construct(
        QuoteTransfer $quoteTransfer,
        DiscountQueryContainer $queryContainer,
        DecisionRuleInterface $decisionRule,
        CalculatorInterface $calculator,
        DistributorInterface $distributor,
        DiscountToMessengerInterface $messengerFacade,
        array $decisionRulePlugins
    ) {
        $this->queryContainer = $queryContainer;
        $this->decisionRule = $decisionRule;
        $this->quoteTransfer = $quoteTransfer;
        $this->calculator = $calculator;
        $this->distributor = $distributor;
        $this->decisionRulePlugins = $decisionRulePlugins;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @return array
     */
    public function calculate()
    {
        $result = $this->retrieveDiscountsToBeCalculated();
        $discountsToBeCalculated = $result[self::KEY_DISCOUNTS];
        $this->setValidationMessages($result[self::KEY_ERRORS]);

        $calculatedDiscounts = $this->calculator->calculate(
            $discountsToBeCalculated,
            $this->quoteTransfer,
            $this->distributor
        );

        $this->addDiscountsToQuote($this->quoteTransfer, $calculatedDiscounts);

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer[] $discounts
     *
     * @return void
     */
    protected function addDiscountsToQuote(QuoteTransfer $quoteTransfer, array $discounts)
    {
        $quoteTransfer->setVoucherDiscounts(new \ArrayObject());
        $quoteTransfer->setCartRuleDiscounts(new \ArrayObject());

        foreach ($discounts as $discount) {
            $discountTransferCopy = $discount[Calculator::KEY_DISCOUNT_TRANSFER];
            if (!empty($discountTransferCopy->getVoucherCode())) {
                $quoteTransfer->addVoucherDiscount($discountTransferCopy);
            } else {
                $quoteTransfer->addCartRuleDiscount($discountTransferCopy);
            }
        }
    }

    /**
     * @param string[] $couponCodes
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
        $discounts = $this->retrieveActiveCartAndVoucherDiscounts($this->getVoucherCodes());

        $discountsToBeCalculated = [];
        $decisionRuleValidationErrors = [];

        foreach ($discounts as $discountEntity) {
            $discountTransfer = $this->hydrateDiscountTransfer($discountEntity);
            $decisionRulePlugins = $this->getDecisionRulePlugins($discountEntity->getPrimaryKey());

            $result = $this->decisionRule->evaluate($discountTransfer, $this->quoteTransfer, $decisionRulePlugins);

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
            $decisionRulePlugin = $this->decisionRulePlugins[$decisionRuleEntity->getDecisionRulePlugin()];

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
     * @return string[]
     */
    protected function getVoucherCodes()
    {
        $voucherDiscounts = $this->quoteTransfer->getVoucherDiscounts();

        if (count($voucherDiscounts) === 0) {
            return [];
        }

        $voucherCodes = [];
        foreach ($voucherDiscounts as $voucherDiscount) {
            $voucherCodes[] = $voucherDiscount->getVoucherCode();
        }

        return $voucherCodes;
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
            $discountTransfer->setVoucherCode($discountEntity->getUsedVoucherCode());
        }

        foreach ($discountEntity->getDiscountCollectors() as $discountCollectorEntity) {
            $discountCollectorTransfer = new DiscountCollectorTransfer();
            $discountCollectorTransfer->fromArray($discountCollectorEntity->toArray(), false);
            $discountTransfer->addDiscountCollectors($discountCollectorTransfer);
        }

        return $discountTransfer;
    }

    /**
     * @param string[] $errors
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

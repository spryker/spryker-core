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
use Spryker\Zed\Discount\Business\Exception\QueryStringException;
use Spryker\Zed\Discount\Business\QueryString\Parser;
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
     * @var \Spryker\Zed\Discount\Business\Model\CalculatorInterface
     */
    protected $calculator;

    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\Parser
     */
    protected $decisionRuleQueryStringParser;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainer $queryContainer
     * @param \Spryker\Zed\Discount\Business\Model\CalculatorInterface $calculator
     * @param \Spryker\Zed\Discount\Business\QueryString\Parser $decisionRuleQueryStringParser
     */
    public function __construct(
        DiscountQueryContainer $queryContainer,
        CalculatorInterface $calculator,
        Parser $decisionRuleQueryStringParser
    ) {
        $this->queryContainer = $queryContainer;
        $this->calculator = $calculator;
        $this->decisionRuleQueryStringParser = $decisionRuleQueryStringParser;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @return array|\Generated\Shared\Transfer\QuoteTransfer[]
     */
    public function calculate(QuoteTransfer $quoteTransfer)
    {
        $discountsToBeCalculated = $this->retrieveDiscountsToBeCalculated($quoteTransfer);

        $calculatedDiscounts = $this->calculator->calculate($discountsToBeCalculated, $quoteTransfer);

        $this->addCalculatedDiscountsToQuote($quoteTransfer, $calculatedDiscounts);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer[] $discounts
     *
     * @return void
     */
    protected function addCalculatedDiscountsToQuote(QuoteTransfer $quoteTransfer, array $discounts)
    {
        $quoteTransfer->setVoucherDiscounts(new \ArrayObject());
        $quoteTransfer->setCartRuleDiscounts(new \ArrayObject());

        foreach ($discounts as $discount) {
            $discountTransfer = $discount[Calculator::KEY_DISCOUNT_TRANSFER];
            if (!empty($discountTransfer->getVoucherCode())) {
                $quoteTransfer->addVoucherDiscount($discountTransfer);
            } else {
                $quoteTransfer->addCartRuleDiscount($discountTransfer);
            }
        }
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @return array|\Generated\Shared\Transfer\DiscountTransfer[]
     */
    protected function retrieveDiscountsToBeCalculated(QuoteTransfer $quoteTransfer)
    {
        $discounts = $this->retrieveActiveCartAndVoucherDiscounts($this->getVoucherCodes($quoteTransfer));

        $discountsToBeCalculated = [];
        foreach ($discounts as $discountEntity) {
            try {
                $isSatisfied = $this->decisionRuleQueryStringParser->parse(
                    $quoteTransfer,
                    $discountEntity->getDecisionRuleQueryString()
                );

                if ($isSatisfied === true) {
                    $discountsToBeCalculated[] = $this->hydrateDiscountTransfer($discountEntity);
                }
            } catch (QueryStringException $e) {
               //@todo log exception
            }
        }

        return $discountsToBeCalculated;

    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array|string[]
     */
    protected function getVoucherCodes(QuoteTransfer $quoteTransfer)
    {
        $voucherDiscounts = $quoteTransfer->getVoucherDiscounts();

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
}

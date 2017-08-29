<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Discount\Business\Exception\QueryStringException;
use Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface;
use Spryker\Zed\Discount\Business\Voucher\VoucherValidatorInterface;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class Discount implements DiscountInterface
{

    use LoggerTrait;

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Discount\Business\Calculator\CalculatorInterface
     */
    protected $calculator;

    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface
     */
    protected $decisionRuleBuilder;

    /**
     * @var \Spryker\Zed\Discount\Business\Voucher\VoucherValidatorInterface
     */
    protected $voucherValidator;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Discount\Business\Calculator\CalculatorInterface $calculator
     * @param \Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface $decisionRuleBuilder
     * @param \Spryker\Zed\Discount\Business\Voucher\VoucherValidatorInterface $voucherValidator
     */
    public function __construct(
        DiscountQueryContainerInterface $queryContainer,
        CalculatorInterface $calculator,
        SpecificationBuilderInterface $decisionRuleBuilder,
        VoucherValidatorInterface $voucherValidator
    ) {
        $this->queryContainer = $queryContainer;
        $this->calculator = $calculator;
        $this->decisionRuleBuilder = $decisionRuleBuilder;
        $this->voucherValidator = $voucherValidator;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function calculate(QuoteTransfer $quoteTransfer)
    {
        $applicableDiscounts = $this->getApplicableDiscounts($quoteTransfer);
        $collectedDiscounts = $this->calculator->calculate($applicableDiscounts, $quoteTransfer);
        $this->addDiscountsToQuote($quoteTransfer, $collectedDiscounts);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer[] $collectedDiscounts
     *
     * @return void
     */
    protected function addDiscountsToQuote(QuoteTransfer $quoteTransfer, array $collectedDiscounts)
    {
        $quoteTransfer->setVoucherDiscounts(new ArrayObject());
        $quoteTransfer->setCartRuleDiscounts(new ArrayObject());

        foreach ($collectedDiscounts as $collectedDiscountTransfer) {
            $discountTransfer = $collectedDiscountTransfer->getDiscount();
            if ($discountTransfer->getVoucherCode()) {
                $quoteTransfer->addVoucherDiscount($discountTransfer);
            } else {
                $quoteTransfer->addCartRuleDiscount($discountTransfer);
            }
        }
    }

    /**
     * @param string[] $voucherCodes
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount[]
     */
    protected function retrieveActiveCartAndVoucherDiscounts(array $voucherCodes = [])
    {
        $discounts = $this->queryContainer
            ->queryActiveCartRules()
            ->find();

        if (count($voucherCodes) > 0) {
            $voucherDiscounts = $this->queryContainer
                ->queryDiscountsBySpecifiedVouchers($voucherCodes)
                ->find();

            $voucherDiscounts = $this->filterUniqueVoucherDiscounts($voucherDiscounts);

            if (count($discounts) == 0) {
                return $voucherDiscounts;
            }

            foreach ($voucherDiscounts as $discountEntity) {
                $discounts->append($discountEntity);
            }

        }

        return $discounts;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount[]|\Propel\Runtime\Collection\Collection $voucherDiscounts
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function filterUniqueVoucherDiscounts(Collection $voucherDiscounts)
    {
        $uniqueVoucherDiscounts = new ObjectCollection();
        foreach ($voucherDiscounts as $discountEntity) {
            $idDiscount = $discountEntity->getIdDiscount();
            if (isset($uniqueVoucherDiscounts[$idDiscount])) {
                continue;
            }

            $uniqueVoucherDiscounts[$idDiscount] = $discountEntity;
        }

        return $uniqueVoucherDiscounts;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer[]
     */
    protected function getApplicableDiscounts(QuoteTransfer $quoteTransfer)
    {
        $discounts = $this->retrieveActiveCartAndVoucherDiscounts(
            $this->getVoucherCodes($quoteTransfer)
        );

        $applicableDiscounts = [];
        foreach ($discounts as $discountEntity) {
            if (!$this->isDiscountApplicable($quoteTransfer, $discountEntity)) {
                continue;
            }

            $applicableDiscounts[] = $this->hydrateDiscountTransfer($discountEntity);
        }

        return $applicableDiscounts;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    protected function getVoucherCodes(QuoteTransfer $quoteTransfer)
    {
        $voucherDiscounts = $quoteTransfer->getVoucherDiscounts();

        $voucherCodes = [];
        foreach ($voucherDiscounts as $voucherDiscountTransfer) {
            $voucherCodes[] = $voucherDiscountTransfer->getVoucherCode();
        }

        $voucherCodes = array_merge($voucherCodes, (array)$quoteTransfer->getUsedNotAppliedVoucherCodes());

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

        return $discountTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return bool
     */
    protected function isDiscountApplicable(QuoteTransfer $quoteTransfer, SpyDiscount $discountEntity)
    {
        if ($discountEntity->getDiscountType() === DiscountConstants::TYPE_VOUCHER) {
            $voucherCode = $discountEntity->getVoucherCode();
            if ($this->voucherValidator->isUsable($voucherCode) === false) {
                return false;
            }
        }

        $queryString = $discountEntity->getDecisionRuleQueryString();
        if (!$queryString) {
            return true;
        }

        try {
            $compositeSpecification = $this->decisionRuleBuilder
                ->buildFromQueryString($queryString);

            foreach ($quoteTransfer->getItems() as $itemTransfer) {
                if ($compositeSpecification->isSatisfiedBy($quoteTransfer, $itemTransfer) === true) {
                    return true;
                }
            }

        } catch (QueryStringException $exception) {
            $this->getLogger()->warning($exception->getMessage(), ['exception' => $exception]);
        }

        return false;
    }

}

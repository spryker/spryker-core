<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Discount\Business\Exception\QueryStringException;
use Spryker\Zed\Discount\Business\Persistence\DiscountEntityMapperInterface;
use Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface;
use Spryker\Zed\Discount\Business\Voucher\VoucherValidatorInterface;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToStoreFacadeInterface;
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
     * @var \Spryker\Zed\Discount\Dependency\Plugin\DiscountApplicableFilterPluginInterface[]
     */
    protected $discountApplicableFilterPlugins = [];

    /**
     * @var \Spryker\Zed\Discount\Business\Persistence\DiscountEntityMapperInterface
     */
    protected $discountEntityMapper;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Facade\DiscountToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Discount\Business\Calculator\CalculatorInterface $calculator
     * @param \Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface $decisionRuleBuilder
     * @param \Spryker\Zed\Discount\Business\Voucher\VoucherValidatorInterface $voucherValidator
     * @param \Spryker\Zed\Discount\Business\Persistence\DiscountEntityMapperInterface $discountEntityMapper
     * @param \Spryker\Zed\Discount\Dependency\Facade\DiscountToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        DiscountQueryContainerInterface $queryContainer,
        CalculatorInterface $calculator,
        SpecificationBuilderInterface $decisionRuleBuilder,
        VoucherValidatorInterface $voucherValidator,
        DiscountEntityMapperInterface $discountEntityMapper,
        DiscountToStoreFacadeInterface $storeFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->calculator = $calculator;
        $this->decisionRuleBuilder = $decisionRuleBuilder;
        $this->voucherValidator = $voucherValidator;
        $this->discountEntityMapper = $discountEntityMapper;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function calculate(QuoteTransfer $quoteTransfer)
    {
        $activeDiscounts = $this->retrieveActiveCartAndVoucherDiscounts(
            $this->getVoucherCodes($quoteTransfer),
            $this->getIdStore($quoteTransfer->getStore())
        );

        [$applicableDiscounts, $nonApplicableDiscounts] = $this->splitDiscountsByApplicability($activeDiscounts, $quoteTransfer);

        $collectedDiscounts = $this->calculator->calculate($applicableDiscounts, $quoteTransfer);

        $this->addDiscountsToQuote($quoteTransfer, $collectedDiscounts);
        $this->addNonApplicableDiscountsToQuote($quoteTransfer, $nonApplicableDiscounts);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer[] $discounts
     *
     * @return void
     */
    protected function addNonApplicableDiscountsToQuote(QuoteTransfer $quoteTransfer, array $discounts): void
    {
        $usedNotAppliedVoucherCodes = $quoteTransfer->getUsedNotAppliedVoucherCodes();

        foreach ($discounts as $discount) {
            if ($discount->getVoucherCode() && !in_array($discount->getVoucherCode(), $usedNotAppliedVoucherCodes)) {
                $quoteTransfer->addUsedNotAppliedVoucherCode($discount->getVoucherCode());
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer[] $collectedDiscounts
     *
     * @return void
     */
    protected function addDiscountsToQuote(QuoteTransfer $quoteTransfer, array $collectedDiscounts): void
    {
        $quoteTransfer->setVoucherDiscounts(new ArrayObject());
        $quoteTransfer->setCartRuleDiscounts(new ArrayObject());

        foreach ($collectedDiscounts as $collectedDiscountTransfer) {
            $discountTransfer = $collectedDiscountTransfer->getDiscount();
            if ($discountTransfer->getVoucherCode()) {
                $quoteTransfer->addVoucherDiscount($discountTransfer);

                continue;
            }

            $quoteTransfer->addCartRuleDiscount($discountTransfer);
        }
    }

    /**
     * @param string[] $voucherCodes
     * @param int $idStore
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount[]
     */
    protected function retrieveActiveCartAndVoucherDiscounts(array $voucherCodes, $idStore): array
    {
        $discounts = $this->queryContainer
            ->queryActiveCartRulesForStore($idStore)
            ->find();

        if (count($voucherCodes) > 0) {
            $voucherDiscounts = $this->queryContainer
                ->queryDiscountsBySpecifiedVouchersForStore($idStore, $voucherCodes)
                ->find();

            foreach ($voucherDiscounts as $discountEntity) {
                $discounts->append($discountEntity);
            }
        }

        return $discounts->getData();
    }

    /**
     * - Returns array of discounts splitted in two arrays by applicability. Applicable discounts first.
     * - Adds only one of the duplicate discounts to the applicable discounts, the rest to the non applicable.
     *
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount[] $discounts
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array [\Orm\Zed\Discount\Persistence\SpyDiscount[], \Orm\Zed\Discount\Persistence\SpyDiscount[]]
     */
    protected function splitDiscountsByApplicability(array $discounts, QuoteTransfer $quoteTransfer)
    {
        $uniqueVoucherDiscounts = [];
        $applicableDiscounts = [];
        $nonApplicableDiscounts = [];
        foreach ($discounts as $key => $discountEntity) {
            if (!$this->isDiscountApplicable($quoteTransfer, $discountEntity) || isset($uniqueVoucherDiscounts[$discountEntity->getIdDiscount()])) {
                $nonApplicableDiscounts[] = $this->hydrateDiscountTransfer($discountEntity, $quoteTransfer);
                continue;
            }

            if ($this->isDiscountEntityOfTypeVoucher($discountEntity)) {
                $uniqueVoucherDiscounts[$discountEntity->getIdDiscount()] = $discountEntity->getIdDiscount();
            }

            $applicableDiscounts[] = $this->hydrateDiscountTransfer($discountEntity, $quoteTransfer);
        }

        return [$applicableDiscounts, $nonApplicableDiscounts];
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    protected function getIdStore(StoreTransfer $storeTransfer)
    {
        if (!$storeTransfer->getIdStore()) {
            $storeTransfer = $this->storeFacade->getStoreByName($storeTransfer->getName());
        }

        return $storeTransfer->getIdStore();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    protected function getVoucherCodes(QuoteTransfer $quoteTransfer): array
    {
        $voucherDiscounts = $quoteTransfer->getVoucherDiscounts();

        $voucherCodes = [];
        foreach ($voucherDiscounts as $voucherDiscountTransfer) {
            $voucherCodes[] = $voucherDiscountTransfer->getVoucherCode();
        }

        return $voucherCodes;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    protected function hydrateDiscountTransfer(SpyDiscount $discountEntity, QuoteTransfer $quoteTransfer): DiscountTransfer
    {
        $discountTransfer = $this->discountEntityMapper->mapFromEntity($discountEntity);
        $discountTransfer->setCurrency($quoteTransfer->getCurrency());
        $discountTransfer->setPriceMode($quoteTransfer->getPriceMode());

        return $discountTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return bool
     */
    protected function isDiscountApplicable(QuoteTransfer $quoteTransfer, SpyDiscount $discountEntity): bool
    {
        if ($this->isDiscountEntityOfTypeVoucher($discountEntity)) {
            if ($this->voucherValidator->isUsable($discountEntity->getVoucherCode()) === false) {
                return false;
            }
        }

        $discountApplicableItems = $this->filterDiscountApplicableItems(
            $quoteTransfer,
            $discountEntity->getIdDiscount()
        );

        if (count($discountApplicableItems) === 0) {
            return false;
        }

        $queryString = $discountEntity->getDecisionRuleQueryString();
        if (!$queryString) {
            return true;
        }

        try {
            $compositeSpecification = $this->decisionRuleBuilder->buildFromQueryString($queryString);

            $discountApplicableItems = $this->filterDiscountApplicableItems(
                $quoteTransfer,
                $discountEntity->getIdDiscount()
            );

            $minimumItemAmount = $discountEntity->getMinimumItemAmount();
            $matchedProductAmount = 0;

            foreach ($discountApplicableItems as $itemTransfer) {
                if ($compositeSpecification->isSatisfiedBy($quoteTransfer, $itemTransfer) !== true) {
                    continue;
                }

                $matchedProductAmount += $itemTransfer->getQuantity();
                if ($matchedProductAmount >= $minimumItemAmount) {
                    return true;
                }
            }
        } catch (QueryStringException $exception) {
            $this->getLogger()->warning($exception->getMessage(), ['exception' => $exception]);
        }

        return false;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return bool
     */
    protected function isDiscountEntityOfTypeVoucher(SpyDiscount $discountEntity): bool
    {
        return $discountEntity->getDiscountType() === DiscountConstants::TYPE_VOUCHER;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idDiscount
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function filterDiscountApplicableItems(QuoteTransfer $quoteTransfer, $idDiscount): array
    {
        if (count($this->discountApplicableFilterPlugins) === 0) {
            return (array)$quoteTransfer->getItems();
        }

        $discountApplicableItems = (array)$quoteTransfer->getItems();
        foreach ($this->discountApplicableFilterPlugins as $discountableItemFilterPlugin) {
            $discountApplicableItems = $discountableItemFilterPlugin->filter($discountApplicableItems, $quoteTransfer, $idDiscount);
        }

        return $discountApplicableItems;
    }

    /**
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountApplicableFilterPluginInterface[] $discountApplicableFilterPlugins
     *
     * @return void
     */
    public function setDiscountApplicableFilterPlugins(array $discountApplicableFilterPlugins)
    {
        $this->discountApplicableFilterPlugins = $discountApplicableFilterPlugins;
    }
}

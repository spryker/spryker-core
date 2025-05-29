<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
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
     * @var string
     */
    protected const METADATA_KEY_ID_DISCOUNT = 'id_discount';

    /**
     * @var string
     */
    protected const ITEM_QUANTITY_DECISION_RULE = 'item-quantity';

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
     * @var array<\Spryker\Zed\Discount\Dependency\Plugin\DiscountApplicableFilterPluginInterface>
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
            $this->getIdStore($quoteTransfer->getStore()),
        );

        $applicableDiscounts = $this->getApplicableDiscountForQuote($activeDiscounts, $quoteTransfer);
        $collectedDiscounts = $this->calculator->calculate($applicableDiscounts, $quoteTransfer);

        $this->addDiscountsToQuote($quoteTransfer, $collectedDiscounts);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<\Generated\Shared\Transfer\CollectedDiscountTransfer> $collectedDiscounts
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
     * @param array<string> $voucherCodes
     * @param int $idStore
     *
     * @return array<\Orm\Zed\Discount\Persistence\SpyDiscount>
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
     * @param array<\Orm\Zed\Discount\Persistence\SpyDiscount> $discounts
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array [\Orm\Zed\Discount\Persistence\SpyDiscount[], \Orm\Zed\Discount\Persistence\SpyDiscount[]]
     */
    protected function getApplicableDiscountForQuote(array $discounts, QuoteTransfer $quoteTransfer)
    {
        $uniqueVoucherDiscounts = [];
        $applicableDiscounts = [];
        foreach ($discounts as $discountEntity) {
            if (!$this->isDiscountApplicable($quoteTransfer, $discountEntity) || isset($uniqueVoucherDiscounts[$discountEntity->getIdDiscount()])) {
                continue;
            }

            if ($this->isDiscountEntityOfTypeVoucher($discountEntity)) {
                $uniqueVoucherDiscounts[$discountEntity->getIdDiscount()] = $discountEntity->getIdDiscount();
            }

            $applicableDiscounts[] = $this->hydrateDiscountTransfer($discountEntity, $quoteTransfer);
        }

        return $applicableDiscounts;
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
     * @return array<string>
     */
    protected function getVoucherCodes(QuoteTransfer $quoteTransfer): array
    {
        $voucherDiscounts = $quoteTransfer->getVoucherDiscounts();

        $voucherCodes = [];
        foreach ($voucherDiscounts as $voucherDiscountTransfer) {
            $voucherCodes[] = $voucherDiscountTransfer->getVoucherCode();
        }

        return array_merge($voucherCodes, $quoteTransfer->getUsedNotAppliedVoucherCodes());
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
            if (!$quoteTransfer->getOrderReference() && $this->voucherValidator->isUsable($discountEntity->getVoucherCode()) === false) {
                return false;
            }
        }

        $discountApplicableItems = $this->filterDiscountApplicableItems(
            $quoteTransfer,
            $discountEntity->getIdDiscount(),
        );

        if (count($discountApplicableItems) === 0) {
            return false;
        }

        $queryString = $discountEntity->getDecisionRuleQueryString();
        if (!$queryString) {
            return true;
        }

        $isDiscountApplicable = false;

        try {
            $metadata = [static::METADATA_KEY_ID_DISCOUNT => $discountEntity->getIdDiscount()];
            /** @var \Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface $compositeSpecification */
            $compositeSpecification = $this->decisionRuleBuilder->buildFromQueryString($queryString, $metadata);

            $minimumItemAmount = $discountEntity->getMinimumItemAmount();
            $matchedProductAmount = 0;

            if ($this->hasDiscountItemQuantityDecisionRule($discountEntity)) {
                $originalItemsCollection = $this->cloneQuoteOriginalItems($quoteTransfer->getItems());
                $quoteTransfer = $this->mergeQuoteItemsBySku($quoteTransfer);
            }

            foreach ($discountApplicableItems as $itemTransfer) {
                if ($compositeSpecification->isSatisfiedBy($quoteTransfer, $itemTransfer) !== true) {
                    continue;
                }

                $matchedProductAmount += $itemTransfer->getQuantity();
                if ($matchedProductAmount >= $minimumItemAmount) {
                    $isDiscountApplicable = true;

                    break;
                }
            }

            $quoteTransfer->setItems($originalItemsCollection ?? $quoteTransfer->getItems());
        } catch (QueryStringException $exception) {
            $this->getLogger()->warning($exception->getMessage(), ['exception' => $exception]);
        }

        return $isDiscountApplicable;
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
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
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
     * @param array<\Spryker\Zed\Discount\Dependency\Plugin\DiscountApplicableFilterPluginInterface> $discountApplicableFilterPlugins
     *
     * @return void
     */
    public function setDiscountApplicableFilterPlugins(array $discountApplicableFilterPlugins)
    {
        $this->discountApplicableFilterPlugins = $discountApplicableFilterPlugins;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return bool
     */
    protected function hasDiscountItemQuantityDecisionRule(SpyDiscount $discountEntity): bool
    {
        return strpos($discountEntity->getDecisionRuleQueryString(), static::ITEM_QUANTITY_DECISION_RULE) !== false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mergeQuoteItemsBySku(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $itemTransfers = $quoteTransfer->getItems();
        /** @var \ArrayObject<string, \Generated\Shared\Transfer\ItemTransfer> $mergedItemsCollection */
        $mergedItemsCollection = new ArrayObject();

        foreach ($itemTransfers as $itemTransfer) {
            $itemSku = $itemTransfer->getSku();
            if ($mergedItemsCollection->offsetExists($itemSku)) {
                $mergedItemsCollection[$itemSku] = $this->mergeQuoteItemTransfer($itemTransfer, $mergedItemsCollection[$itemSku]);

                continue;
            }

            $mergedItemsCollection[$itemSku] = $itemTransfer;
        }

        $quoteTransfer->setItems($mergedItemsCollection);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $mergeableItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $mergedItemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function mergeQuoteItemTransfer(ItemTransfer $mergeableItemTransfer, ItemTransfer $mergedItemTransfer): ItemTransfer
    {
        $mergedItemTransfer->setQuantity($mergeableItemTransfer->getQuantity() + $mergedItemTransfer->getQuantity());

        return $mergedItemTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $quoteItemsCollection
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function cloneQuoteOriginalItems(ArrayObject $quoteItemsCollection): ArrayObject
    {
        /** @var \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $originalItemsCollection */
        $originalItemsCollection = new ArrayObject();
        foreach ($quoteItemsCollection as $itemTransfer) {
            $originalItemsCollection[] = clone $itemTransfer;
        }

        return $originalItemsCollection;
    }
}

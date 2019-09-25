<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\CollectedDiscountTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\DiscountVoucherTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountBusinessFactory getFactory()
 * @method \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface getRepository()
 */
class DiscountFacade extends AbstractFacade implements DiscountFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function calculateDiscounts(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()
            ->createDiscount()
            ->calculate($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isItemSkuSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        ClauseTransfer $clauseTransfer
    ) {
        return $this->getFactory()
            ->createSkuDecisionRule()
            ->isSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isQuoteGrandTotalSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        ClauseTransfer $clauseTransfer
    ) {
        return $this->getFactory()
            ->createGrandTotalDecisionRule()
            ->isSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isTotalQuantitySatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        ClauseTransfer $clauseTransfer
    ) {
        return $this->getFactory()
            ->createTotalQuantityDecisionRule()
            ->isSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isSubTotalSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        ClauseTransfer $clauseTransfer
    ) {

        return $this->getFactory()
            ->createSubTotalDecisionRule()
            ->isSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return array
     */
    public function collectBySku(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer)
    {
        return $this->getFactory()
            ->createSkuCollector()
            ->collect($quoteTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isItemQuantitySatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        ClauseTransfer $clauseTransfer
    ) {

        return $this->getFactory()
            ->createItemQuantityDecisionRule()
            ->isSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collectByItemQuantity(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer)
    {
        return $this->getFactory()
            ->createItemQuantityCollector()
            ->collect($quoteTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isItemPriceSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        ClauseTransfer $clauseTransfer
    ) {
        return $this->getFactory()
            ->createItemPriceDecisionRule()
            ->isSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collectByItemPrice(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer)
    {
        return $this->getFactory()
            ->createItemPriceCollector()
            ->collect($quoteTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isCalendarWeekSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        ClauseTransfer $clauseTransfer
    ) {
        return $this->getFactory()
           ->createCalendarWeekDecisionRule()
           ->isSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isDayOfTheWeekSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        ClauseTransfer $clauseTransfer
    ) {
        return $this->getFactory()
            ->createDayOfWeekDecisionRule()
            ->isSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isMonthSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        ClauseTransfer $clauseTransfer
    ) {

        return $this->getFactory()
            ->createMonthDecisionRule()
            ->isSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isTimeSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        ClauseTransfer $clauseTransfer
    ) {
        return $this->getFactory()
            ->createTimeDecisionRule()
            ->isSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $type
     *
     * @return string[]
     */
    public function getQueryStringFieldsByType($type)
    {
        return $this->getFactory()
            ->createQueryStringMetaDataProviderFactory()
            ->createMetaProviderByType($type)
            ->getAvailableFields();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $type
     * @param string $fieldName
     *
     * @return string[]
     */
    public function getQueryStringFieldExpressionsForField($type, $fieldName)
    {
        return $this->getFactory()
            ->createQueryStringMetaDataProviderFactory()
            ->createMetaProviderByType($type)
            ->getAvailableOperatorExpressionsForField($fieldName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $type
     *
     * @return string[]
     */
    public function getQueryStringComparatorExpressions($type)
    {
        return $this->getFactory()
            ->createQueryStringMetaDataProviderFactory()
            ->createMetaProviderByType($type)
            ->getAvailableComparatorExpressions();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $type
     *
     * @return string[]
     */
    public function getQueryStringLogicalComparators($type)
    {
        return $this->getFactory()
            ->createQueryStringMetaDataProviderFactory()
            ->createMetaProviderByType($type)
            ->getLogicalComparators();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param string $compareWith
     *
     * @return bool
     */
    public function queryStringCompare(ClauseTransfer $clauseTransfer, $compareWith)
    {
        return $this->getFactory()
            ->createComparatorOperators()
            ->compare($clauseTransfer, $compareWith);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $type
     * @param string $queryString
     *
     * @return string[]
     */
    public function validateQueryStringByType($type, $queryString)
    {
        return $this->getFactory()
            ->createQueryStringValidator()
            ->validateByType($type, $queryString);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfigurator
     *
     * @return int
     */
    public function saveDiscount(DiscountConfiguratorTransfer $discountConfigurator)
    {
        return $this->getFactory()
            ->createDiscountPersist()
            ->save($discountConfigurator);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfigurator
     *
     * @return bool
     */
    public function updateDiscount(DiscountConfiguratorTransfer $discountConfigurator)
    {
        return $this->getFactory()
            ->createDiscountPersist()
            ->update($discountConfigurator);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated use `findHydratedDiscountConfiguratorByIdDiscount()` instead.
     *
     * @param int $idDiscount
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    public function getHydratedDiscountConfiguratorByIdDiscount($idDiscount)
    {
        return $this->getFactory()
            ->createDiscountConfiguratorHydrate()
            ->getByIdDiscount($idDiscount);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idDiscount
     * @param bool $isActive
     *
     * @return bool
     */
    public function toggleDiscountVisibility($idDiscount, $isActive = false)
    {
        return $this->getFactory()
            ->createDiscountPersist()
            ->toggleDiscountVisibility($idDiscount, $isActive);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountVoucherTransfer $discountVoucherTransfer
     *
     * @return \Generated\Shared\Transfer\VoucherCreateInfoTransfer
     */
    public function saveVoucherCodes(DiscountVoucherTransfer $discountVoucherTransfer)
    {
        return $this->getFactory()
            ->createDiscountPersist()
            ->saveVoucherCodes($discountVoucherTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer[] $discountableObjects
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return int
     */
    public function calculatePercentageDiscount(array $discountableObjects, DiscountTransfer $discountTransfer)
    {
        return $this->getFactory()
            ->createCalculatorPercentageType()
            ->calculateDiscount($discountableObjects, $discountTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer[] $discountableObjects
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return int
     */
    public function calculateFixedDiscount(array $discountableObjects, DiscountTransfer $discountTransfer)
    {
        return $this->getFactory()
            ->createCalculatorFixedType()
            ->calculateDiscount($discountableObjects, $discountTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer $collectedDiscountTransfer
     *
     * @return void
     */
    public function distributeAmount(CollectedDiscountTransfer $collectedDiscountTransfer)
    {
        $this->getFactory()
            ->createDistributor()
            ->distributeDiscountAmountToDiscountableItems($collectedDiscountTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string[] $voucherCodes
     *
     * @return int
     */
    public function releaseUsedVoucherCodes(array $voucherCodes)
    {
        return $this->getFactory()
            ->createVoucherCode()
            ->releaseUsedCodes($voucherCodes);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string[] $voucherCodes
     *
     * @return int
     */
    public function useVoucherCodes(array $voucherCodes)
    {
        return $this->getFactory()
            ->createVoucherCode()
            ->useCodes($voucherCodes);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderDiscountsForCheckout(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        $this->getFactory()
            ->createCheckoutDiscountOrderSaver()
            ->saveOrderDiscounts($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrder(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()
            ->createDiscountOrderHydrate()
            ->hydrate($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $type
     *
     * @return array
     */
    public function getQueryStringValueOptions($type)
    {
        return $this->getFactory()
            ->createQueryStringMetaDataProviderFactory()
            ->createMetaProviderByType($type)
            ->getQueryStringValueOptions();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isCurrencyDecisionRuleSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        ClauseTransfer $clauseTransfer
    ) {

        return $this->getFactory()
            ->createCurrencyDecisionRule()
            ->isSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isPriceModeDecisionRuleSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        ClauseTransfer $clauseTransfer
    ) {

         return $this->getFactory()
             ->createPriceModeDecisionRule()
             ->isSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $resultQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $sourceQuoteTransfer
     *
     * @return void
     */
    public function checkDiscountChanges(QuoteTransfer $resultQuoteTransfer, QuoteTransfer $sourceQuoteTransfer): void
    {
        $this->getFactory()->createQuoteChangeObserver()->checkDiscountChanges($resultQuoteTransfer, $sourceQuoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idDiscount
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer|null
     */
    public function findHydratedDiscountConfiguratorByIdDiscount(int $idDiscount): ?DiscountConfiguratorTransfer
    {
        return $this->getFactory()
            ->createDiscountConfiguratorHydrate()
            ->findByIdDiscount($idDiscount);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function validateVoucherDiscountsMaxUsage(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        return $this->getFactory()
            ->createQuoteVoucherDiscountMaxUsageValidator()
            ->validate($quoteTransfer, $checkoutResponseTransfer);
    }
}

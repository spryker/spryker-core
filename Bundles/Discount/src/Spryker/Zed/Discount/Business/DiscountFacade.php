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
use Generated\Shared\Transfer\DiscountVoucherTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountBusinessFactory getFactory()
 */
class DiscountFacade extends AbstractFacade implements DiscountFacadeInterface
{

    /**
     * Specification:
     *  - Find all discounts with voucher
     *  - Find all discounts matching decision rules
     *  - Collect discountable items for each discount type
     *  - Apply discount to exclusive if exists
     *  - distribute discount amount throw all discountable items
     *  - Add discount totals to quote discount properties
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
     * Specification:
     * - Check if given item transfer matching clause
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
     *  Specification:
     * - Check if quote grandTotal matching clause
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
     *  Specification:
     * - Check if cart total quantity matching clause
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
     *  Specification:
     * - Check quote subtotal matching clause
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
     *  Specification:
     * - Collect all items matching given sku in clause
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
     * Specification:
     * - Check if item quantity matching clause
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
     * Specification:
     * - Collect all items matching given quantity in clause
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
     * Specification:
     * - Check if there is items matching single item price in clause
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
     * Specification:
     * - Collect all items matching given quantity in clause
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
     * Specification:
     * - Check if current week in year matching clause
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
     * Specification:
     * - Check if current day of the week is matching clause
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
     * Specification:
     * - Check if current month is matching clause
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
     * Specification:
     * - Check if current time matching clause
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return boolean
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
     * Specification:
     * - Given type look for meta data provider
     * - Collect all available fields from all registered plugins
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
     * Specification:
     * - Given type look for meta data provider
     * - Collect all available comparator operators for given fieldName
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
     * Specification:
     * - Given type look for meta data provider
     * - Get all available comparators
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
     * Specification:
     * - Given type look for meta data provider
     * - Get boolean logical comparators
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
     * Specification:
     * - Given configure clause
     * - Select comparator operator based on clause operator, execute it and return result.
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
     * Specification:
     * - Configure specification builder on type and query string
     * - Try building query string
     * - Store all occurred error to array and return it
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
     * Specification:
     * - Hydrate discount entity from DiscountConfiguratorTransfer and persist it.
     * - If discount type is voucher create voucher pool without voucherCodes
     * - Return id of discount entity in persistence.
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
     * Specification:
     * - Hydrate discount entity from DiscountConfiguratorTransfer and persist it.
     * - If discount type is voucher create/update voucher pool without voucherCodes
     * - Return bool if discount entity was persisted
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
     * Specification:
     * - Read idDiscount from persistence
     * - Hydrate data from entities to DiscountConfiguratorTransfer
     * - return DiscountConfiguratorTransfer
     *
     * @api
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
     * Specification:
     * - Find discount entity
     * - Change discount state to enabled/disabled.
     * - Persist
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
     * Specification:
     * - Find discount to which voucherCodes have to be generated
     * - Change discount state to enabled/disabled.
     * - Create pool if not created yet.
     * - Using voucher engine generate voucherCodes by provided configuration from DiscountVoucherTransfer
     * - Persist code with reference to current discount
     * - Return VoucherCreateInfoTransfer with error or success messages if there was any
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
     * Specification:
     * - Loop over all discountable items and calculate discount price amount per item
     * - Sum each amount to to total
     * - Round up cent fraction for total discount amount!
     * - Return total calculated discount amount on given discountable items
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer[] $discountableObjects
     * @param float $percentage
     *
     * @return float
     */
    public function calculatePercentage(array $discountableObjects, $percentage)
    {
        return $this->getFactory()
            ->createCalculatorPercentage()
            ->calculate($discountableObjects, $percentage);
    }

    /**
     * Specification:
     *
     * - Return amount passed as parameter,
     * - Return 0 if negative number is given
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer[] $discountableObjects
     * @param float $amount
     *
     * @return float
     */
    public function calculateFixed(array $discountableObjects, $amount)
    {
        return $this->getFactory()
            ->createCalculatorFixed()
            ->calculate($discountableObjects, $amount);
    }

    /**
     * Specification:
     *
     * - Loop over each DiscountableItemTransfer and calculate each item price amount share from current discount total, for single item.
     * - Calculate floating point error and store it for later item, add it to next item.
     * - Store item price share amount into DiscountableItemTransfer::originalItemCalculatedDiscounts array object reference! Which points to original item!
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
     * Specification:
     *
     * - For given voucherCodes find all voucher entities with counter
     * - Reduce voucher number of uses property by 1 to indicate it's not used/released.
     *
     * @api
     *
     * @param string[] $voucherCodes
     *
     * @return bool
     */
    public function releaseUsedVoucherCodes(array $voucherCodes)
    {
        return $this->getFactory()
            ->createVoucherCode()
            ->releaseUsedCodes($voucherCodes);
    }

    /**
     * Specification:
     *
     * - For given voucherCodes find all voucher entities with counter
     * - Increment voucher number of uses property by 1.
     *
     * @api
     *
     * @param string[] $voucherCodes
     *
     * @return bool
     */
    public function useVoucherCodes(array $voucherCodes)
    {
        return $this->getFactory()
            ->createVoucherCode()
            ->useCodes($voucherCodes);
    }

    /**
     * Specification:
     *
     * - Loop over all quote items, take calculated discounts and persist them discount amount is for single item
     * - Loop over all quote expenses, take calculated discounts and persist them discount amount is for single item
     * - If there is voucher codes mark them as already used by incrementing number of uses.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrderDiscounts(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $this->getFactory()
            ->createDiscountOrderSaver()
            ->saveDiscounts($quoteTransfer, $checkoutResponseTransfer);
    }

}

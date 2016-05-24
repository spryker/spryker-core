<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\CollectedDiscountTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountVoucherTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\VoucherCreateInfoTransfer;
use Spryker\Zed\Discount\Business\Exception\ComparatorException;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountBusinessFactory getFactory()
 */
class DiscountFacade extends AbstractFacade
{

    /**
     * Calculate discounts based on provided quote transfer.
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
     *
     * @param QuoteTransfer $quoteTransfer
     * @param ItemTransfer $itemTransfer
     * @param ClauseTransfer $clauseTransfer
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
     *
     * @param QuoteTransfer $quoteTransfer
     * @param ItemTransfer $itemTransfer
     * @param ClauseTransfer $clauseTransfer
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
     * @param QuoteTransfer $quoteTransfer
     * @param ItemTransfer $itemTransfer
     * @param ClauseTransfer $clauseTransfer
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
            ->isSatisfiedBy($quoteTransfer, $itemTransfer , $clauseTransfer);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param ItemTransfer $itemTransfer
     * @param ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isSubTotalSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        ClauseTransfer $clauseTransfer
    )
    {
        return $this->getFactory()
            ->createSubTotalDecisionRule()
            ->isSatisfiedBy($quoteTransfer,$itemTransfer , $clauseTransfer);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param ClauseTransfer $clauseTransfer
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
     * @api
     *
     * @param string $type
     *
     * @return array|string[]
     */
    public function getQueryStringFieldsByType($type)
    {
        return $this->getFactory()
            ->createQueryStringSpecificationMetaProviderFactory()
            ->createMetaProviderByType($type)
            ->getAvailableFields();
    }

    /**
     * @api
     *
     * @param string $type
     * @param string $fieldName
     *
     * @return array|string[]
     */
    public function getQueryStringFieldExpressionsForField($type, $fieldName)
    {
        return $this->getFactory()
            ->createQueryStringSpecificationMetaProviderFactory()
            ->createMetaProviderByType($type)
            ->getAvailableOperatorExpressionsForField($fieldName);
    }

    /**
     * @api
     *
     * @param string $type
     *
     * @return array|string[]
     */
    public function getQueryStringComparatorExpressions($type)
    {
        return $this->getFactory()
            ->createQueryStringSpecificationMetaProviderFactory()
            ->createMetaProviderByType($type)
            ->getAvailableComparatorExpressions();
    }

    /**
     * @param string $type
     *
     * @return array|string[]
     */
    public function getQueryStringLogicalComparators($type)
    {
        return $this->getFactory()
            ->createQueryStringSpecificationMetaProviderFactory()
            ->createMetaProviderByType($type)
            ->getLogicalComparators();
    }

    /**
     * @api
     *
     * @param ClauseTransfer $clauseTransfer
     * @param string $compareWith
     *
     * @throws ComparatorException
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
     * @param string $type
     * @param string $queryString
     *
     * @return array|string[]
     */
    public function validateQueryStringByType($type, $queryString)
    {
        return $this->getFactory()
            ->createQueryStringValidator()
            ->validateByType($type, $queryString);
    }


    /**
     * @param DiscountConfiguratorTransfer $discountConfigurator
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
     * @param DiscountConfiguratorTransfer $discountConfigurator
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
     * @param int $idDiscount
     *
     * @return DiscountConfiguratorTransfer
     */
    public function getHydratedDiscountConfiguratorByIdDiscount($idDiscount)
    {
        return $this->getFactory()
            ->createDiscountConfiguratorHydrate()
            ->getByIdDiscount($idDiscount);
    }

    /**
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
     * @param DiscountVoucherTransfer $discountVoucherTransfer
     *
     * @return VoucherCreateInfoTransfer
     */
    public function saveVoucherCodes(DiscountVoucherTransfer $discountVoucherTransfer)
    {
        return $this->getFactory()
            ->createDiscountPersist()
            ->saveVoucherCodes($discountVoucherTransfer);
    }

    /**
     * @api
     *
     * @param DiscountableItemTransfer[] $discountableObjects
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
     * @api
     *
     * @param DiscountableItemTransfer[] $discountableObjects
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
     * @api
     *
     * @param CollectedDiscountTransfer $collectedDiscountTransfer
     *
     * @return void
     */
    public function distributeAmount(CollectedDiscountTransfer $collectedDiscountTransfer)
    {
        $this->getFactory()->createDistributor()->distribute($collectedDiscountTransfer);
    }

    /**
     * @api
     *
     * @param string $pluginName
     *
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface
     */
    public function getCalculatorPluginByName($pluginName)
    {
        return $this->getFactory()
            ->getCalculatorPlugins()[$pluginName];
    }


    /**
     * @api
     *
     * @param string[] $codes
     *
     * @return bool
     */
    public function releaseUsedVoucherCodes(array $codes)
    {
        return $this->getFactory()
            ->createVoucherCode()
            ->releaseUsedCodes($codes);
    }

    /**
     * @api
     *
     * @param string[] $codes
     *
     * @return bool
     */
    public function useVoucherCodes(array $codes)
    {
        return $this->getFactory()
            ->createVoucherCode()
            ->useCodes($codes);
    }

    /**
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

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderTotalDiscountAmount(OrderTransfer $orderTransfer)
    {
        $this->getFactory()
            ->createOrderDiscountTotalAmount()
            ->aggregate($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderCalculatedDiscounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()
            ->createSalesOrderTotalsAggregator()
            ->aggregate($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateItemDiscounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()
            ->createItemTotalOrderAggregator()
            ->aggregate($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateGrandTotalWithDiscounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()
            ->createSalesOrderGrandTotalAggregator()
            ->aggregate($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderExpenseTaxWithDiscounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()
            ->createOrderExpenseTaxWithDiscountsAggregator()
            ->aggregate($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderExpensesWithDiscounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()
            ->createOrderExpenseWithDiscountsAggregator()
            ->aggregate($orderTransfer);
    }

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business;

use Generated\Shared\Transfer\CartReorderResponseTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionRequestTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionResponseTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentRequestTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentResponseTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

interface SalesOrderAmendmentFacadeInterface
{
    /**
     * Specification:
     * - Retrieves sales order amendment entities filtered by criteria from Persistence.
     * - Uses `SalesOrderAmendmentCriteriaTransfer.salesOrderAmendmentConditions.salesOrderAmendmentIds` to filter by IDs.
     * - Uses `SalesOrderAmendmentCriteriaTransfer.salesOrderAmendmentConditions.uuids` to filter by UUIDs.
     * - Uses `SalesOrderAmendmentCriteriaTransfer.salesOrderAmendmentConditions.originalOrderReferences` to filter by original order references.
     * - Uses `SalesOrderAmendmentCriteriaTransfer.sort.field` to set the 'order by' field.
     * - Uses `SalesOrderAmendmentCriteriaTransfer.sort.isAscending` to set ascending/descending order.
     * - Uses `SalesOrderAmendmentCriteriaTransfer.pagination.{limit, offset}` to paginate results with limit and offset.
     * - Uses `SalesOrderAmendmentCriteriaTransfer.pagination.{page, maxPerPage}` to paginate results with page and maxPerPage.
     * - Executes a stack of {@link \Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentExpanderPluginInterface} plugins.
     * - Returns `SalesOrderAmendmentCollectionTransfer` filled with found sales order amendments.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentCriteriaTransfer $salesOrderAmendmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentCollectionTransfer
     */
    public function getSalesOrderAmendmentCollection(
        SalesOrderAmendmentCriteriaTransfer $salesOrderAmendmentCriteriaTransfer
    ): SalesOrderAmendmentCollectionTransfer;

    /**
     * Specification:
     * - Retrieves sales order amendment quote entities filtered by criteria from Persistence.
     * - Uses `SalesOrderAmendmentQuoteCriteriaTransfer.salesOrderAmendmentQuoteConditions.salesOrderAmendmentQuoteIds` to filter by IDs.
     * - Uses `SalesOrderAmendmentQuoteCriteriaTransfer.salesOrderAmendmentQuoteConditions.uuids` to filter by UUIDs.
     * - Uses `SalesOrderAmendmentQuoteCriteriaTransfer.salesOrderAmendmentQuoteConditions.customerReferences` to filter by customer references.
     * - Uses `SalesOrderAmendmentQuoteCriteriaTransfer.salesOrderAmendmentQuoteConditions.storeNames` to filter by store names.
     * - Uses `SalesOrderAmendmentQuoteCriteriaTransfer.salesOrderAmendmentQuoteConditions.amendmentOrderReferences` to filter by amendment order references.
     * - Uses `SalesOrderAmendmentQuoteCriteriaTransfer.sort.field` to set the 'order by' field.
     * - Uses `SalesOrderAmendmentQuoteCriteriaTransfer.sort.isAscending` to set ascending/descending order.
     * - Uses `SalesOrderAmendmentQuoteCriteriaTransfer.pagination.{limit, offset}` to paginate results with limit and offset.
     * - Uses `SalesOrderAmendmentQuoteCriteriaTransfer.pagination.{page, maxPerPage}` to paginate results with page and maxPerPage.
     * - Returns `SalesOrderAmendmentQuoteCollectionTransfer` filled with found sales order amendment quotes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCriteriaTransfer $salesOrderAmendmentQuoteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionTransfer
     */
    public function getSalesOrderAmendmentQuoteCollection(
        SalesOrderAmendmentQuoteCriteriaTransfer $salesOrderAmendmentQuoteCriteriaTransfer
    ): SalesOrderAmendmentQuoteCollectionTransfer;

    /**
     * Specification:
     * - Requires `SalesOrderAmendmentRequestTransfer.originalOrderReference` to be set.
     * - Requires `SalesOrderAmendmentRequestTransfer.amendedOrderReference` to be set.
     * - Executes a stack of {@link \Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentValidatorRulePluginInterface} plugins.
     * - Executes a stack of {@link \Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPreCreatePluginInterface} plugins.
     * - Persists sales order amendment entity.
     * - Executes a stack of {@link \Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPostCreatePluginInterface} plugins.
     * - Returns `SalesOrderAmendmentResponseTransfer` with created sales order amendment.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentRequestTransfer $salesOrderAmendmentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentResponseTransfer
     */
    public function createSalesOrderAmendment(
        SalesOrderAmendmentRequestTransfer $salesOrderAmendmentRequestTransfer
    ): SalesOrderAmendmentResponseTransfer;

    /**
     * Specification:
     * - Creates a sales order amendment quotes.
     * - Requires `SalesOrderAmendmentQuoteCollectionRequestTransfer.salesOrderAmendmentQuotes` to be set.
     * - Requires `SalesOrderAmendmentQuoteCollectionRequestTransfer.salesOrderAmendmentQuotes.quote` to be set.
     * - Requires `SalesOrderAmendmentQuoteCollectionRequestTransfer.salesOrderAmendmentQuotes.store` to be set.
     * - Requires `SalesOrderAmendmentQuoteCollectionRequestTransfer.salesOrderAmendmentQuotes.customerReference` to be set.
     * - Requires `SalesOrderAmendmentQuoteCollectionRequestTransfer.salesOrderAmendmentQuotes.amendmentOrderReference` to be set.
     * - Uses transaction for the operation.
     * - Filters quote fields based on the `SalesOrderAmendmentConfig::getQuoteFieldsAllowedForSaving()` configuration.
     * - Filters quote item fields based on the `SalesOrderAmendmentConfig::getQuoteItemFieldsAllowedForSaving()` configuration.
     * - Returns `SalesOrderAmendmentQuoteCollectionResponseTransfer` with persisted sales order amendment quotes and errors if any occurred.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionRequestTransfer $salesOrderAmendmentQuoteCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionResponseTransfer
     */
    public function createSalesOrderAmendmentQuoteCollection(
        SalesOrderAmendmentQuoteCollectionRequestTransfer $salesOrderAmendmentQuoteCollectionRequestTransfer
    ): SalesOrderAmendmentQuoteCollectionResponseTransfer;

    /**
     * Specification:
     * - Requires `SalesOrderAmendmentTransfer.uuid` to be set.
     * - Validates if sales order amendment with provided UUID exists.
     * - Executes a stack of {@link \Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentValidatorRulePluginInterface} plugins.
     * - Executes a stack of {@link \Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPreUpdatePluginInterface} plugins.
     * - Update sales order amendment entity in Persistence.
     * - Executes a stack of {@link \Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPostUpdatePluginInterface} plugins.
     * - Returns `SalesOrderAmendmentResponseTransfer` with updated sales order amendment.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentResponseTransfer
     */
    public function updateSalesOrderAmendment(
        SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
    ): SalesOrderAmendmentResponseTransfer;

    /**
     * Specification:
     * - Expects `SalesOrderAmendmentTransfer.uuid` or `SalesOrderAmendmentTransfer.idSalesOrderAmendment` to be set.
     * - Executes a stack of {@link \Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPreDeletePluginInterface} plugins.
     * - Deletes sales order amendment entity from Persistence.
     * - Executes a stack of {@link \Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPostDeletePluginInterface} plugins.
     * - Returns `SalesOrderAmendmentResponseTransfer` with deleted sales order amendment.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentDeleteCriteriaTransfer $salesOrderAmendmentDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentResponseTransfer
     */
    public function deleteSalesOrderAmendment(
        SalesOrderAmendmentDeleteCriteriaTransfer $salesOrderAmendmentDeleteCriteriaTransfer
    ): SalesOrderAmendmentResponseTransfer;

    /**
     * Specification:
     * - Deletes sales order amendment quotes based on the provided criteria.
     * - Expects `SalesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer.salesOrderAmendmentQuoteIds` or `SalesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer.uuids` to be set.
     * - Returns `SalesOrderAmendmentQuoteResponseTransfer` filled with deleted sales order amendment quotes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer $salesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionResponseTransfer
     */
    public function deleteSalesOrderAmendmentQuoteCollection(
        SalesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer $salesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer
    ): SalesOrderAmendmentQuoteCollectionResponseTransfer;

    /**
     * Specification:
     * - Requires `OrderTransfer.orderReference` to be set.
     * - Retrieves the most recent sales order amendment entity by order reference from Persistence, sorted in descending order by the `created_at` column.
     * - Expands `OrderTransfer.salesOrderAmendment` with found sales order amendment.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithSalesOrderAmendment(OrderTransfer $orderTransfer): OrderTransfer;

    /**
     * Specification:
     * - Requires `CartReorderTransfer.quote` to be set.
     * - Requires `CartReorderTransfer.order` to be set.
     * - Requires `CartReorderTransfer.quote.amendmentOrderReference` to be set.
     * - Requires `CartReorderTransfer.order.orderReference` to be set.
     * - Validates if `CartReorderTransfer.quote.amendmentOrderReference` matches `CartReorderTransfer.order.orderReference`.
     * - Returns `ErrorCollectionTransfer` with error messages if validation fails.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     * @param \Generated\Shared\Transfer\CartReorderResponseTransfer $cartReorderResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderResponseTransfer
     */
    public function validateCartReorder(
        CartReorderTransfer $cartReorderTransfer,
        CartReorderResponseTransfer $cartReorderResponseTransfer
    ): CartReorderResponseTransfer;

    /**
     * Specification:
     * - Requires `QuoteTransfer.amendmentOrderReference` to be set.
     * - Requires `QuoteTransfer.customerReference` to be set.
     * - Retrieves order entity by `QuoteTransfer.amendmentOrderReference` from Persistence.
     * - Sets `QuoteTransfer.originalOrder` with found order entity.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteWithOriginalOrder(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Requires `QuoteTransfer.originalOrder` to be set.
     * - Obtains order from `QuoteTransfer.originalOrder`, compares the items with the provided quote items and replaces the items if they differ.
     * - Executes a stack of {@link \Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentItemCollectorStrategyPluginInterface} plugins
     * to identify a strategy to divide order items into groups to create/update/delete/skip.
     * - Uses default strategy if no applicable strategy is found.
     * - Uses {@link \Spryker\Zed\Sales\Business\SalesFacadeInterface::createSalesOrderItemCollectionByQuote()} to create new order items.
     * - Uses {@link \Spryker\Zed\Sales\Business\SalesFacadeInterface::updateSalesOrderItemCollectionByQuote()} to update existing order items.
     * - Uses {@link \Spryker\Zed\Sales\Business\SalesFacadeInterface::deleteSalesOrderItemCollection()} to delete order items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function replaceSalesOrderItems(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void;
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business;

use Generated\Shared\Transfer\CartReorderResponseTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentRequestTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentResponseTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentTransfer;

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
     * - Requires `OrderTransfer.orderReference` to be set.
     * - Retrieves sales order amendment entity by order reference from Persistence.
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
     * - Requires `CartReorderTransfer.order.orderReference` to be set.
     * - Does nothing if `CartReorderTransfer.quote.amendmentOrderReference` is not set.
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
}

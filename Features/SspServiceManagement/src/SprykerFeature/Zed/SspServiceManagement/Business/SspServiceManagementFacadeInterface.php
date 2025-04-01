<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\ProductAbstractTypeCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Generated\Shared\Transfer\SspServiceCollectionTransfer;
use Generated\Shared\Transfer\SspServiceCriteriaTransfer;

interface SspServiceManagementFacadeInterface
{
    /**
     * Specification:
     * - Retrieves a collection of services.
     * - Uses the provided criteria to filter and sort the results.
     * - Returns a SspServiceCollectionTransfer transfer object with the results and pagination information.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspServiceCollectionTransfer
     */
    public function getServiceCollection(SspServiceCriteriaTransfer $sspServiceCriteriaTransfer): SspServiceCollectionTransfer;

    /**
     * Specification:
     * - Imports product shipment type relation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importProductShipmentType(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer;

    /**
     * Specification:
     * - Updates a collection of sales order items with schedule information.
     * - Requires `SalesOrderItemCollectionRequestTransfer.items` to be set.
     * - Validates that the order items collection is not empty.
     * - Validates that the order exists and returns error if not found.
     * - Validates that the order has payment methods and returns error if none found.
     * - Resolves payment method key using payment provider and payment method.
     * - Creates a quote transfer with payment information.
     * - Updates sales order item collection using the constructed quote.
     * - Returns `SalesOrderItemCollectionResponseTransfer` with updated items or error information.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function updateSalesOrderItemCollection(
        SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
    ): SalesOrderItemCollectionResponseTransfer;

    /**
     * Specification:
     * - Returns collection of product abstract types.
     * - Returns empty collection if no product abstract types found.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTypeCollectionTransfer
     */
    public function getProductAbstractTypeCollection(): ProductAbstractTypeCollectionTransfer;

    /**
     * Specification:
     * - Imports product abstract types.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importProductAbstractType(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer;

    /**
     * Specification:
     * - Imports product abstract to product abstract type relations.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importProductAbstractToProductAbstractType(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer;
}

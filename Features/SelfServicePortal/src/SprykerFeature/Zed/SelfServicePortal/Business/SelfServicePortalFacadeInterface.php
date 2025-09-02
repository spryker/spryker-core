<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business;

use Generated\Shared\Transfer\DashboardRequestTransfer;
use Generated\Shared\Transfer\DashboardResponseTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionResponseTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Generated\Shared\Transfer\SspAssetCollectionRequestTransfer;
use Generated\Shared\Transfer\SspAssetCollectionResponseTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspModelCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SspModelCollectionRequestTransfer;
use Generated\Shared\Transfer\SspModelCollectionResponseTransfer;
use Generated\Shared\Transfer\SspModelCollectionTransfer;
use Generated\Shared\Transfer\SspModelCriteriaTransfer;
use Generated\Shared\Transfer\SspServiceCollectionTransfer;
use Generated\Shared\Transfer\SspServiceCriteriaTransfer;

interface SelfServicePortalFacadeInterface
{
    /**
     * Specification:
     * - Retrieves a collection of services.
     * - Uses the provided criteria to filter and sort the results.
     * - Returns a `SspServiceCollectionTransfer` transfer object with the results and pagination information.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspServiceCollectionTransfer
     */
    public function getSspServiceCollection(SspServiceCriteriaTransfer $sspServiceCriteriaTransfer): SspServiceCollectionTransfer;

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
     * - Cancels a collection of sales order items.
     * - Requires `SalesOrderItemCollectionRequestTransfer.items` to be set.
     * - Validates that the order items collection is not empty.
     * - Validates that the order exists and returns error if not found.
     * - Cancels the sales order items by triggering the cancel event in the state machine.
     * - Returns `SalesOrderItemCollectionResponseTransfer` with canceled items or error information.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function cancelSalesOrderItemCollection(
        SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
    ): SalesOrderItemCollectionResponseTransfer;

    /**
     * Specification:
     * - Creates a collection of file attachments in the storage.
     * - Uses `FileAttachmentCollectionResponseTransfer.fileAttachmentsToAdd` to create file attachments.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer $fileAttachmentCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionResponseTransfer
     */
    public function createFileAttachmentCollection(
        FileAttachmentCollectionRequestTransfer $fileAttachmentCollectionRequestTransfer
    ): FileAttachmentCollectionResponseTransfer;

    /**
     * Specification:
     * - Deletes file attachments by provided criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer $fileAttachmentCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionResponseTransfer
     */
    public function deleteFileAttachmentCollection(
        FileAttachmentCollectionRequestTransfer $fileAttachmentCollectionRequestTransfer
    ): FileAttachmentCollectionResponseTransfer;

    /**
     * Specification:
     * - Gets file collection based on criteria and user permissions.
     * - Uses `FileAttachmentCriteriaTransfer` to filter and sort the results.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionTransfer
     */
    public function getFileAttachmentCollection(
        FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
    ): FileAttachmentCollectionTransfer;

    /**
     * Specification:
     * - Returns company user-specific dashboard data.
     * - Runs a stack of `\SprykerFeature\Zed\SelfServicePortal\Dependency\Plugin\DashboardDataExpanderPluginInterface` plugins to collect the data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DashboardRequestTransfer $dashboardRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DashboardResponseTransfer
     */
    public function getDashboard(DashboardRequestTransfer $dashboardRequestTransfer): DashboardResponseTransfer;

    /**
     * Specification:
     * - Creates ssp inquiries.
     * - Executes a stack of `\SprykerFeature\Zed\SelfServicePortal\Business\Hooks\PreCreate\SspInquiryPreCreateHookInterface`.
     * - Executes a stack of `\SprykerFeature\Zed\SelfServicePortal\Business\Hooks\PostCreate\SspInquiryPostCreateHookInterface`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer
     */
    public function createSspInquiryCollection(
        SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer
    ): SspInquiryCollectionResponseTransfer;

    /**
     * Specification:
     * - Finds ssp inquiries by criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function getSspInquiryCollection(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer;

    /**
     * Specification:
     * - Cancels ssp inquiries from provided collection.
     * - Requires `SspInquiryCollectionRequestTransfer.sspInquiries.reference` to be provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer
     */
    public function cancelSspInquiryCollection(
        SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer
    ): SspInquiryCollectionResponseTransfer;

    /**
     * Specification:
     * - Creates a collection of assets.
     * - Validates asset data.
     * - Persists assets to database.
     * - Returns response with created assets and validation messages.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspAssetCollectionRequestTransfer $sspAssetCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionResponseTransfer
     */
    public function createSspAssetCollection(SspAssetCollectionRequestTransfer $sspAssetCollectionRequestTransfer): SspAssetCollectionResponseTransfer;

    /**
     * Specification:
     * - Retrieves assets by criteria from Persistence.
     * - Uses pagination, sorting and filtering from criteria.
     * - Expands assets with relations based on criteria includes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionTransfer
     */
    public function getSspAssetCollection(SspAssetCriteriaTransfer $sspAssetCriteriaTransfer): SspAssetCollectionTransfer;

    /**
     * Specification:
     * - Updates a collection of assets.
     * - Validates asset data.
     * - Updates assets in database.
     * - Returns response with updated assets and validation messages.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspAssetCollectionRequestTransfer $sspAssetCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionResponseTransfer
     */
    public function updateSspAssetCollection(SspAssetCollectionRequestTransfer $sspAssetCollectionRequestTransfer): SspAssetCollectionResponseTransfer;

    /**
     * Specification:
     * - Saves product classes for the given product concrete.
     * - Requires `ProductConcreteTransfer.fkProductAbstract` to be provided.
     * - Requires `ProductConcreteTransfer.productClasses` to be provided.
     * - Deletes existing product class relations for the abstract product first.
     * - Creates new relations between abstract product and product classes.
     * - Returns the updated product concrete transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function saveProductClassesForProductConcrete(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer;

    /**
     * Specification:
     * - Creates a collection of SSP models.
     * - Validates model data.
     * - Persists models to database.
     * - Returns response with created models and validation messages.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspModelCollectionRequestTransfer $sspModelCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspModelCollectionResponseTransfer
     */
    public function createSspModelCollection(SspModelCollectionRequestTransfer $sspModelCollectionRequestTransfer): SspModelCollectionResponseTransfer;

    /**
     * Specification:
     * - Retrieves models by criteria from Persistence.
     * - Uses pagination, sorting and filtering from criteria.
     * - Expands models with relations based on criteria includes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspModelCriteriaTransfer $sspModelCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspModelCollectionTransfer
     */
    public function getSspModelCollection(SspModelCriteriaTransfer $sspModelCriteriaTransfer): SspModelCollectionTransfer;

    /**
     * Specification:
     * - Deletes a collection of SSP models based on provided criteria.
     * - Requires `SspModelCollectionDeleteCriteriaTransfer.sspModelIds` to be provided.
     * - Validates that the model IDs collection is not empty.
     * - Retrieves existing models by the provided IDs from persistence.
     * - Returns `SspModelCollectionResponseTransfer` with deleted models or empty collection if no models found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspModelCollectionDeleteCriteriaTransfer $sspModelCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspModelCollectionResponseTransfer
     */
    public function deleteSspModelCollection(
        SspModelCollectionDeleteCriteriaTransfer $sspModelCollectionDeleteCriteriaTransfer
    ): SspModelCollectionResponseTransfer;
}

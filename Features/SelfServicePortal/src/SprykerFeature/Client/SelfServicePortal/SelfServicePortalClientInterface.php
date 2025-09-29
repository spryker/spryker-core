<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal;

use Generated\Shared\Transfer\DashboardRequestTransfer;
use Generated\Shared\Transfer\DashboardResponseTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Generated\Shared\Transfer\SspAssetCollectionRequestTransfer;
use Generated\Shared\Transfer\SspAssetCollectionResponseTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetQuoteItemAttachmentRequestTransfer;
use Generated\Shared\Transfer\SspAssetSearchCollectionTransfer;
use Generated\Shared\Transfer\SspAssetSearchCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetStorageCollectionTransfer;
use Generated\Shared\Transfer\SspAssetStorageCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspModelStorageCollectionTransfer;
use Generated\Shared\Transfer\SspModelStorageCriteriaTransfer;
use Generated\Shared\Transfer\SspServiceCollectionTransfer;
use Generated\Shared\Transfer\SspServiceCriteriaTransfer;

interface SelfServicePortalClientInterface
{
    /**
     * Specification:
     * - Retrieves a collection of services based on criteria.
     * - Uses Zed facade to fetch the data.
     * - Returns a `SspServiceCollectionTransfer` with the services and pagination information.
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
     * - Makes Zed request.
     * - Gets files according to permissions.
     * - Uses `FileAttachmentCriteriaTransfer` to filter files.
     * - Returns a `FileAttachmentCollectionTransfer` with the files based on criteria includes.
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
     * - Retrieves the dashboard data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DashboardRequestTransfer $dashboardRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DashboardResponseTransfer
     */
    public function getDashboardData(DashboardRequestTransfer $dashboardRequestTransfer): DashboardResponseTransfer;

    /**
     * Specification:
     * - Updates sales order items collection.
     * - Uses Zed facade to perform the update.
     * - Returns a `SalesOrderItemCollectionResponseTransfer` with the updated items and potential errors.
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
     * - Cancels sales order items collection.
     * - Uses Zed facade to perform the cancellation.
     * - Returns a `SalesOrderItemCollectionResponseTransfer` with potential errors.
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
     * - Creates a ssp inquiry collection.
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
     * - Gets a ssp inquiry collection by criteria.
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
     * - Cancels ssp inquiries from the provided collection.
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
     * - Makes Zed request.
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
     * - Makes Zed request.
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
     * - Makes Zed request.
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
     * - Requires `SspModelStorageCriteriaTransfer.sspModelStorageConditions` to be set.
     * - Retrieves SSP model storage data filtered by criteria from Storage.
     * - Uses `SspModelStorageCriteriaTransfer.sspModelStorageConditions.sspModelIds` to filter by SSP model IDs.
     * - Returns `SspModelStorageCollectionTransfer` filled with found SSP models.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspModelStorageCriteriaTransfer $sspModelStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspModelStorageCollectionTransfer
     */
    public function getSspModelStorageCollection(
        SspModelStorageCriteriaTransfer $sspModelStorageCriteriaTransfer
    ): SspModelStorageCollectionTransfer;

    /**
     * Specification:
     * - Retrieves SSP asset search collection from search based on criteria.
     * - Uses search engine to find assets matching the criteria.
     * - Returns `SspAssetSearchCollectionTransfer` filled with found SSP assets.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspAssetSearchCriteriaTransfer $sspAssetSearchCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetSearchCollectionTransfer
     */
    public function getSspAssetSearchCollection(
        SspAssetSearchCriteriaTransfer $sspAssetSearchCriteriaTransfer
    ): SspAssetSearchCollectionTransfer;

    /**
     * Specification:
     * - Retrieves SSP asset storage collection from storage based on criteria.
     * - Requires `SspAssetStorageCriteriaTransfer.sspAssetStorageConditions` to be set.
     * - Requires `SspAssetStorageCriteriaTransfer.companyUser` to be set.
     * - Filters by SSP asset references provided in criteria conditions.
     * - Filters out assets that company user does not have access to based on `ViewBusinessUnitSspAssetPermissionPlugin` and `ViewCompanySspAssetPermissionPlugin` permission.
     * - Returns collection of SSP asset storage transfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspAssetStorageCriteriaTransfer $sspAssetStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetStorageCollectionTransfer
     */
    public function getSspAssetStorageCollection(
        SspAssetStorageCriteriaTransfer $sspAssetStorageCriteriaTransfer
    ): SspAssetStorageCollectionTransfer;

    /**
     * Specification:
     * - Sets ssp asset to quote item and saves the quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspAssetQuoteItemAttachmentRequestTransfer $sspAssetQuoteItemAttachmentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function attachSspAssetToQuoteItem(SspAssetQuoteItemAttachmentRequestTransfer $sspAssetQuoteItemAttachmentRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Checks asset compatibility with products by SKUs in bulk.
     * - Returns an array indexed by asset reference and SKU combinations.
     * - Each result indicates whether the asset is compatible with the product.
     * - Uses ProductStorageClient to resolve SKUs to product IDs.
     * - Uses current locale for product data retrieval.
     *
     * @api
     *
     * @param array<string> $assetReferences
     * @param array<string> $skus
     *
     * @return array<string, array<string, bool>> Indexed by [assetReference][sku] => bool
     */
    public function getAssetProductCompatibilityMatrix(array $assetReferences, array $skus): array;
}

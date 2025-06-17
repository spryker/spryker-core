<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal;

use Generated\Shared\Transfer\DashboardRequestTransfer;
use Generated\Shared\Transfer\DashboardResponseTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer;
use Generated\Shared\Transfer\FileManagerDataTransfer;
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
use Generated\Shared\Transfer\SspInquiryFileDownloadRequestTransfer;
use Generated\Shared\Transfer\SspServiceCollectionTransfer;
use Generated\Shared\Transfer\SspServiceCriteriaTransfer;

interface SelfServicePortalClientInterface
{
    /**
     * Specification:
     * - Retrieves a collection of services based on criteria.
     * - Uses Zed facade to fetch the data.
     * - Returns a SspServiceCollectionTransfer with the services and pagination information.
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
     * - Makes Zed request.
     * - Gets files according to permissions.
     * - Uses `FileAttachmentFileCriteriaTransfer.fileAttachmentFileConditions.fileTypes` to filter files by file types.
     * - Uses `FileAttachmentFileCriteriaTransfer.fileAttachmentFileConditions.uuids` to filter files by file UUIDs.
     * - Uses `FileAttachmentFileCriteriaTransfer.fileAttachmentFileConditions.entityTypes` to filter files by entity types (company, company_user, company_business_unit).
     * - Uses `FileAttachmentFileCriteriaTransfer.fileAttachmentFileConditions.rangeCreatedAt` to filter files by creation date range.
     * - Uses `FileAttachmentFileCriteriaTransfer.fileAttachmentFileSearchConditions.searchString` to search in file names and references.
     * - Uses `FileAttachmentFileCriteriaTransfer.sortCollection` to sort the results.
     * - Uses `FileAttachmentFileCriteriaTransfer.pagination` to paginate the results.
     * - Filters files based on `ViewCompanyUserFilesPermissionPlugin` permission for company user files.
     * - Filters files based on `ViewCompanyBusinessUnitFilesPermissionPlugin` permission for company business unit files.
     * - Filters files based on `ViewCompanyFilesPermissionPlugin` permission for company files.
     * - Returns `FileAttachmentFileCollectionTransfer` with filtered files.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer
     */
    public function getFileAttachmentFileCollectionAccordingToPermissions(
        FileAttachmentFileCriteriaTransfer $fileAttachmentFileCriteriaTransfer
    ): FileAttachmentFileCollectionTransfer;

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
    public function getDashboard(DashboardRequestTransfer $dashboardRequestTransfer): DashboardResponseTransfer;

    /**
     * Specification:
     * - Updates sales order items collection.
     * - Uses Zed facade to perform the update.
     * - Returns a SalesOrderItemCollectionResponseTransfer with the updated items and potential errors.
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
     * - Returns a SalesOrderItemCollectionResponseTransfer with potential errors.
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
     * - Requires SspInquiryCollectionRequestTransfer.sspInquiries.reference to be provided.
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
     * - Downloads a file attached to the ssp inquiry by SspInquiryFileDownloadRequestTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspInquiryFileDownloadRequestTransfer $sspInquiryFileDownloadRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function downloadSspInquiryFile(SspInquiryFileDownloadRequestTransfer $sspInquiryFileDownloadRequestTransfer): FileManagerDataTransfer;

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
}

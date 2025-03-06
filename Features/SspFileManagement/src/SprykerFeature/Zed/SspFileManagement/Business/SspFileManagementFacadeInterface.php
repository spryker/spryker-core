<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Business;

use Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionResponseTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer;

interface SspFileManagementFacadeInterface
{
    /**
     * Specification:
     * - Deletes a collection of file attachments from the persistent storage by delete criteria.
     * - Uses `FileAttachmentCollectionDeleteCriteriaTransfer.fileIds` to filter file attachments by `fileIds`.
     * - Uses `FileAttachmentCollectionDeleteCriteriaTransfer.companyIds` to filter file attachments by `companyIds`.
     * - Uses `FileAttachmentCollectionDeleteCriteriaTransfer.companyUserIds` to filter file attachments by `companyUserIds`.
     * - Uses `FileAttachmentCollectionDeleteCriteriaTransfer.companyBusinessUnitIds` to filter file attachments by `companyBusinessUnitIds`.
     * - Sets validation errors in `FileAttachmentCollectionDeleteCriteriaTransfer.ErrorTransfer[]`.
     * - Returns `FileAttachmentCollectionDeleteCriteriaTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionDeleteCriteriaTransfer $fileAttachmentCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionResponseTransfer
     */
    public function deleteFileAttachmentCollection(
        FileAttachmentCollectionDeleteCriteriaTransfer $fileAttachmentCollectionDeleteCriteriaTransfer
    ): FileAttachmentCollectionResponseTransfer;

    /**
     * Specification:
     * - Saves a collection of file attachments to the storage.
     * - Uses `FileAttachmentCollectionResponseTransfer.fileAttachments` to save file attachments.
     * - Returns `FileAttachmentCollectionResponseTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer $fileAttachmentCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionResponseTransfer
     */
    public function saveFileAttachmentCollection(
        FileAttachmentCollectionRequestTransfer $fileAttachmentCollectionRequestTransfer
    ): FileAttachmentCollectionResponseTransfer;

    /**
     * Specification:
     * - Returns a collection of file attachments filtered by criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionTransfer
     */
    public function getFileAttachmentCollection(FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer): FileAttachmentCollectionTransfer;

    /**
     * Specification:
     * - Gets file collection based on criteria and user permissions.
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
     * - Returns `FileAttachmentFileCollectionTransfer` with filtered files and available file type filters.
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
}

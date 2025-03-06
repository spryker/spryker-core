<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SspFileManagement;

use Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentFileCriteriaTransfer;

interface SspFileManagementClientInterface
{
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
}

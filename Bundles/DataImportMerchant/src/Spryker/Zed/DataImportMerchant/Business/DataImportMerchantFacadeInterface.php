<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchant\Business;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;

interface DataImportMerchantFacadeInterface
{
    /**
     * Specification:
     * - Requires `DataImportMerchantFileCollectionRequest.isTransactional` field to be set.
     * - Requires `DataImportMerchantFileCollectionRequest.dataImportMerchantFiles` field to be set.
     * - Requires `DataImportMerchantFile.idUser` field to be set.
     * - Requires `DataImportMerchantFile.merchantReference` field to be set.
     * - Requires `DataImportMerchantFile.importerType` field to be set.
     * - Requires `DataImportMerchantFile.fileInfo` field to be set.
     * - Requires `DataImportMerchantFileInfo.originalFileName` field to be set.
     * - Requires `DataImportMerchantFileInfo.contentType` field to be set.
     * - Requires `DataImportMerchantFileInfo.size` field to be set.
     * - Requires `DataImportMerchantFileInfo.content` field to be set.
     * - Executes stack of {@link \Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\DataImportMerchantFileValidatorPluginInterface} plugins.
     * - Saves files to filesystem before creating database entities.
     * - Sets status to 'pending' for newly created entities.
     * - Creates data import merchant file entities in persistence.
     * - Returns collection response with created entities or validation errors.
     * - Uses transactional operation if `isTransactional` is true.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer $dataImportMerchantFileCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer
     */
    public function createDataImportMerchantFileCollection(
        DataImportMerchantFileCollectionRequestTransfer $dataImportMerchantFileCollectionRequestTransfer
    ): DataImportMerchantFileCollectionResponseTransfer;

    /**
     * Specification:
     * - Does not filter entities when `DataImportMerchantFileCriteria.dataImportMerchantFileConditions` is not provided.
     * - Filters by `DataImportMerchantFileConditions.dataImportMerchantFileIds` if provided.
     * - Filters by `DataImportMerchantFileConditions.uuids` if provided.
     * - Filters by `DataImportMerchantFileConditions.merchantReferences` if provided.
     * - Filters by `DataImportMerchantFileConditions.statuses` if provided.
     * - Filters by `DataImportMerchantFileConditions.importerTypes` if provided.
     * - Filters by `DataImportMerchantFileConditions.userIds` if provided.
     * - Searches by `DataImportMerchantFileCriteria.dataImportMerchantFileSearchConditions` if original file name is provided.
     * - Applies sorting by `DataImportMerchantFileCriteria.sortCollection` if provided.
     * - Applies pagination by pagination if provided.
     * - Retrieves data import merchant file collection from persistence.
     * - Executes stack of {@link \Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\DataImportMerchantFileExpanderPluginInterface} plugins.
     * - Returns DataImportMerchantFileCollectionTransfer with found entities.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCriteriaTransfer $dataImportMerchantFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer
     */
    public function getDataImportMerchantFileCollection(
        DataImportMerchantFileCriteriaTransfer $dataImportMerchantFileCriteriaTransfer
    ): DataImportMerchantFileCollectionTransfer;

    /**
     * Specification:
     * - Retrieves data import merchant files with 'pending' status from persistence.
     * - Limits the number of files processed per execution based on configuration.
     * - For each file, sets status to 'in_progress' and records start time.
     * - Executes `DataImportFacade::importByAction()` using file source and configuration.
     * - Updates file status based on import results:
     *   - 'successful' if import completed without errors
     *   - 'failed' if import failed completely or exception occurred
     *   - 'imported_with_errors' if import partially succeeded with some errors
     * - Extracts and stores detailed error information from import reports.
     * - Records import completion time and results in file entity.
     * - Handles exceptions gracefully by setting failed status and generic error message.
     *
     * @api
     *
     * @return void
     */
    public function import(): void;

    /**
     * Specification:
     * - Requires `MerchantTransfer.merchantReference` field to be set.
     * - Returns possible CSV headers indexed by importer type.
     * - Executes stack of {@link \Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\PossibleCsvHeaderExpanderPluginInterface} plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return array<string, array<string>>
     */
    public function getPossibleCsvHeadersIndexedByImporterType(MerchantTransfer $merchantTransfer): array;
}

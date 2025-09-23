<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\DataImportMerchantPortalGui\Communication\GuiTable\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\DataImportMerchantFileCriteriaTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileTableCriteriaTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileTransfer;
use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Spryker\Shared\GuiTable\DataProvider\AbstractGuiTableDataProvider;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\DataImportMerchantFileTableConfigurationProvider;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\GuiTable\Mapper\DataImportMerchantFileGuiTableMapperInterface;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\Reader\DataImportMerchantFileReaderInterface;
use Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\DataImportMerchantPortalGui\Dependency\Service\DataImportMerchantPortalGuiToUtilEncodingServiceInterface;

class DataImportMerchantFileGuiTableDataProvider extends AbstractGuiTableDataProvider
{
    /**
     * @uses \Orm\Zed\DataImportMerchant\Persistence\Map\SpyDataImportMerchantFileTableMap::COL_ID_DATA_IMPORT_MERCHANT_FILE
     *
     * @var string
     */
    protected const COL_ID_DATA_IMPORT_MERCHANT_FILE = 'spy_data_import_merchant_file.id_data_import_merchant_file';

    /**
     * @param \Spryker\Zed\DataImportMerchantPortalGui\Communication\GuiTable\Mapper\DataImportMerchantFileGuiTableMapperInterface $dataImportMerchantFileGuiTableMapper
     * @param \Spryker\Zed\DataImportMerchantPortalGui\Communication\Reader\DataImportMerchantFileReaderInterface $dataImportMerchantFileReader
     * @param \Spryker\Zed\DataImportMerchantPortalGui\Dependency\Service\DataImportMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        protected DataImportMerchantFileGuiTableMapperInterface $dataImportMerchantFileGuiTableMapper,
        protected DataImportMerchantFileReaderInterface $dataImportMerchantFileReader,
        protected DataImportMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService,
        protected DataImportMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileTableCriteriaTransfer
     */
    protected function createCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer): AbstractTransfer
    {
        return new DataImportMerchantFileTableCriteriaTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTableCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function fetchData(AbstractTransfer $criteriaTransfer): GuiTableDataResponseTransfer
    {
        $dataImportMerchantFileCriteriaTransfer = $this->createDataImportMerchantFileCriteriaTransfer($criteriaTransfer);
        $dataImportMerchantFileCollectionTransfer = $this->dataImportMerchantFileReader
            ->getDataImportMerchantFileCollection($dataImportMerchantFileCriteriaTransfer);

        $guiTableDataResponseTransfer = $this->dataImportMerchantFileGuiTableMapper
            ->mapDataImportMerchantFileCollectionTransferToGuiTableDataResponseTransfer(
                $dataImportMerchantFileCollectionTransfer,
                new GuiTableDataResponseTransfer(),
            );

        foreach ($dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles() as $dataImportMerchantFileTransfer) {
            $guiTableDataResponseTransfer->addRow($this->getRowData($dataImportMerchantFileTransfer));
        }

        return $guiTableDataResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTableCriteriaTransfer $dataImportMerchantFileTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCriteriaTransfer
     */
    protected function createDataImportMerchantFileCriteriaTransfer(
        DataImportMerchantFileTableCriteriaTransfer $dataImportMerchantFileTableCriteriaTransfer
    ): DataImportMerchantFileCriteriaTransfer {
        $dataImportMerchantFileCriteriaTransfer = $this->dataImportMerchantFileGuiTableMapper
            ->mapDataImportMerchantFileTableCriteriaTransferToDataImportMerchantFileCriteriaTransfer(
                $dataImportMerchantFileTableCriteriaTransfer,
                new DataImportMerchantFileCriteriaTransfer(),
            );

        if (
            $dataImportMerchantFileCriteriaTransfer->getSortCollection() !== null
            && $dataImportMerchantFileCriteriaTransfer->getSortCollection()->count() !== 0
        ) {
            return $dataImportMerchantFileCriteriaTransfer;
        }

        return $this->addDefaultSorting($dataImportMerchantFileCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCriteriaTransfer $dataImportMerchantFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCriteriaTransfer
     */
    protected function addDefaultSorting(
        DataImportMerchantFileCriteriaTransfer $dataImportMerchantFileCriteriaTransfer
    ): DataImportMerchantFileCriteriaTransfer {
        $sortTransfer = (new SortTransfer())
            ->setField(static::COL_ID_DATA_IMPORT_MERCHANT_FILE)
            ->setIsAscending(false);

        /** @var \ArrayObject<int, \Generated\Shared\Transfer\SortTransfer> $sortCollection */
        $sortCollection = new ArrayObject([$sortTransfer]);

        return $dataImportMerchantFileCriteriaTransfer->setSortCollection($sortCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableRowDataResponseTransfer
     */
    protected function getRowData(
        DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
    ): GuiTableRowDataResponseTransfer {
        $dataImportMerchantFileInfoTransfer = $dataImportMerchantFileTransfer->getFileInfoOrFail();

        return (new GuiTableRowDataResponseTransfer())->setResponseData([
            DataImportMerchantFileTableConfigurationProvider::COL_KEY_CREATED_AT => $dataImportMerchantFileTransfer->getCreatedAt(),
            DataImportMerchantFileTableConfigurationProvider::COL_KEY_FILE_NAME => $dataImportMerchantFileInfoTransfer->getOriginalFileName(),
            DataImportMerchantFileTableConfigurationProvider::COL_KEY_IMPORTER_TYPE => $this->translatorFacade->trans($dataImportMerchantFileTransfer->getImporterTypeOrFail()),
            DataImportMerchantFileTableConfigurationProvider::COL_KEY_IMPORTED_BY => $this->formatImportedByColumnData($dataImportMerchantFileTransfer),
            DataImportMerchantFileTableConfigurationProvider::COL_KEY_STATUS => $this->translatorFacade->trans($dataImportMerchantFileTransfer->getStatusOrFail()),
            DataImportMerchantFileTableConfigurationProvider::KEY_AVAILABLE_ACTIONS => $this->getAvailableRowActions($dataImportMerchantFileTransfer),
            DataImportMerchantFileTransfer::UUID => $dataImportMerchantFileTransfer->getUuid(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return array<string>
     */
    protected function getAvailableRowActions(DataImportMerchantFileTransfer $dataImportMerchantFileTransfer): array
    {
        $availableActions = [
            DataImportMerchantFileTableConfigurationProvider::ACTION_ID_DOWNLOAD_ORIGINAL_FILE,
        ];

        if ($this->merchantDataImportHasErrors($dataImportMerchantFileTransfer)) {
            $availableActions[] = DataImportMerchantFileTableConfigurationProvider::ACTION_ID_DOWNLOAD_ERRORS_FILE;
        }

        return $availableActions;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return bool
     */
    protected function merchantDataImportHasErrors(DataImportMerchantFileTransfer $dataImportMerchantFileTransfer): bool
    {
        $errors = $dataImportMerchantFileTransfer->getImportResult()?->getErrors();
        if (!$errors) {
            return false;
        }

        /** @var array<string, mixed> $decodedErrors */
        $decodedErrors = $this->utilEncodingService->decodeJson($errors, true);

        return count($decodedErrors) > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return string|null
     */
    protected function formatImportedByColumnData(DataImportMerchantFileTransfer $dataImportMerchantFileTransfer): ?string
    {
        $userTransfer = $dataImportMerchantFileTransfer->getUser();

        if (!$userTransfer) {
            return null;
        }

        $firstName = $userTransfer->getFirstName() ?? '';
        $lastName = $userTransfer->getLastName() ?? '';
        $fullName = trim(sprintf('%s %s', $firstName, $lastName));

        return $fullName !== '' ? $fullName : null;
    }
}

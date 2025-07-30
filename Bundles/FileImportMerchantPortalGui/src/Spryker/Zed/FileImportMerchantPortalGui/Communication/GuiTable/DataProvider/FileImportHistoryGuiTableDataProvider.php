<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Communication\GuiTable\DataProvider;

use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Generated\Shared\Transfer\MerchantFileImportTableCriteriaTransfer;
use Generated\Shared\Transfer\MerchantFileImportTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Shared\GuiTable\DataProvider\AbstractGuiTableDataProvider;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\FileImportMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\FileImportHistoryGuiTableConfigurationProvider;
use Spryker\Zed\FileImportMerchantPortalGui\Dependency\Facade\FileImportMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\FileImportMerchantPortalGui\Persistence\FileImportMerchantPortalGuiRepositoryInterface;

class FileImportHistoryGuiTableDataProvider extends AbstractGuiTableDataProvider
{
    /**
     * @var string
     */
    protected const KEY_UUID_MERCHANT_FILE_IMPORT = 'uuidMerchantFileImport';

    /**
     * @var string
     */
    protected const KEY_UUID_MERCHANT_FILE = 'uuidMerchantFile';

    /**
     * @param \Spryker\Zed\FileImportMerchantPortalGui\Persistence\FileImportMerchantPortalGuiRepositoryInterface $fileImportMerchantPortalGuiRepository
     * @param \Spryker\Zed\FileImportMerchantPortalGui\Dependency\Facade\FileImportMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        protected FileImportMerchantPortalGuiRepositoryInterface $fileImportMerchantPortalGuiRepository,
        protected FileImportMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportTableCriteriaTransfer
     */
    protected function createCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer): AbstractTransfer
    {
        return (new MerchantFileImportTableCriteriaTransfer())
            ->setPage($guiTableDataRequestTransfer->getPage())
            ->setPageSize($guiTableDataRequestTransfer->getPageSize())
            ->setOrderBy($guiTableDataRequestTransfer->getOrderBy())
            ->setOrderDirection($guiTableDataRequestTransfer->getOrderDirection());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportTableCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function fetchData(AbstractTransfer $criteriaTransfer): GuiTableDataResponseTransfer
    {
        $merchantFileImportCollectionTransfer = $this->fileImportMerchantPortalGuiRepository
            ->getMerchantFileImportTableData($criteriaTransfer);

        $guiTableDataResponseTransfer = new GuiTableDataResponseTransfer();

        foreach ($merchantFileImportCollectionTransfer->getMerchantFileImports() as $merchantFileImportTransfer) {
            $guiTableDataResponseTransfer->addRow($this->getRowData($merchantFileImportTransfer));
        }

        $paginationTransfer = $merchantFileImportCollectionTransfer->getPagination() ?? new PaginationTransfer();

        return $guiTableDataResponseTransfer
            ->setPage($paginationTransfer->getPage())
            ->setPageSize($paginationTransfer->getMaxPerPage())
            ->setTotal($paginationTransfer->getNbResults());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportTransfer $merchantFileImportTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableRowDataResponseTransfer
     */
    protected function getRowData(
        MerchantFileImportTransfer $merchantFileImportTransfer
    ): GuiTableRowDataResponseTransfer {
        $merchantFileTransfer = $merchantFileImportTransfer->getMerchantFileOrFail();

        return (new GuiTableRowDataResponseTransfer())->setResponseData([
            FileImportHistoryGuiTableConfigurationProvider::COL_KEY_CREATED_AT => $merchantFileImportTransfer->getCreatedAt(),
            FileImportHistoryGuiTableConfigurationProvider::COL_KEY_FILE_NAME => $merchantFileTransfer->getOriginalFileName(),
            FileImportHistoryGuiTableConfigurationProvider::COL_KEY_ENTITY_TYPE => $this->translatorFacade->trans($merchantFileImportTransfer->getEntityTypeOrFail()),
            FileImportHistoryGuiTableConfigurationProvider::COL_KEY_IMPORTED_BY => $this->formatImportedByColumnData($merchantFileImportTransfer),
            FileImportHistoryGuiTableConfigurationProvider::COL_KEY_STATUS => $this->translatorFacade->trans($merchantFileImportTransfer->getStatusOrFail()),
            FileImportHistoryGuiTableConfigurationProvider::KEY_AVAILABLE_ACTIONS => $this->getAvailableRowActions($merchantFileImportTransfer),
            static::KEY_UUID_MERCHANT_FILE_IMPORT => $merchantFileImportTransfer->getUuid(),
            static::KEY_UUID_MERCHANT_FILE => $merchantFileTransfer->getUuid(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportTransfer $merchantFileImportTransfer
     *
     * @return array<string>
     */
    protected function getAvailableRowActions(MerchantFileImportTransfer $merchantFileImportTransfer): array
    {
        $availableActions = [
            FileImportHistoryGuiTableConfigurationProvider::ACTION_ID_DOWNLOAD_ORIGINAL_FILE,
        ];

        if ($this->merchantFileImportHasErrors($merchantFileImportTransfer)) {
            $availableActions[] = FileImportHistoryGuiTableConfigurationProvider::ACTION_ID_DOWNLOAD_ERRORS_FILE;
        }

        return $availableActions;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportTransfer $merchantFileImportTransfer
     *
     * @return string|null
     */
    protected function formatImportedByColumnData(MerchantFileImportTransfer $merchantFileImportTransfer): ?string
    {
        $userTransfer = $merchantFileImportTransfer->getMerchantFile()?->getUser();

        if (!$userTransfer) {
            return null;
        }

        return sprintf('%s %s', $userTransfer->getFirstName(), $userTransfer->getLastName());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportTransfer $merchantFileImportTransfer
     *
     * @return bool
     */
    protected function merchantFileImportHasErrors(MerchantFileImportTransfer $merchantFileImportTransfer): bool
    {
        if (!$merchantFileImportTransfer->getErrors()) {
            return false;
        }

        /** @var array<string, mixed> $decodedErrors */
        $decodedErrors = json_decode($merchantFileImportTransfer->getErrors(), true);

        return count($decodedErrors) > 0;
    }
}

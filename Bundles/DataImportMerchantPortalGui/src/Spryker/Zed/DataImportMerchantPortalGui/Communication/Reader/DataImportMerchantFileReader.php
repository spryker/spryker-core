<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchantPortalGui\Communication\Reader;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileConditionsTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileCriteriaTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\DataImportMerchantFileTableConfigurationProvider;
use Spryker\Zed\DataImportMerchantPortalGui\DataImportMerchantPortalGuiConfig;
use Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToDataImportMerchantFacadeInterface;
use Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToMerchantUserFacadeInterface;

class DataImportMerchantFileReader implements DataImportMerchantFileReaderInterface
{
    /**
     * @param \Spryker\Zed\DataImportMerchantPortalGui\DataImportMerchantPortalGuiConfig $dataImportMerchantPortalGuiConfig
     * @param \Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToDataImportMerchantFacadeInterface $dataImportMerchantFacade
     * @param \Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     */
    public function __construct(
        protected DataImportMerchantPortalGuiConfig $dataImportMerchantPortalGuiConfig,
        protected DataImportMerchantPortalGuiToDataImportMerchantFacadeInterface $dataImportMerchantFacade,
        protected DataImportMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCriteriaTransfer $dataImportMerchantFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer
     */
    public function getDataImportMerchantFileCollection(
        DataImportMerchantFileCriteriaTransfer $dataImportMerchantFileCriteriaTransfer
    ): DataImportMerchantFileCollectionTransfer {
        $merchantUserTransfer = $this->merchantUserFacade->getCurrentMerchantUser();
        $dataImportMerchantFileCriteriaTransfer
            ->getDataImportMerchantFileConditionsOrFail()
            ->addMerchantReference($merchantUserTransfer->getMerchantOrFail()->getMerchantReferenceOrFail());

        return $this->dataImportMerchantFacade->getDataImportMerchantFileCollection($dataImportMerchantFileCriteriaTransfer);
    }

    /**
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileTransfer|null
     */
    public function findDataImportMerchantFileByUuid(string $uuid): ?DataImportMerchantFileTransfer
    {
        $dataImportMerchantFileCriteriaTransfer = $this->createDataImportMerchantFileCriteriaTransfer($uuid);

        return $this->dataImportMerchantFacade
            ->getDataImportMerchantFileCollection($dataImportMerchantFileCriteriaTransfer)
            ->getDataImportMerchantFiles()
            ->getIterator()
            ->current();
    }

    /**
     * @return array<string, array<int|string, string>>
     */
    public function getFilterOptions(): array
    {
        $statusOptions = [];
        $importedByOptions = [];
        $importerTypeOptions = [];

        $dataImportMerchantFileCriteriaTransfer = $this->createDataImportMerchantFileCriteriaTransfer();
        $readCollectionBatchSize = $this->dataImportMerchantPortalGuiConfig
            ->getReadDataImportMerchantFileCollectionBatchSize();
        $offset = 0;

        do {
            $paginationTransfer = (new PaginationTransfer())
                ->setOffset($offset)
                ->setLimit($readCollectionBatchSize);

            $dataImportMerchantFileCriteriaTransfer->setPagination($paginationTransfer);
            $dataImportMerchantFileCollectionTransfer = $this->dataImportMerchantFacade
                ->getDataImportMerchantFileCollection($dataImportMerchantFileCriteriaTransfer);

            if ($dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles()->count() === 0) {
                break;
            }

            $statusOptions = $this->addStatusFilterOptions($statusOptions, $dataImportMerchantFileCollectionTransfer);
            $importedByOptions = $this->addImportedByFilterOptions($importedByOptions, $dataImportMerchantFileCollectionTransfer);
            $importerTypeOptions = $this->addImporterTypeFilterOptions($importerTypeOptions, $dataImportMerchantFileCollectionTransfer);

            $offset += $readCollectionBatchSize;
        } while (
            $dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles()->count() !== 0
        );

        return [
            DataImportMerchantFileTableConfigurationProvider::FILTER_ID_ENTITY_TYPES => $importerTypeOptions,
            DataImportMerchantFileTableConfigurationProvider::FILTER_ID_IMPORTED_BY => $importedByOptions,
            DataImportMerchantFileTableConfigurationProvider::FILTER_ID_STATUSES => $statusOptions,
        ];
    }

    /**
     * @param array<string, string> $importerTypeOptions
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer $dataImportMerchantFileCollectionTransfer
     *
     * @return array<string, string>
     */
    protected function addImporterTypeFilterOptions(
        array $importerTypeOptions,
        DataImportMerchantFileCollectionTransfer $dataImportMerchantFileCollectionTransfer
    ): array {
        foreach ($dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles() as $dataImportMerchantFileTransfer) {
            $importerType = $dataImportMerchantFileTransfer->getImporterTypeOrFail();
            $importerTypeOptions[$importerType] = $importerType;
        }

        return $importerTypeOptions;
    }

    /**
     * @param array<int|string, string> $importedByOptions
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer $dataImportMerchantFileCollectionTransfer
     *
     * @return array<int|string, string>
     */
    protected function addImportedByFilterOptions(
        array $importedByOptions,
        DataImportMerchantFileCollectionTransfer $dataImportMerchantFileCollectionTransfer
    ): array {
        foreach ($dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles() as $dataImportMerchantFileTransfer) {
            $userTransfer = $dataImportMerchantFileTransfer->getUser();
            if (!$userTransfer) {
                continue;
            }

            $userId = $userTransfer->getIdUserOrFail();
            $importedByOptions[$userId] = sprintf(
                '%s %s',
                $userTransfer->getFirstNameOrFail(),
                $userTransfer->getLastNameOrFail(),
            );
        }

        return $importedByOptions;
    }

    /**
     * @param array<string, string> $statusOptions
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer $dataImportMerchantFileCollectionTransfer
     *
     * @return array<string, string>
     */
    protected function addStatusFilterOptions(
        array $statusOptions,
        DataImportMerchantFileCollectionTransfer $dataImportMerchantFileCollectionTransfer
    ): array {
        foreach ($dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles() as $dataImportMerchantFileTransfer) {
            $status = $dataImportMerchantFileTransfer->getStatusOrFail();
            $statusOptions[$status] = $status;
        }

        return $statusOptions;
    }

    /**
     * @param string|null $uuid
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCriteriaTransfer
     */
    protected function createDataImportMerchantFileCriteriaTransfer(
        ?string $uuid = null
    ): DataImportMerchantFileCriteriaTransfer {
        $merchantUserTransfer = $this->merchantUserFacade->getCurrentMerchantUser();
        $dataImportMerchantFileConditionsTransfer = (new DataImportMerchantFileConditionsTransfer())
            ->addMerchantReference($merchantUserTransfer->getMerchantOrFail()->getMerchantReferenceOrFail());

        if ($uuid) {
            $dataImportMerchantFileConditionsTransfer->addUuid($uuid);
        }

        return (new DataImportMerchantFileCriteriaTransfer())
            ->setDataImportMerchantFileConditions($dataImportMerchantFileConditionsTransfer);
    }
}

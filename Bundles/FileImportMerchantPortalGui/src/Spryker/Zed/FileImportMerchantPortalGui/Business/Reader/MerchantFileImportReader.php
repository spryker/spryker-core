<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Business\Reader;

use Generated\Shared\Transfer\MerchantFileConditionsTransfer;
use Generated\Shared\Transfer\MerchantFileCriteriaTransfer;
use Generated\Shared\Transfer\MerchantFileImportCollectionTransfer;
use Generated\Shared\Transfer\MerchantFileImportCriteriaTransfer;
use Generated\Shared\Transfer\MerchantFileImportTableCriteriaTransfer;
use Generated\Shared\Transfer\MerchantFileImportTransfer;
use Spryker\Zed\FileImportMerchantPortalGui\Dependency\Facade\FileImportMerchantPortalGuiToMerchantFileFacadeInterface;
use Spryker\Zed\FileImportMerchantPortalGui\Persistence\FileImportMerchantPortalGuiRepositoryInterface;

class MerchantFileImportReader implements MerchantFileImportReaderInterface
{
    /**
     * @param \Spryker\Zed\FileImportMerchantPortalGui\Persistence\FileImportMerchantPortalGuiRepositoryInterface $fileImportMerchantPortalGuiRepository
     * @param \Spryker\Zed\FileImportMerchantPortalGui\Dependency\Facade\FileImportMerchantPortalGuiToMerchantFileFacadeInterface $MerchantFileFacade
     */
    public function __construct(
        protected FileImportMerchantPortalGuiRepositoryInterface $fileImportMerchantPortalGuiRepository,
        protected FileImportMerchantPortalGuiToMerchantFileFacadeInterface $MerchantFileFacade
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportCriteriaTransfer $merchantFileImportCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportTransfer|null
     */
    public function findMerchantFileImport(
        MerchantFileImportCriteriaTransfer $merchantFileImportCriteriaTransfer
    ): ?MerchantFileImportTransfer {
        $merchantFileImportCollectionTransfer = $this->getMerchantFileImportCollection($merchantFileImportCriteriaTransfer);

        return $merchantFileImportCollectionTransfer->getMerchantFileImports()->getIterator()->current();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportCriteriaTransfer $merchantFileImportCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportCollectionTransfer
     */
    public function getMerchantFileImportCollection(
        MerchantFileImportCriteriaTransfer $merchantFileImportCriteriaTransfer
    ): MerchantFileImportCollectionTransfer {
        $merchantFileImportCollectionTransfer = $this->fileImportMerchantPortalGuiRepository
            ->getMerchantFileImportCollection($merchantFileImportCriteriaTransfer);

        $merchantFileImportCollectionTransfer = $this->expandMerchantFileImportCollectionWithMerchantFiles(
            $merchantFileImportCollectionTransfer,
        );

        return $merchantFileImportCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportTableCriteriaTransfer $merchantFileImportTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportCollectionTransfer
     */
    public function getMerchantFileImportTableData(
        MerchantFileImportTableCriteriaTransfer $merchantFileImportTableCriteriaTransfer
    ): MerchantFileImportCollectionTransfer {
        return $this->fileImportMerchantPortalGuiRepository->getMerchantFileImportTableData($merchantFileImportTableCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportCollectionTransfer $merchantFileImportCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportCollectionTransfer
     */
    protected function expandMerchantFileImportCollectionWithMerchantFiles(
        MerchantFileImportCollectionTransfer $merchantFileImportCollectionTransfer
    ): MerchantFileImportCollectionTransfer {
        $merchantFileIds = [];

        foreach ($merchantFileImportCollectionTransfer->getMerchantFileImports() as $merchantFileImportTransfer) {
            $merchantFileIds[] = $merchantFileImportTransfer->getFkMerchantFileOrFail();
        }

        if (!$merchantFileIds) {
            return $merchantFileImportCollectionTransfer;
        }

        $merchantFileCriteriaTransfer = $this->buildMerchantFileCriteriaTransfer($merchantFileIds);

        $merchantFileCollectionTransfer = $this->MerchantFileFacade->getMerchantFileCollection(
            $merchantFileCriteriaTransfer,
        );

        if (!$merchantFileCollectionTransfer->getMerchantFiles()->count()) {
            return $merchantFileImportCollectionTransfer;
        }

        foreach ($merchantFileCollectionTransfer->getMerchantFiles() as $merchantFileTransfer) {
            foreach ($merchantFileImportCollectionTransfer->getMerchantFileImports() as $merchantFileImportTransfer) {
                if ($merchantFileTransfer->getIdMerchantFile() !== $merchantFileImportTransfer->getFkMerchantFile()) {
                    continue;
                }

                $merchantFileImportTransfer->setMerchantFile($merchantFileTransfer);
            }
        }

        return $merchantFileImportCollectionTransfer;
    }

    /**
     * @param array<int> $merchantFileIds
     *
     * @return \Generated\Shared\Transfer\MerchantFileCriteriaTransfer
     */
    protected function buildMerchantFileCriteriaTransfer(array $merchantFileIds): MerchantFileCriteriaTransfer
    {
        return (new MerchantFileCriteriaTransfer())->setMerchantFileConditions(
            (new MerchantFileConditionsTransfer())->setMerchantFileIds($merchantFileIds),
        );
    }
}

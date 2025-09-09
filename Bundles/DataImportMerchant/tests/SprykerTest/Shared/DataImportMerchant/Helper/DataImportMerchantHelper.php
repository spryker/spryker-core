<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\DataImportMerchant\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\DataImportMerchantFileBuilder;
use Generated\Shared\Transfer\DataImportMerchantFileTransfer;
use Orm\Zed\DataImportMerchant\Persistence\SpyDataImportMerchantFile;
use Orm\Zed\DataImportMerchant\Persistence\SpyDataImportMerchantFileQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class DataImportMerchantHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array<string, mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileTransfer
     */
    public function haveDataImportMerchantFile(array $seedData = []): DataImportMerchantFileTransfer
    {
        if (isset($seedData['uuid'])) {
            $dataImportMerchantFileEntity = $this->getDataImportMerchantFileQuery()->findOneByUuid($seedData['uuid']);

            if ($dataImportMerchantFileEntity) {
                return (new DataImportMerchantFileTransfer())->fromArray($dataImportMerchantFileEntity->toArray(), true);
            }
        }

        $dataImportMerchantFileTransfer = (new DataImportMerchantFileBuilder($seedData))->build();
        $dataImportMerchantFileTransfer = $this->createDataImportMerchantFile($dataImportMerchantFileTransfer);

        $this->getDataCleanupHelper()->addCleanup(function () use ($dataImportMerchantFileTransfer): void {
            $this->deleteDataImportMerchantFile($dataImportMerchantFileTransfer);
        });

        return $dataImportMerchantFileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileTransfer
     */
    protected function createDataImportMerchantFile(DataImportMerchantFileTransfer $dataImportMerchantFileTransfer): DataImportMerchantFileTransfer
    {
        $data = $dataImportMerchantFileTransfer->modifiedToArray();
        unset($data['file_info'], $data['import_result']);

        $dataImportMerchantFileEntity = (new SpyDataImportMerchantFile())
            ->fromArray($data)
            ->setOriginalFileName($dataImportMerchantFileTransfer->getFileInfo()?->getOriginalFileName() ?? '')
            ->setFkUser($dataImportMerchantFileTransfer->getIdUser());

        if ($dataImportMerchantFileTransfer->getFileInfo()) {
            $dataImportMerchantFileEntity->setFileInfo(
                json_encode($dataImportMerchantFileTransfer->getFileInfo()->toArray()) ?: '',
            );
        }

        if ($dataImportMerchantFileTransfer->getImportResult()) {
            $dataImportMerchantFileEntity->setImportResult(
                json_encode($dataImportMerchantFileTransfer->getImportResult()->toArray()) ?: '',
            );
        }

        $dataImportMerchantFileEntity->save();

        $resultTransfer = new DataImportMerchantFileTransfer();
        $entityArray = $dataImportMerchantFileEntity->toArray();
        unset($entityArray['file_info'], $entityArray['import_result']);

        $resultTransfer->fromArray($entityArray, true);
        $resultTransfer->setIdUser($dataImportMerchantFileEntity->getFkUser());

        if ($dataImportMerchantFileTransfer->getFileInfo()) {
            $resultTransfer->setFileInfo($dataImportMerchantFileTransfer->getFileInfo());
        }

        if ($dataImportMerchantFileTransfer->getImportResult()) {
            $resultTransfer->setImportResult($dataImportMerchantFileTransfer->getImportResult());
        }

        return $resultTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return void
     */
    protected function deleteDataImportMerchantFile(DataImportMerchantFileTransfer $dataImportMerchantFileTransfer): void
    {
        $this->getDataImportMerchantFileQuery()
            ->filterByIdDataImportMerchantFile($dataImportMerchantFileTransfer->getIdDataImportMerchantFile())
            ->delete();
    }

    /**
     * @return \Orm\Zed\DataImportMerchant\Persistence\SpyDataImportMerchantFileQuery
     */
    protected function getDataImportMerchantFileQuery(): SpyDataImportMerchantFileQuery
    {
        return SpyDataImportMerchantFileQuery::create();
    }
}

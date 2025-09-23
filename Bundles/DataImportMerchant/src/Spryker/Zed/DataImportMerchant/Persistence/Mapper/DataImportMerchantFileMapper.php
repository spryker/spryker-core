<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchant\Persistence\Mapper;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileInfoTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileResultTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileTransfer;
use Orm\Zed\DataImportMerchant\Persistence\SpyDataImportMerchantFile;
use Spryker\Zed\DataImportMerchant\Dependency\Service\DataImportMerchantToUtilEncodingServiceInterface;

class DataImportMerchantFileMapper
{
    /**
     * @param \Spryker\Zed\DataImportMerchant\Dependency\Service\DataImportMerchantToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(protected DataImportMerchantToUtilEncodingServiceInterface $utilEncodingService)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     * @param \Orm\Zed\DataImportMerchant\Persistence\SpyDataImportMerchantFile $dataImportMerchantFileEntity
     *
     * @return \Orm\Zed\DataImportMerchant\Persistence\SpyDataImportMerchantFile
     */
    public function mapDataImportMerchantFileTransferToDataImportMerchantFileEntity(
        DataImportMerchantFileTransfer $dataImportMerchantFileTransfer,
        SpyDataImportMerchantFile $dataImportMerchantFileEntity
    ): SpyDataImportMerchantFile {
        $data = $dataImportMerchantFileTransfer->modifiedToArray();
        unset($data['file_info'], $data['import_result']);

        $dataImportMerchantFileEntity
            ->fromArray($data)
            ->setFkUser($dataImportMerchantFileTransfer->getIdUserOrFail())
            ->setOriginalFileName($dataImportMerchantFileTransfer->getFileInfoOrFail()->getOriginalFileNameOrFail())
            ->setFileInfo($this->utilEncodingService->encodeJson($dataImportMerchantFileTransfer->getFileInfoOrFail()->toArray()) ?? '');

        if ($dataImportMerchantFileTransfer->getImportResult()) {
            $dataImportMerchantFileEntity->setImportResult(
                $this->utilEncodingService->encodeJson($dataImportMerchantFileTransfer->getImportResult()->toArray()),
            );
        }

        return $dataImportMerchantFileEntity;
    }

    /**
     * @param \Orm\Zed\DataImportMerchant\Persistence\SpyDataImportMerchantFile $dataImportMerchantFileEntity
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileTransfer
     */
    public function mapDataImportMerchantFileEntityToDataImportMerchantFileTransfer(
        SpyDataImportMerchantFile $dataImportMerchantFileEntity,
        DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
    ): DataImportMerchantFileTransfer {
        $fileInfoData = $dataImportMerchantFileEntity->getFileInfo();
        $fileInfoTransfer = new DataImportMerchantFileInfoTransfer();
        if ($fileInfoData !== null) {
            $fileInfoDataArray = $this->utilEncodingService->decodeJson($fileInfoData, true) ?: [];
            $fileInfoTransfer->fromArray($fileInfoDataArray);
        }

        $importResultTransfer = new DataImportMerchantFileResultTransfer();
        $importResultData = $dataImportMerchantFileEntity->getImportResult();
        if ($importResultData !== null) {
            $importResultDataArray = $this->utilEncodingService->decodeJson($importResultData, true);
            if ($importResultDataArray !== null) {
                $importResultTransfer->fromArray($importResultDataArray, true);
            }
        }

        $entityArray = $dataImportMerchantFileEntity->toArray();
        unset($entityArray['file_info'], $entityArray['import_result']);

        $dataImportMerchantFileTransfer
            ->fromArray($entityArray, true)
            ->setIdUser($dataImportMerchantFileEntity->getFkUser())
            ->setFileInfo($fileInfoTransfer)
            ->setImportResult($importResultTransfer);

        return $dataImportMerchantFileTransfer;
    }

    /**
     * @param iterable<\Orm\Zed\DataImportMerchant\Persistence\SpyDataImportMerchantFile> $dataImportMerchantFileEntities
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer $dataImportMerchantFileCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer
     */
    public function mapDataImportMerchantFileEntityCollectionToDataImportMerchantFileCollectionTransfer(
        iterable $dataImportMerchantFileEntities,
        DataImportMerchantFileCollectionTransfer $dataImportMerchantFileCollectionTransfer
    ): DataImportMerchantFileCollectionTransfer {
        foreach ($dataImportMerchantFileEntities as $dataImportMerchantFileEntity) {
            $dataImportMerchantFileTransfer = $this->mapDataImportMerchantFileEntityToDataImportMerchantFileTransfer(
                $dataImportMerchantFileEntity,
                new DataImportMerchantFileTransfer(),
            );

            $dataImportMerchantFileCollectionTransfer->addDataImportMerchantFile($dataImportMerchantFileTransfer);
        }

        return $dataImportMerchantFileCollectionTransfer;
    }
}

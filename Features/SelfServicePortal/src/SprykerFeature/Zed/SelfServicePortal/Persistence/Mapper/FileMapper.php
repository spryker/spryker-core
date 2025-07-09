<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyCollectionTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentTransfer;
use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Orm\Zed\FileManager\Persistence\SpyFile;
use Propel\Runtime\Collection\Collection;
use SprykerFeature\Zed\SelfServicePortal\Persistence\QueryBuilder\FileAttachmentQueryBuilder;

class FileMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\FileManager\Persistence\SpyFile> $fileEntities
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionTransfer $fileCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionTransfer
     */
    public function mapFileEntityCollectionToFileAttachmentCollectionTransfer(
        Collection $fileEntities,
        FileAttachmentCollectionTransfer $fileCollectionTransfer
    ): FileAttachmentCollectionTransfer {
        /** @var \Orm\Zed\FileManager\Persistence\SpyFile $fileEntity */
        foreach ($fileEntities as $fileEntity) {
            $fileTransfer = $this->mapFileEntityToFileTransfer($fileEntity);
            $fileTransfer = $this->addFileInfoTransfers($fileEntity, $fileTransfer);

            $fileAttachmentTransfer = (new FileAttachmentTransfer())->setFile($fileTransfer);

            $fileAttachmentTransfer = $this->initEmptyCollections($fileAttachmentTransfer);

            if ($fileEntity->hasVirtualColumn(FileAttachmentQueryBuilder::SSP_ASSET_IDS_COLUMN)) {
                foreach ($this->extractRelationIds($fileEntity->getVirtualColumn(FileAttachmentQueryBuilder::SSP_ASSET_IDS_COLUMN)) as $sspAssetId) {
                    $fileAttachmentTransfer->getSspAssetCollectionOrFail()->addSspAsset(
                        (new SspAssetTransfer())->setIdSspAsset((int)$sspAssetId),
                    );
                }
            }
            if ($fileEntity->hasVirtualColumn(FileAttachmentQueryBuilder::COMPANY_USER_IDS_COLUMN)) {
                foreach ($this->extractRelationIds($fileEntity->getVirtualColumn(FileAttachmentQueryBuilder::COMPANY_USER_IDS_COLUMN)) as $companyUserId) {
                    $fileAttachmentTransfer->getCompanyUserCollectionOrFail()->addCompanyUser(
                        (new CompanyUserTransfer())->setIdCompanyUser((int)$companyUserId),
                    );
                }
            }
            if ($fileEntity->hasVirtualColumn(FileAttachmentQueryBuilder::BUSINESS_UNIT_IDS_COLUMN)) {
                foreach ($this->extractRelationIds($fileEntity->getVirtualColumn(FileAttachmentQueryBuilder::BUSINESS_UNIT_IDS_COLUMN)) as $businessUnitId) {
                    $fileAttachmentTransfer->getBusinessUnitCollectionOrFail()->addCompanyBusinessUnit(
                        (new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit((int)$businessUnitId),
                    );
                }
            }
            if ($fileEntity->hasVirtualColumn(FileAttachmentQueryBuilder::COMPANY_IDS_COLUMN)) {
                foreach ($this->extractRelationIds($fileEntity->getVirtualColumn(FileAttachmentQueryBuilder::COMPANY_IDS_COLUMN)) as $companyId) {
                    $fileAttachmentTransfer->getCompanyCollectionOrFail()->addCompany(
                        (new CompanyTransfer())->setIdCompany((int)$companyId),
                    );
                }
            }

            $fileCollectionTransfer->addFileAttachment($fileAttachmentTransfer);
        }

        return $fileCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $fileEntity
     *
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    protected function mapFileEntityToFileTransfer(SpyFile $fileEntity): FileTransfer
    {
        return (new FileTransfer())
            ->fromArray($fileEntity->toArray(), true);
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $file
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    protected function addFileInfoTransfers(SpyFile $file, FileTransfer $fileTransfer): FileTransfer
    {
        foreach ($file->getSpyFileInfos() as $fileInfo) {
            $fileTransfer->addFileInfo(
                (new FileInfoTransfer())->fromArray(
                    $fileInfo->toArray(),
                    true,
                ),
            );
        }

        return $fileTransfer;
    }

    /**
     * @param string|null $relationIdsString
     *
     * @return array<int|string>
     */
    protected function extractRelationIds(?string $relationIdsString): array
    {
        if (!$relationIdsString) {
            return [];
        }

        return array_unique(explode(',', $relationIdsString));
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentTransfer
     */
    protected function initEmptyCollections(FileAttachmentTransfer $fileAttachmentTransfer): FileAttachmentTransfer
    {
        return $fileAttachmentTransfer
            ->setSspAssetCollection(new SspAssetCollectionTransfer())
            ->setCompanyUserCollection(new CompanyUserCollectionTransfer())
            ->setBusinessUnitCollection(new CompanyBusinessUnitCollectionTransfer())
            ->setCompanyCollection(new CompanyCollectionTransfer());
    }
}

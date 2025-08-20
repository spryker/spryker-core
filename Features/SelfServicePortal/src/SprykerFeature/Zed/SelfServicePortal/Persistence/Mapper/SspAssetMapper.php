<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\SspAssetIncludeTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAsset;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;

class SspAssetMapper
{
    public function __construct(protected UtilDateTimeServiceInterface $utilDateTimeService)
    {
    }

    public function mapSspAssetTransferToSpySspAssetEntity(
        SspAssetTransfer $sspAssetTransfer,
        SpySspAsset $sspAssetEntity
    ): SpySspAsset {
        $sspAssetEntity->fromArray($sspAssetTransfer->modifiedToArray());

        $sspAssetEntity->setFkCompanyBusinessUnit($sspAssetTransfer->getCompanyBusinessUnit()?->getIdCompanyBusinessUnit());

        $sspAssetEntity->setFkImageFile($sspAssetTransfer->getImage()?->getIdFile());

        return $sspAssetEntity;
    }

    public function mapSpySspAssetEntityToSspAssetTransfer(
        SpySspAsset $spySspAssetEntity,
        SspAssetTransfer $sspAssetTransfer
    ): SspAssetTransfer {
        $sspAssetTransfer->fromArray($spySspAssetEntity->toArray(), true);
        if ($spySspAssetEntity->getCreatedAt()) {
            /**
             * @var \DateTime $createdAt
             */
            $createdAt = $spySspAssetEntity->getCreatedAt();
            $sspAssetTransfer->setCreatedDate($createdAt->format('Y-m-d H:i:s'));
        }

        if ($spySspAssetEntity->getFkImageFile()) {
            $sspAssetTransfer->setImage(
                (new FileTransfer())->setIdFile($spySspAssetEntity->getFkImageFile()),
            );
        }

        if ($spySspAssetEntity->getFkCompanyBusinessUnit()) {
            $sspAssetTransfer->setCompanyBusinessUnit(
                (new CompanyBusinessUnitTransfer())
                    ->setIdCompanyBusinessUnit(
                        $spySspAssetEntity->getFkCompanyBusinessUnit(),
                    )
                    ->setFkCompany($spySspAssetEntity->getSpyCompanyBusinessUnit()?->getFkCompany()),
            );
        }

        return $sspAssetTransfer;
    }

    public function mapSpySspAssetEntityToSspAssetTransferIncludes(
        SpySspAsset $spySspAssetEntity,
        SspAssetTransfer $sspAssetTransfer,
        SspAssetIncludeTransfer $sspAssetIncludeTransfer
    ): SspAssetTransfer {
        if ($sspAssetIncludeTransfer->getWithOwnerCompanyBusinessUnit() && $spySspAssetEntity->getSpyCompanyBusinessUnit()) {
            /**
             * @var \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit $companyBusinessUnit
             */
            $companyBusinessUnit = $spySspAssetEntity->getSpyCompanyBusinessUnit();

            $sspAssetTransfer->setCompanyBusinessUnit(
                (new CompanyBusinessUnitTransfer())->fromArray($companyBusinessUnit->toArray(), true),
            );
        }

        if ($sspAssetIncludeTransfer->getWithSspModels()) {
            foreach ($spySspAssetEntity->getSpySspAssetToSspModels() as $spySspAssetToSspModelEntity) {
                $spySspModelEntity = $spySspAssetToSspModelEntity->getSpySspModel();
                if ($spySspModelEntity) {
                    $sspModelTransfer = (new SspModelTransfer())->fromArray($spySspModelEntity->toArray(), true);
                    $sspAssetTransfer->addModel($sspModelTransfer);
                }
            }
        }

        return $sspAssetTransfer;
    }
}

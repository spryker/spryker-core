<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
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

        if ($sspAssetTransfer->getCompanyBusinessUnit()?->getIdCompanyBusinessUnit()) {
            $sspAssetEntity->setFkCompanyBusinessUnit($sspAssetTransfer->getCompanyBusinessUnit()->getIdCompanyBusinessUnit());
        }

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

        $companyBusinessUnitOwnerEntity = $spySspAssetEntity->getSpyCompanyBusinessUnit();

        if ($companyBusinessUnitOwnerEntity) {
            $sspAssetTransfer->setCompanyBusinessUnit(
                (new CompanyBusinessUnitTransfer())
                    ->setIdCompanyBusinessUnit(
                        $companyBusinessUnitOwnerEntity->getIdCompanyBusinessUnit(),
                    )
                    ->setUuid($companyBusinessUnitOwnerEntity->getUuid())
                    ->setFkCompany($companyBusinessUnitOwnerEntity->getFkCompany()),
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
             * @var \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit $companyBusinessUnitEntity
             */
            $companyBusinessUnitEntity = $spySspAssetEntity->getSpyCompanyBusinessUnit();

            $companyBusinessUnitTransfer = (new CompanyBusinessUnitTransfer())->fromArray($companyBusinessUnitEntity->toArray(), true);

            if ($companyBusinessUnitEntity->getCompany() !== null) {
                $companyTransfer = (new CompanyTransfer())->fromArray($companyBusinessUnitEntity->getCompany()->toArray(), true);
                $companyBusinessUnitTransfer->setCompany($companyTransfer);
                $companyBusinessUnitTransfer->setFkCompany($companyTransfer->getIdCompany());
            }

            $sspAssetTransfer->setCompanyBusinessUnit($companyBusinessUnitTransfer);
        }

        if ($sspAssetIncludeTransfer->getWithSspModels() && $spySspAssetEntity->getSpySspAssetToSspModels()->count()) {
            foreach ($spySspAssetEntity->getSpySspAssetToSspModels() as $sspAssetToSspModelEntity) {
                $sspAssetTransfer->getSspModels()->append(
                    (new SspModelTransfer())->fromArray($sspAssetToSspModelEntity->getSpySspModel()->toArray(), true),
                );
            }
        }

        return $sspAssetTransfer;
    }
}

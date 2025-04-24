<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspAssetManagement\Persistence\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\SspAssetIncludeTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Orm\Zed\SspAssetManagement\Persistence\SpySspAsset;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;

class SspAssetMapper implements SspAssetMapperInterface
{
    /**
     * @param \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface $utilDateTimeService
     */
    public function __construct(protected UtilDateTimeServiceInterface $utilDateTimeService)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     * @param \Orm\Zed\SspAssetManagement\Persistence\SpySspAsset $sspAssetEntity
     *
     * @return \Orm\Zed\SspAssetManagement\Persistence\SpySspAsset
     */
    public function mapSspAssetTransferToSpySspAssetEntity(
        SspAssetTransfer $sspAssetTransfer,
        SpySspAsset $sspAssetEntity
    ): SpySspAsset {
        $sspAssetEntity->fromArray($sspAssetTransfer->modifiedToArray());

        $sspAssetEntity->setFkCompanyBusinessUnit($sspAssetTransfer->getCompanyBusinessUnit()?->getIdCompanyBusinessUnit());

        $sspAssetEntity->setFkImageFile($sspAssetTransfer->getImage()?->getIdFile());

        return $sspAssetEntity;
    }

    /**
     * @param \Orm\Zed\SspAssetManagement\Persistence\SpySspAsset $spySspAssetEntity
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetTransfer
     */
    public function mapSpySspAssetEntityToSspAssetTransfer(
        SpySspAsset $spySspAssetEntity,
        SspAssetTransfer $sspAssetTransfer
    ): SspAssetTransfer {
        $sspAssetTransfer->fromArray($spySspAssetEntity->toArray(), true);
        if ($spySspAssetEntity->getCreatedAt()) {
            $sspAssetTransfer->setCreatedDate($spySspAssetEntity->getCreatedAt()->format('Y-m-d H:i:s'));
        }

        if ($spySspAssetEntity->getFkImageFile()) {
            $sspAssetTransfer->setImage(
                (new FileTransfer())->setIdFile($spySspAssetEntity->getFkImageFile()),
            );
        }

        if ($spySspAssetEntity->getFkCompanyBusinessUnit()) {
            $sspAssetTransfer->setCompanyBusinessUnit(
                (new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit($spySspAssetEntity->getFkCompanyBusinessUnit()),
            );
        }

        return $sspAssetTransfer;
    }

    /**
     * @param \Orm\Zed\SspAssetManagement\Persistence\SpySspAsset $spySspAssetEntity
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     * @param \Generated\Shared\Transfer\SspAssetIncludeTransfer $sspAssetIncludeTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetTransfer
     */
    public function mapSpySspAssetEntityToSspAssetTransferIncludes(
        SpySspAsset $spySspAssetEntity,
        SspAssetTransfer $sspAssetTransfer,
        SspAssetIncludeTransfer $sspAssetIncludeTransfer
    ): SspAssetTransfer {
        if ($sspAssetIncludeTransfer->getWithCompanyBusinessUnit() && $spySspAssetEntity->getSpyCompanyBusinessUnit()) {
            $sspAssetTransfer->setCompanyBusinessUnit(
                (new CompanyBusinessUnitTransfer())->fromArray($spySspAssetEntity->getSpyCompanyBusinessUnit()->toArray(), true),
            );
        }

        return $sspAssetTransfer;
    }
}

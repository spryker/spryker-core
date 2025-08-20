<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper;

use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpySspModel;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;

class SspModelMapper
{
    public function __construct(protected UtilDateTimeServiceInterface $utilDateTimeService)
    {
    }

    public function mapSspModelTransferToSpySspModelEntity(
        SspModelTransfer $sspModelTransfer,
        SpySspModel $sspModelEntity
    ): SpySspModel {
        $sspModelEntity->fromArray($sspModelTransfer->modifiedToArray());

        $sspModelEntity->setFkImageFile($sspModelTransfer->getImage()?->getIdFile());

        return $sspModelEntity;
    }

    public function mapSpySspModelEntityToSspModelTransfer(
        SpySspModel $spySspModelEntity,
        SspModelTransfer $sspModelTransfer
    ): SspModelTransfer {
        $sspModelTransfer->fromArray($spySspModelEntity->toArray(), true);

        if ($spySspModelEntity->getFkImageFile()) {
            $sspModelTransfer->setImage(
                (new FileTransfer())->setIdFile($spySspModelEntity->getFkImageFile()),
            );
        }

        return $sspModelTransfer;
    }

    public function mapSpySspModelEntityToSspModelTransferWithAssets(
        SpySspModel $spySspModelEntity,
        SspModelTransfer $sspModelTransfer
    ): SspModelTransfer {
        foreach ($spySspModelEntity->getSpySspAssetToSspModels() as $spySspAssetToSspModelEntity) {
            $sspAssetEntity = $spySspAssetToSspModelEntity->getSpySspAsset();
            if ($sspAssetEntity) {
                $sspAssetTransfer = (new SspAssetTransfer())
                    ->setIdSspAsset($sspAssetEntity->getIdSspAsset())
                    ->setName($sspAssetEntity->getName())
                    ->setNote($sspAssetEntity->getNote());

                $sspModelTransfer->addSspAsset($sspAssetTransfer);
            }
        }

        return $sspModelTransfer;
    }

    public function mapSpySspModelEntityToSspModelTransferWithProductLists(
        SpySspModel $spySspModelEntity,
        SspModelTransfer $sspModelTransfer
    ): SspModelTransfer {
        foreach ($spySspModelEntity->getSpySspModelToProductLists() as $spySspModelToProductListEntity) {
            $productListEntity = $spySspModelToProductListEntity->getSpyProductList();
            if ($productListEntity) {
                $productListTransfer = (new ProductListTransfer())
                    ->setIdProductList($productListEntity->getIdProductList())
                    ->setTitle($productListEntity->getTitle())
                    ->setType($productListEntity->getType());

                $sspModelTransfer->addProductList($productListTransfer);
            }
        }

        return $sspModelTransfer;
    }
}

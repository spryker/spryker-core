<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\SspModel\DataImport\Step;

use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToSspModelQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspModelQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\Event\Business\EventFacadeInterface;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig as SharedSelfServicePortalConfig;
use SprykerFeature\Zed\SelfServicePortal\Business\SspModel\DataImport\DataSet\SspAssetModelDataSetInterface;

class SspModelAssetWriterStep implements DataImportStepInterface
{
    public function __construct(protected EventFacadeInterface $eventFacade)
    {
    }

    public function execute(DataSetInterface $dataSet): void
    {
        $assetReference = $dataSet[SspAssetModelDataSetInterface::COLUMN_ASSET_REFERENCE];
        $modelReference = $dataSet[SspAssetModelDataSetInterface::COLUMN_MODEL_REFERENCE];

        $sspAssetEntity = SpySspAssetQuery::create()
            ->filterByReference($assetReference)
            ->findOne();

        if (!$sspAssetEntity) {
            throw new EntityNotFoundException($assetReference);
        }

        $sspModelEntity = SpySspModelQuery::create()
            ->filterByReference($modelReference)
            ->findOne();

        if (!$sspModelEntity) {
            throw new EntityNotFoundException($modelReference);
        }

        $sspAssetModelEntity = SpySspAssetToSspModelQuery::create()
            ->filterByFkSspAsset($sspAssetEntity->getIdSspAsset())
            ->filterByFkSspModel($sspModelEntity->getIdSspModel())
            ->findOneOrCreate();

        if ($sspAssetModelEntity->isNew()) {
            $sspAssetModelEntity->save();
        }

        $this->triggerAssetPublishEvent($sspAssetEntity->getIdSspAsset());
        $this->triggerModelPublishEvent($sspModelEntity->getIdSspModel());
    }

    protected function triggerAssetPublishEvent(int $idSspAsset): void
    {
        $eventEntityTransfer = new EventEntityTransfer();
        $eventEntityTransfer->setId($idSspAsset);

        $this->eventFacade->trigger(SharedSelfServicePortalConfig::SSP_ASSET_PUBLISH, $eventEntityTransfer);
    }

    protected function triggerModelPublishEvent(int $idSspModel): void
    {
        $eventEntityTransfer = new EventEntityTransfer();
        $eventEntityTransfer->setId($idSspModel);

        $this->eventFacade->trigger(SharedSelfServicePortalConfig::SSP_MODEL_PUBLISH, $eventEntityTransfer);
    }
}

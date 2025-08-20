<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\DataImport\Step;

use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\Event\Business\EventFacadeInterface;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig as SharedSelfServicePortalConfig;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\DataImport\DataSet\SspAssetDataSetInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class SspAssetWriterStep implements DataImportStepInterface
{
    public function __construct(
        protected SelfServicePortalConfig $config,
        protected EventFacadeInterface $eventFacade
    ) {
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface<\Orm\Zed\SelfServicePortal\Persistence\SpySspAsset> $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $sspAssetEntity = SpySspAssetQuery::create()
            ->clear()
            ->filterByReference($dataSet[SspAssetDataSetInterface::COLUMN_REFERENCE])
            ->findOneOrCreate();

        $assetData = $dataSet->getArrayCopy();

        if (!isset($assetData[SspAssetDataSetInterface::COLUMN_STATUS]) || empty($assetData[SspAssetDataSetInterface::COLUMN_STATUS])) {
            $assetData[SspAssetDataSetInterface::COLUMN_STATUS] = $this->config->getInitialAssetStatus();
        }
        $sspAssetEntity->fromArray($assetData);
        $sspAssetEntity->save();
        $dataSet[SspAssetDataSetInterface::ID_SSP_ASSET] = $sspAssetEntity->getIdSspAsset();

        $this->triggerPublishEvent($sspAssetEntity->getIdSspAsset());
    }

    protected function triggerPublishEvent(int $idSspAsset): void
    {
        $eventEntityTransfer = new EventEntityTransfer();
        $eventEntityTransfer->setId($idSspAsset);

        $this->eventFacade->trigger(SharedSelfServicePortalConfig::SSP_ASSET_PUBLISH, $eventEntityTransfer);
    }
}

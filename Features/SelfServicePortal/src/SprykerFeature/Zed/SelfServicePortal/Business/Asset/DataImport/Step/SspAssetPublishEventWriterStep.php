<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\DataImport\Step;

use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\Event\Business\EventFacadeInterface;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig as SharedSelfServicePortalConfig;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\DataImport\DataSet\SspAssetDataSetInterface;

class SspAssetPublishEventWriterStep implements DataImportStepInterface
{
    public function __construct(protected EventFacadeInterface $eventFacade)
    {
    }

    public function execute(DataSetInterface $dataSet): void
    {
        $idSspAsset = $dataSet[SspAssetDataSetInterface::ID_SSP_ASSET];

        $this->triggerPublishEvent($idSspAsset);
    }

    protected function triggerPublishEvent(int $idSspAsset): void
    {
        $eventEntityTransfer = new EventEntityTransfer();
        $eventEntityTransfer->setId($idSspAsset);

        $this->eventFacade->trigger(SharedSelfServicePortalConfig::SSP_ASSET_PUBLISH, $eventEntityTransfer);
    }
}

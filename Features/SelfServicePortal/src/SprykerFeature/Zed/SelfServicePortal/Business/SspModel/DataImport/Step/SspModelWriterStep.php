<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\SspModel\DataImport\Step;

use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpySspModelQuery;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\Event\Business\EventFacadeInterface;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig as SharedSelfServicePortalConfig;
use SprykerFeature\Zed\SelfServicePortal\Business\SspModel\DataImport\DataSet\SspModelDataSetInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class SspModelWriterStep implements DataImportStepInterface
{
    public function __construct(
        protected SelfServicePortalConfig $config,
        protected SequenceNumberFacadeInterface $sequenceNumberFacade,
        protected EventFacadeInterface $eventFacade
    ) {
    }

    public function execute(DataSetInterface $dataSet): void
    {
        $this->sequenceNumberFacade->generate(
            $this->config->getModelSequenceNumberSettings(),
        );

        if (!$dataSet[SspModelDataSetInterface::COLUMN_REFERENCE]) {
            throw new DataImportException('Reference is required.');
        }

        if (!$dataSet[SspModelDataSetInterface::COLUMN_NAME]) {
            throw new DataImportException('Name is required.');
        }

        $sspModelEntity = SpySspModelQuery::create()
            ->filterByReference($dataSet[SspModelDataSetInterface::COLUMN_REFERENCE])
            ->findOneOrCreate();

        $sspModelEntity->fromArray($dataSet->getArrayCopy());

        if ($sspModelEntity->isNew() || $sspModelEntity->isModified()) {
            $sspModelEntity->save();
        }

        $this->triggerPublishEvent($sspModelEntity->getIdSspModel());
    }

    protected function triggerPublishEvent(int $idSspModel): void
    {
        $eventEntityTransfer = new EventEntityTransfer();
        $eventEntityTransfer->setId($idSspModel);

        $this->eventFacade->trigger(SharedSelfServicePortalConfig::SSP_MODEL_PUBLISH, $eventEntityTransfer);
    }
}

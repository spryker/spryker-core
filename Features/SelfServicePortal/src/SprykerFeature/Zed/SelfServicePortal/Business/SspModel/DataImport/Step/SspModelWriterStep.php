<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\SspModel\DataImport\Step;

use Orm\Zed\SelfServicePortal\Persistence\SpySspModelQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\SspModel\DataImport\DataSet\SspModelDataSetInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class SspModelWriterStep implements DataImportStepInterface
{
    public function __construct(
        protected SelfServicePortalConfig $config,
        protected SequenceNumberFacadeInterface $sequenceNumberFacade
    ) {
    }

    public function execute(DataSetInterface $dataSet): void
    {
        $this->sequenceNumberFacade->generate(
            $this->config->getModelSequenceNumberSettings(),
        );

        $sspModelEntity = SpySspModelQuery::create()
            ->filterByReference($dataSet[SspModelDataSetInterface::COLUMN_REFERENCE])
            ->findOneOrCreate();

        $sspModelEntity->fromArray($dataSet->getArrayCopy());

        if ($sspModelEntity->isNew() || $sspModelEntity->isModified()) {
            $sspModelEntity->save();
        }
    }
}

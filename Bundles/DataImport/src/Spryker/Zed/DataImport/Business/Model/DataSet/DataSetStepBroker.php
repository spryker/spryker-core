<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataSet;

use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepAfterExecuteInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepAwareInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepBeforeExecuteInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;

class DataSetStepBroker implements DataSetStepBrokerInterface, DataImportStepAwareInterface
{
    /**
     * @var \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface[]
     */
    protected $dataImportSteps = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface $dataImportStep
     *
     * @return $this
     */
    public function addStep(DataImportStepInterface $dataImportStep)
    {
        $this->dataImportSteps[] = $dataImportStep;

        return $this;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        foreach ($this->dataImportSteps as $dataImportStep) {
            if ($dataImportStep instanceof DataImportStepBeforeExecuteInterface) {
                $dataImportStep->beforeExecute();
            }

            $dataImportStep->execute($dataSet);

            if ($dataImportStep instanceof DataImportStepAfterExecuteInterface) {
                $dataImportStep->afterExecute();
            }
        }
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\PriceProductScheduleWriterStep;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step\AbstractSkuToIdProductAbstractStep;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step\ConcreteSkuToIdProductStep;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step\CurrencyToIdCurrencyStep;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step\PreparePriceDataStep;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step\PriceProductScheduleListNameToIdStep;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step\PriceTypeToIdPriceType;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step\StoreToIdStoreStep;

/**
 * @method \Spryker\Zed\PriceProductScheduleDataImport\PriceProductScheduleDataImportConfig getConfig()
 */
class PriceProductScheduleDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getPriceProductScheduleDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getPriceProductScheduleDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createPriceProductScheduleListNameToIdStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createAbstractSkuToIdProductAbstractStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createConcreteSkuToIdProductStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createStoreToIdStoreStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createCurrencyToIdCurrencyStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createPriceTypeToIdPriceTypeWriterStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createPreparePriceDataStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createPriceProductScheduleWriterStep());

        $dataImporter = $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAbstractSkuToIdProductAbstractStep(): DataImportStepInterface
    {
        return new AbstractSkuToIdProductAbstractStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createConcreteSkuToIdProductStep(): DataImportStepInterface
    {
        return new ConcreteSkuToIdProductStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createStoreToIdStoreStep(): DataImportStepInterface
    {
        return new StoreToIdStoreStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCurrencyToIdCurrencyStep(): DataImportStepInterface
    {
        return new CurrencyToIdCurrencyStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createPriceProductScheduleListNameToIdStep(): DataImportStepInterface
    {
        return new PriceProductScheduleListNameToIdStep($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createPriceTypeToIdPriceTypeWriterStep(): DataImportStepInterface
    {
        return new PriceTypeToIdPriceType();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createPreparePriceDataStep(): DataImportStepInterface
    {
        return new PreparePriceDataStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createPriceProductScheduleWriterStep(): DataImportStepInterface
    {
        return new PriceProductScheduleWriterStep();
    }
}

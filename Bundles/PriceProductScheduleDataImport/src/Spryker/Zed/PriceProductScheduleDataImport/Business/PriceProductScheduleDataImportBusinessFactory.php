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
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step\DateValidatorStep;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step\PreparePriceDataStep;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step\PriceProductScheduleListNameToIdStep;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step\PriceTypeToIdPriceTypeStep;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step\StoreNameToIdStoreStep;

/**
 * @method \Spryker\Zed\PriceProductScheduleDataImport\PriceProductScheduleDataImportConfig getConfig()
 */
class PriceProductScheduleDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getPriceProductScheduleDataImporter()
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporter $dataImporter */
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getPriceProductScheduleDataImporterConfiguration()
        );

        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware $dataSetStepBroker */
        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createDateValidatorStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createPriceProductScheduleListNameToIdStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createAbstractSkuToIdProductAbstractStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createConcreteSkuToIdProductStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createStoreNameToIdStoreStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createCurrencyToIdCurrencyStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createPriceTypeToIdPriceTypeStep());
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
    public function createStoreNameToIdStoreStep(): DataImportStepInterface
    {
        return new StoreNameToIdStoreStep();
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
    public function createPriceTypeToIdPriceTypeStep(): DataImportStepInterface
    {
        return new PriceTypeToIdPriceTypeStep();
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

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createDateValidatorStep(): DataImportStepInterface
    {
        return new DateValidatorStep();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockDataImport\Business;

use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\StockDataImport\Business\Writer\Step\NameValidatorStep;
use Spryker\Zed\StockDataImport\Business\Writer\StockWriterStep;
use Spryker\Zed\StockDataImport\StockDataImportDependencyProvider;

/**
 * @method \Spryker\Zed\StockDataImport\StockDataImportConfig getConfig()
 */
class StockDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getStockDataImporter()
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporter $dataImporter */
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getStockDataImporterConfiguration()
        );

        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware $dataSetStepBroker */
        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createStockWriterStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createNameValidatorStep());

        $dataImporter = $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createNameValidatorStep(): DataImportStepInterface
    {
        return new NameValidatorStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createStockWriterStep(): DataImportStepInterface
    {
        return new StockWriterStep($this->getStockPropelQuery());
    }

    /**
     * @return \Orm\Zed\Stock\Persistence\SpyStockQuery
     */
    protected function getStockPropelQuery(): SpyStockQuery
    {
        return $this->getProvidedDependency(StockDataImportDependencyProvider::PROPEL_QUERY_STOCK);
    }
}

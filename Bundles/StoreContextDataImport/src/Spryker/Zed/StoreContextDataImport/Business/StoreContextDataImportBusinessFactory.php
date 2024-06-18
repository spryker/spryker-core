<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextDataImport\Business;

use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Orm\Zed\StoreContext\Persistence\SpyStoreContextQuery;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\StoreContextDataImport\Business\DataImportStep\StoreContextWriterStep;
use Spryker\Zed\StoreContextDataImport\Business\DataImportStep\StoreNameToIdStoreStep;
use Spryker\Zed\StoreContextDataImport\StoreContextDataImportDependencyProvider;

/**
 * @method \Spryker\Zed\StoreContextDataImport\StoreContextDataImportConfig getConfig()
 */
class StoreContextDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getStoreContextDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getStoreContextDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createStoreNameToIdStoreStep())
            ->addStep($this->createStoreContextWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createStoreNameToIdStoreStep(): DataImportStepInterface
    {
        return new StoreNameToIdStoreStep($this->getStorePropelQuery());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createStoreContextWriterStep(): DataImportStepInterface
    {
        return new StoreContextWriterStep($this->getStoreContextPropelQuery());
    }

    /**
     * @return \Orm\Zed\StoreContext\Persistence\SpyStoreContextQuery<\Orm\Zed\StoreContext\Persistence\SpyStoreContext>
     */
    public function getStoreContextPropelQuery(): SpyStoreContextQuery
    {
        return $this->getProvidedDependency(StoreContextDataImportDependencyProvider::PROPEL_QUERY_STORE_CONTEXT);
    }

    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery<\Orm\Zed\Store\Persistence\SpyStore>
     */
    public function getStorePropelQuery(): SpyStoreQuery
    {
        return $this->getProvidedDependency(StoreContextDataImportDependencyProvider::PROPEL_QUERY_STORE);
    }
}

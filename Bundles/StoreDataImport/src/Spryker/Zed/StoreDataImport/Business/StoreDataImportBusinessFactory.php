<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\StoreDataImport\Business;

use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\StoreDataImport\Business\DataImportStep\StoreDataImportStep;
use Spryker\Zed\StoreDataImport\StoreDataImportDependencyProvider;

/**
 * @method \Spryker\Zed\StoreDataImport\StoreDataImportConfig getConfig()
 */
class StoreDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getStoreDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getStoreDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createStoreDataImportStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createStoreDataImportStep(): DataImportStepInterface
    {
        return new StoreDataImportStep($this->getStorePropelQuery());
    }

    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery<mixed>
     */
    public function getStorePropelQuery(): SpyStoreQuery
    {
        return $this->getProvidedDependency(StoreDataImportDependencyProvider::PROPEL_QUERY_STORE);
    }
}

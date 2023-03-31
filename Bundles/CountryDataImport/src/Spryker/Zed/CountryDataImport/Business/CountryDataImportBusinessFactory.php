<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CountryDataImport\Business;

use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Country\Persistence\SpyCountryStoreQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\CountryDataImport\Business\DataImportStep\CountryIso2CodeToIdCountryStep;
use Spryker\Zed\CountryDataImport\Business\DataImportStep\CountryStoreWriterStep;
use Spryker\Zed\CountryDataImport\Business\DataImportStep\StoreNameToIdStoreStep;
use Spryker\Zed\CountryDataImport\CountryDataImportDependencyProvider;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;

/**
 * @method \Spryker\Zed\CountryDataImport\CountryDataImportConfig getConfig()
 */
class CountryDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getCountryStoreDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getCountryStoreDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createCountryIso2CodeToIdCountryStep())
            ->addStep($this->createStoreNameToIdStoreStep())
            ->addStep($this->createCountryStoreWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCountryIso2CodeToIdCountryStep(): DataImportStepInterface
    {
        return new CountryIso2CodeToIdCountryStep($this->getCountryPropelQuery());
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
    public function createCountryStoreWriterStep(): DataImportStepInterface
    {
        return new CountryStoreWriterStep($this->getCountryStorePropelQuery());
    }

    /**
     * @return \Orm\Zed\Country\Persistence\SpyCountryQuery<mixed>
     */
    public function getCountryPropelQuery(): SpyCountryQuery
    {
        return $this->getProvidedDependency(CountryDataImportDependencyProvider::PROPEL_QUERY_COUNTRY);
    }

    /**
     * @return \Orm\Zed\Country\Persistence\SpyCountryStoreQuery<mixed>
     */
    public function getCountryStorePropelQuery(): SpyCountryStoreQuery
    {
        return $this->getProvidedDependency(CountryDataImportDependencyProvider::PROPEL_QUERY_COUNTRY_STORE);
    }

    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery<mixed>
     */
    public function getStorePropelQuery(): SpyStoreQuery
    {
        return $this->getProvidedDependency(CountryDataImportDependencyProvider::PROPEL_QUERY_STORE);
    }
}

<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\LocaleDataImport\Business;

use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Orm\Zed\Locale\Persistence\SpyLocaleStoreQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\LocaleDataImport\Business\DataImportStep\DefaultLocaleStoreWriterStep;
use Spryker\Zed\LocaleDataImport\Business\DataImportStep\LocaleNameToIdLocaleStep;
use Spryker\Zed\LocaleDataImport\Business\DataImportStep\LocaleStoreWriterStep;
use Spryker\Zed\LocaleDataImport\Business\DataImportStep\StoreNameToIdStoreStep;
use Spryker\Zed\LocaleDataImport\LocaleDataImportDependencyProvider;

/**
 * @method \Spryker\Zed\LocaleDataImport\LocaleDataImportConfig getConfig()
 */
class LocaleDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getLocaleStoreDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getLocaleStoreDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createStoreNameToIdStoreStep())
            ->addStep($this->createLocaleNameToIdLocaleStep())
            ->addStep($this->createLocaleStoreWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getDefaultLocaleStoreDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getDefaultLocaleStoreDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createLocaleNameToIdLocaleStep())
            ->addStep($this->createDefaultLocaleStoreWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createLocaleStoreWriterStep(): DataImportStepInterface
    {
        return new LocaleStoreWriterStep($this->getLocaleStorePropelQuery());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createDefaultLocaleStoreWriterStep(): DataImportStepInterface
    {
        return new DefaultLocaleStoreWriterStep($this->getStorePropelQuery());
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
    public function createLocaleNameToIdLocaleStep(): DataImportStepInterface
    {
        return new LocaleNameToIdLocaleStep($this->getLocalePropelQuery());
    }

    /**
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery<mixed>
     */
    public function getLocalePropelQuery(): SpyLocaleQuery
    {
        return $this->getProvidedDependency(LocaleDataImportDependencyProvider::PROPEL_QUERY_LOCALE);
    }

    /**
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleStoreQuery<mixed>
     */
    public function getLocaleStorePropelQuery(): SpyLocaleStoreQuery
    {
        return $this->getProvidedDependency(LocaleDataImportDependencyProvider::PROPEL_QUERY_LOCALE_STORE);
    }

    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery<mixed>
     */
    public function getStorePropelQuery(): SpyStoreQuery
    {
        return $this->getProvidedDependency(LocaleDataImportDependencyProvider::PROPEL_QUERY_STORE);
    }
}

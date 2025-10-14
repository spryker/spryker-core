<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CurrencyDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Orm\Zed\Currency\Persistence\SpyCurrencyQuery;
use Orm\Zed\Currency\Persistence\SpyCurrencyStoreQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\CurrencyDataImport\Business\DataImportStep\CurrencyCodeToIdCurrencyStep;
use Spryker\Zed\CurrencyDataImport\Business\DataImportStep\CurrencyStoreWriterStep;
use Spryker\Zed\CurrencyDataImport\Business\DataImportStep\StoreNameToIdStoreStep;
use Spryker\Zed\CurrencyDataImport\CurrencyDataImportDependencyProvider;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;

/**
 * @method \Spryker\Zed\CurrencyDataImport\CurrencyDataImportConfig getConfig()
 */
class CurrencyDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getCurrencyStoreDataImporter(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterInterface {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $dataImporterConfigurationTransfer ?? $this->getConfig()->getCurrencyStoreDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createCurrencyNameToIdCurrencyStep())
            ->addStep($this->createStoreNameToIdStoreStep())
            ->addStep($this->createCurrencyStoreWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCurrencyNameToIdCurrencyStep(): DataImportStepInterface
    {
        return new CurrencyCodeToIdCurrencyStep($this->getCurrencyPropelQuery());
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
    public function createCurrencyStoreWriterStep(): DataImportStepInterface
    {
        return new CurrencyStoreWriterStep(
            $this->getCurrencyStorePropelQuery(),
            $this->getStorePropelQuery(),
        );
    }

    /**
     * @return \Orm\Zed\Currency\Persistence\SpyCurrencyQuery<mixed>
     */
    public function getCurrencyPropelQuery(): SpyCurrencyQuery
    {
        return $this->getProvidedDependency(CurrencyDataImportDependencyProvider::PROPEL_QUERY_CURRENCY);
    }

    /**
     * @return \Orm\Zed\Currency\Persistence\SpyCurrencyStoreQuery<mixed>
     */
    public function getCurrencyStorePropelQuery(): SpyCurrencyStoreQuery
    {
        return $this->getProvidedDependency(CurrencyDataImportDependencyProvider::PROPEL_QUERY_CURRENCY_STORE);
    }

    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery<mixed>
     */
    public function getStorePropelQuery(): SpyStoreQuery
    {
        return $this->getProvidedDependency(CurrencyDataImportDependencyProvider::PROPEL_QUERY_STORE);
    }
}

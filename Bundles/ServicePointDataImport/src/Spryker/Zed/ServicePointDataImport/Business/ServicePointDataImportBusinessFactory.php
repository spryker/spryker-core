<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ServicePointDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\ServicePointDataImport\Business\DataImportStep\ServicePoint\ServicePointWriteDataImportStep;
use Spryker\Zed\ServicePointDataImport\Business\DataImportStep\ServicePointAddress\CountryIso2CodeToIdCountryStep;
use Spryker\Zed\ServicePointDataImport\Business\DataImportStep\ServicePointAddress\RegionBelongsToCountryStep;
use Spryker\Zed\ServicePointDataImport\Business\DataImportStep\ServicePointAddress\RegionIso2CodeToIdRegionStep;
use Spryker\Zed\ServicePointDataImport\Business\DataImportStep\ServicePointAddress\ServicePointAddressWriteDataImportStep;
use Spryker\Zed\ServicePointDataImport\Business\DataImportStep\ServicePointStore\ServicePointKeyToIdServicePointStep;
use Spryker\Zed\ServicePointDataImport\Business\DataImportStep\ServicePointStore\ServicePointStoreWriteDataImportStep;
use Spryker\Zed\ServicePointDataImport\Business\DataImportStep\ServicePointStore\StoreNameToIdStoreStep;

/**
 * @method \Spryker\Zed\ServicePointDataImport\ServicePointDataImportConfig getConfig()
 */
class ServicePointDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getServicePointDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getServicePointDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createServicePointWriteDataImportStep());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getServicePointStoreDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getServicePointStoreDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();

        $dataSetStepBroker->addStep($this->createServicePointKeyToIdServicePointStep());
        $dataSetStepBroker->addStep($this->createStoreNameToIdStoreStep());
        $dataSetStepBroker->addStep($this->createServicePointStoreWriteDataImportStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getServicePointAddressDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getServicePointAddressDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();

        $dataSetStepBroker->addStep($this->createServicePointKeyToIdServicePointStep());
        $dataSetStepBroker->addStep($this->createCountryIso2CodeToIdCountryStep());
        $dataSetStepBroker->addStep($this->createRegionIso2CodeToIdRegionStep());
        $dataSetStepBroker->addStep($this->createRegionBelongsToCountryStep());
        $dataSetStepBroker->addStep($this->createServicePointAddressWriteDataImportStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createServicePointAddressWriteDataImportStep(): DataImportStepInterface
    {
        return new ServicePointAddressWriteDataImportStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createRegionIso2CodeToIdRegionStep(): DataImportStepInterface
    {
        return new RegionIso2CodeToIdRegionStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createRegionBelongsToCountryStep(): DataImportStepInterface
    {
        return new RegionBelongsToCountryStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCountryIso2CodeToIdCountryStep(): DataImportStepInterface
    {
        return new CountryIso2CodeToIdCountryStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createServicePointWriteDataImportStep(): DataImportStepInterface
    {
        return new ServicePointWriteDataImportStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createServicePointKeyToIdServicePointStep(): DataImportStepInterface
    {
        return new ServicePointKeyToIdServicePointStep();
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
    public function createServicePointStoreWriteDataImportStep(): DataImportStepInterface
    {
        return new ServicePointStoreWriteDataImportStep();
    }
}

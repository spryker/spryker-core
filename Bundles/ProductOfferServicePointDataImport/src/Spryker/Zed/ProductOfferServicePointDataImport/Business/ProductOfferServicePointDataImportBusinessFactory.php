<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferServicePointDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\ProductOfferServicePointDataImport\Business\DataImportStep\ProductOfferService\ProductOfferHasOneServiceValidationDataImportStep;
use Spryker\Zed\ProductOfferServicePointDataImport\Business\DataImportStep\ProductOfferService\ProductOfferReferenceToIdProductOfferDataImportStep;
use Spryker\Zed\ProductOfferServicePointDataImport\Business\DataImportStep\ProductOfferService\ProductOfferServiceWriteDataImportStep;
use Spryker\Zed\ProductOfferServicePointDataImport\Business\DataImportStep\ProductOfferService\ServiceKeyToIdServiceDataImportStep;

/**
 * @method \Spryker\Zed\ProductOfferServicePointDataImport\ProductOfferServicePointDataImportConfig getConfig()
 */
class ProductOfferServicePointDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getProductOfferServiceDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getProductOfferServiceDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createServiceKeyToIdServiceDataImportStep());
        $dataSetStepBroker->addStep($this->createProductOfferReferenceToIdProductOfferDataImportStep());
        $dataSetStepBroker->addStep($this->createProductOfferHasOneServiceValidationDataImportStep());
        $dataSetStepBroker->addStep($this->createProductOfferServiceWriteDataImportStep());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductOfferServiceWriteDataImportStep(): DataImportStepInterface
    {
        return new ProductOfferServiceWriteDataImportStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createServiceKeyToIdServiceDataImportStep(): DataImportStepInterface
    {
        return new ServiceKeyToIdServiceDataImportStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductOfferHasOneServiceValidationDataImportStep(): DataImportStepInterface
    {
        return new ProductOfferHasOneServiceValidationDataImportStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductOfferReferenceToIdProductOfferDataImportStep(): DataImportStepInterface
    {
        return new ProductOfferReferenceToIdProductOfferDataImportStep();
    }
}

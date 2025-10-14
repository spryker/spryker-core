<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\ProductOfferShipmentTypeDataImport\Business\DataImportStep\ProductOfferShipmentType\ProductOfferReferenceToIdProductOfferDataImportStep;
use Spryker\Zed\ProductOfferShipmentTypeDataImport\Business\DataImportStep\ProductOfferShipmentType\ProductOfferShipmentTypeDataSetValidationStep;
use Spryker\Zed\ProductOfferShipmentTypeDataImport\Business\DataImportStep\ProductOfferShipmentType\ProductOfferShipmentTypeWriteDataImportStep;
use Spryker\Zed\ProductOfferShipmentTypeDataImport\Business\DataImportStep\ProductOfferShipmentType\ShipmentTypeKeyToIdShipmentTypeDataImportStep;

/**
 * @method \Spryker\Zed\ProductOfferShipmentTypeDataImport\ProductOfferShipmentTypeDataImportConfig getConfig()
 */
class ProductOfferShipmentTypeDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getProductOfferShipmentTypeDataImporter(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterInterface {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $dataImporterConfigurationTransfer ?? $this->getConfig()->getProductOfferShipmentTypeDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();

        $dataSetStepBroker->addStep($this->createProductOfferShipmentTypeDataSetValidationStep());
        $dataSetStepBroker->addStep($this->createProductOfferReferenceToIdProductOfferDataImportStep());
        $dataSetStepBroker->addStep($this->createShipmentTypeKeyToIdShipmentTypeDataImportStep());
        $dataSetStepBroker->addStep($this->createProductOfferShipmentTypeWriteDataImportStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductOfferShipmentTypeDataSetValidationStep(): DataImportStepInterface
    {
        return new ProductOfferShipmentTypeDataSetValidationStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductOfferReferenceToIdProductOfferDataImportStep(): DataImportStepInterface
    {
        return new ProductOfferReferenceToIdProductOfferDataImportStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createShipmentTypeKeyToIdShipmentTypeDataImportStep(): DataImportStepInterface
    {
        return new ShipmentTypeKeyToIdShipmentTypeDataImportStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductOfferShipmentTypeWriteDataImportStep(): DataImportStepInterface
    {
        return new ProductOfferShipmentTypeWriteDataImportStep();
    }
}

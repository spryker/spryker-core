<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\ProductOfferShipmentTypeDataImport\Business\DataImportStep\ProductOfferShipmentType\ProductOfferShipmentTypeDataSetValidationStep;
use Spryker\Zed\ProductOfferShipmentTypeDataImport\Business\DataImportStep\ProductOfferShipmentType\ProductOfferShipmentTypeWriteDataImportStep;
use Spryker\Zed\ProductOfferShipmentTypeDataImport\Business\DataImportStep\ProductOfferShipmentType\ShipmentTypeKeyToUuidShipmentTypeDataImportStep;

/**
 * @method \Spryker\Zed\ProductOfferShipmentTypeDataImport\ProductOfferShipmentTypeDataImportConfig getConfig()
 */
class ProductOfferShipmentTypeDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getProductOfferShipmentTypeDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getProductOfferShipmentTypeDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();

        $dataSetStepBroker->addStep($this->createProductOfferShipmentTypeDataSetValidationStep());
        $dataSetStepBroker->addStep($this->createShipmentTypeKeyToUuidShipmentTypeDataImportStep());
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
    public function createShipmentTypeKeyToUuidShipmentTypeDataImportStep(): DataImportStepInterface
    {
        return new ShipmentTypeKeyToUuidShipmentTypeDataImportStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductOfferShipmentTypeWriteDataImportStep(): DataImportStepInterface
    {
        return new ProductOfferShipmentTypeWriteDataImportStep();
    }
}

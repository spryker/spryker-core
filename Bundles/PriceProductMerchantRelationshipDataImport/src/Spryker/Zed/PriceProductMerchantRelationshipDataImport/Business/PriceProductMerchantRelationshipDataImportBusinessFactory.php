<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\Model\PriceProductMerchantRelationshipWriterStep;
use Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\Model\Step\CurrencyToIdCurrencyStep;
use Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\Model\Step\IdPriceProductStep;
use Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\Model\Step\MerchantRelationshipKeyToIdMerchantRelationshipStep;
use Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\Model\Step\ProductSkuToIdProductStep;
use Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\Model\Step\StoreToIdStoreStep;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipDataImport\PriceProductMerchantRelationshipDataImportConfig getConfig()
 */
class PriceProductMerchantRelationshipDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function createPriceProductMerchantRelationshipDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getPriceProductMerchantRelationshipDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createMerchantRelationshipKeyToIdBusinessUnitStep())
            ->addStep($this->createProductSkuToIdProductStep())
            ->addStep($this->createStoreToIdStoreStep())
            ->addStep($this->createCurrencyToIdCurrencyStep())
            ->addStep($this->createIdPriceProductStep())
            ->addStep(new PriceProductMerchantRelationshipWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantRelationshipKeyToIdBusinessUnitStep(): DataImportStepInterface
    {
        return new MerchantRelationshipKeyToIdMerchantRelationshipStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductSkuToIdProductStep(): DataImportStepInterface
    {
        return new ProductSkuToIdProductStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createStoreToIdStoreStep(): DataImportStepInterface
    {
        return new StoreToIdStoreStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCurrencyToIdCurrencyStep(): DataImportStepInterface
    {
        return new CurrencyToIdCurrencyStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createIdPriceProductStep(): DataImportStepInterface
    {
        return new IdPriceProductStep();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\PriceProductOfferDataImport\Business\Step\CurrencyToIdCurrencyStep;
use Spryker\Zed\PriceProductOfferDataImport\Business\Step\PriceProductOfferWriterStep;
use Spryker\Zed\PriceProductOfferDataImport\Business\Step\PriceTypeToIdPriceType;
use Spryker\Zed\PriceProductOfferDataImport\Business\Step\ProductOfferReferenceToIdProductOfferStep;
use Spryker\Zed\PriceProductOfferDataImport\Business\Step\StoreToIdStoreStep;

/**
 * @method \Spryker\Zed\PriceProductOfferDataImport\PriceProductOfferDataImportConfig getConfig()
 */
class PriceProductOfferDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createPriceProductOfferDataImport(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getPriceProductOfferDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();

        $dataSetStepBroker->addStep($this->createProductOfferReferenceToIdProductOfferStep());
        $dataSetStepBroker->addStep($this->createPriceTypeToPriceTypeStep());
        $dataSetStepBroker->addStep($this->createStoreToIdStoreStep());
        $dataSetStepBroker->addStep($this->createCurrencyToIdCurrencyStep());

        $dataSetStepBroker->addStep(new PriceProductOfferWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createPriceTypeToPriceTypeStep(): DataImportStepInterface
    {
        return new PriceTypeToIdPriceType();
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
    public function createProductOfferReferenceToIdProductOfferStep(): DataImportStepInterface
    {
        return new ProductOfferReferenceToIdProductOfferStep();
    }
}

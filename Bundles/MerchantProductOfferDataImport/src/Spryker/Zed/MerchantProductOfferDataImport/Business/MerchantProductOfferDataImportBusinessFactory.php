<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\ConcreteSkuValidationStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\MerchantKeyToIdMerchantStep;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\MerchantProductOfferStep;

/**
 * @method \Spryker\Zed\MerchantProductOfferDataImport\MerchantProductOfferDataImportConfig getConfig()
 */
class MerchantProductOfferDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getMerchantProductOfferDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getMerchantProductOfferDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();

        $dataSetStepBroker->addStep($this->createMerchantKeyToIdMerchantStep());
        $dataSetStepBroker->addStep($this->createConcreteSkuValidationStep());
        $dataSetStepBroker->addStep($this->createMerchantProductOfferStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantKeyToIdMerchantStep(): DataImportStepInterface
    {
        return new MerchantKeyToIdMerchantStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createConcreteSkuValidationStep(): DataImportStepInterface
    {
        return new ConcreteSkuValidationStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantProductOfferStep(): DataImportStepInterface
    {
        return new MerchantProductOfferStep();
    }
}

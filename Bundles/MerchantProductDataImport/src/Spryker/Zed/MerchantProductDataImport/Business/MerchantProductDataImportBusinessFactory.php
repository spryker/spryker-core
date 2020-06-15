<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\MerchantProductDataImport\Business\MerchantProduct\Step\MerchantProductAbstractWriterStep;
use Spryker\Zed\MerchantProductDataImport\Business\MerchantProduct\Step\MerchantReferenceToIdMerchantStep;
use Spryker\Zed\MerchantProductDataImport\Business\MerchantProduct\Step\ProductAbstractSkuToIdProductAbstractStep;

/**
 * @method \Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig getConfig()
 * @method \Spryker\Zed\MerchantProductDataImport\Persistence\MerchantProductDataImportEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantProductDataImport\Persistence\MerchantProductDataImportRepositoryInterface getRepository()
 */
class MerchantProductDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getMerchantProductDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getMerchantProductDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createMerchantReferenceToIdMerchantStep())
            ->addStep($this->createProductAbstractSkuToIdProductAbstractStep())
            ->addStep($this->createMerchantProductAbstractWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantReferenceToIdMerchantStep(): DataImportStepInterface
    {
        return new MerchantReferenceToIdMerchantStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantProductAbstractWriterStep(): DataImportStepInterface
    {
        return new MerchantProductAbstractWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductAbstractSkuToIdProductAbstractStep(): DataImportStepInterface
    {
        return new ProductAbstractSkuToIdProductAbstractStep();
    }
}

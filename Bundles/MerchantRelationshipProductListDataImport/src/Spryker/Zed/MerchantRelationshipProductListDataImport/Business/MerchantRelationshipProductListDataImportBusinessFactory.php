<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\MerchantRelationshipProductListDataImport\Business\Model\MerchantRelationshipProductListWriterStep;
use Spryker\Zed\MerchantRelationshipProductListDataImport\Business\Model\Step\MerchantRelationshipKeyToIdMerchantRelationshipStep;
use Spryker\Zed\MerchantRelationshipProductListDataImport\Business\Model\Step\ProductListKeyToIdProductListStep;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductListDataImport\MerchantRelationshipProductListDataImportConfig getConfig()
 */
class MerchantRelationshipProductListDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function createMerchantRelationshipProductListDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getMerchantRelationshipProductListDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createMerchantRelationshipKeyToIdMerchantRelationshipStep())
            ->addStep($this->createProductListKeyToIdProductListStep())
            ->addStep(new MerchantRelationshipProductListWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantRelationshipKeyToIdMerchantRelationshipStep(): DataImportStepInterface
    {
        return new MerchantRelationshipKeyToIdMerchantRelationshipStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductListKeyToIdProductListStep(): DataImportStepInterface
    {
        return new ProductListKeyToIdProductListStep();
    }
}

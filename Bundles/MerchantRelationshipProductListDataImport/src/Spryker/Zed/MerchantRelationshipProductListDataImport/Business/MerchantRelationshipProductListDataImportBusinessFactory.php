<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantRelationshipProductListDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\MerchantRelationshipProductListDataImport\Business\Model\MerchantRelationshipProductListWriterStep;
use Spryker\Zed\MerchantRelationshipProductListDataImport\Business\Model\Step\MerchantRelationshipKeyToIdMerchantRelationshipStep;
use Spryker\Zed\MerchantRelationshipProductListDataImport\Business\Model\Step\ProductListKeyToIdProductListStep;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductListDataImport\MerchantRelationshipProductListDataImportConfig getConfig()
 * @method \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware createTransactionAwareDataSetStepBroker($bulkSize = null)
 * @method \Spryker\Zed\DataImport\Business\Model\DataImporter getCsvDataImporterFromConfig(\Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer)
 */
class MerchantRelationshipProductListDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createMerchantRelationshipProductListDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getMerchantRelationshipProductListDataImporterConfiguration(),
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

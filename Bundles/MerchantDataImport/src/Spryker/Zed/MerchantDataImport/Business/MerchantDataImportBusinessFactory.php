<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\MerchantDataImport\Business\MerchantStore\Step\MerchantReferenceToIdMerchantStep;
use Spryker\Zed\MerchantDataImport\Business\MerchantStore\Step\MerchantStoreWriterStep;
use Spryker\Zed\MerchantDataImport\Business\MerchantStore\Step\StoreNameToIdStoreStep;
use Spryker\Zed\MerchantDataImport\Business\Model\DataSet\MerchantDataSetInterface;
use Spryker\Zed\MerchantDataImport\Business\Model\MerchantWriterStep;
use Spryker\Zed\MerchantDataImport\Dependency\Facade\MerchantDataImportToMerchantFacadeInterface;
use Spryker\Zed\MerchantDataImport\MerchantDataImportDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantDataImport\MerchantDataImportConfig getConfig()
 */
class MerchantDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createMerchantDataImport(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $dataImporterConfigurationTransfer ?: $this->getConfig()->getMerchantDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createLocalizedAttributesExtractorStep([
                MerchantDataSetInterface::URL,
            ]))
            ->addStep(new MerchantWriterStep(
                $this->getEventFacade(),
                $this->getMerchantFacade(),
            ));

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createMerchantStoreDataImport(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterInterface {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $dataImporterConfigurationTransfer ?: $this->getConfig()->getMerchantStoreDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createMerchantReferenceToIdMerchantStep())
            ->addStep($this->createStoreNameToIdStoreStep())
            ->addStep(new MerchantStoreWriterStep());

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
    public function createStoreNameToIdStoreStep(): DataImportStepInterface
    {
        return new StoreNameToIdStoreStep();
    }

    /**
     * @return \Spryker\Zed\MerchantDataImport\Dependency\Facade\MerchantDataImportToMerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantDataImportToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(MerchantDataImportDependencyProvider::FACADE_MERCHANT);
    }
}

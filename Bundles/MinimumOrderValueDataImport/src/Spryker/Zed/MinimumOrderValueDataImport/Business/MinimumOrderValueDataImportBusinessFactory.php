<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MinimumOrderValueDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\MinimumOrderValueDataImport\Business\Model\DataImportStep\MinimumOrderValueWriterStep;
use Spryker\Zed\MinimumOrderValueDataImport\Dependency\Facade\MinimumOrderValueDataImportToCurrencyFacadeInterface;
use Spryker\Zed\MinimumOrderValueDataImport\Dependency\Facade\MinimumOrderValueDataImportToMinimumOrderValueFacadeInterface;
use Spryker\Zed\MinimumOrderValueDataImport\Dependency\Facade\MinimumOrderValueDataImportToStoreFacadeInterface;
use Spryker\Zed\MinimumOrderValueDataImport\MinimumOrderValueDataImportDependencyProvider;

/**
 * @method \Spryker\Zed\MinimumOrderValueDataImport\MinimumOrderValueDataImportConfig getConfig()
 */
class MinimumOrderValueDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function createMinimumOrderValueDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getMinimumOrderValueDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(
            new MinimumOrderValueWriterStep(
                $this->getMinimumOrderValueFacade(),
                $this->getStoreFacade(),
                $this->getCurrencyFacade()
            )
        );

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueDataImport\Dependency\Facade\MinimumOrderValueDataImportToMinimumOrderValueFacadeInterface
     */
    protected function getMinimumOrderValueFacade(): MinimumOrderValueDataImportToMinimumOrderValueFacadeInterface
    {
        return $this->getProvidedDependency(MinimumOrderValueDataImportDependencyProvider::FACADE_MINIMUM_ORDER_VALUE);
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueDataImport\Dependency\Facade\MinimumOrderValueDataImportToStoreFacadeInterface
     */
    protected function getStoreFacade(): MinimumOrderValueDataImportToStoreFacadeInterface
    {
        return $this->getProvidedDependency(MinimumOrderValueDataImportDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueDataImport\Dependency\Facade\MinimumOrderValueDataImportToCurrencyFacadeInterface
     */
    protected function getCurrencyFacade(): MinimumOrderValueDataImportToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(MinimumOrderValueDataImportDependencyProvider::FACADE_CURRENCY);
    }
}
